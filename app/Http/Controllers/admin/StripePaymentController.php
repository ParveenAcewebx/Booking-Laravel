<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Service;
use App\Models\Vendor;
use App\Models\User;
use App\Models\Transaction;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Stripe\StripeClient;

class StripePaymentController extends Controller
{
    protected string $siteTitle;
    public function __construct()
    {
        $this->siteTitle = get_setting('site_title');
    }
    public function stripeCheckout(Request $request)
    {
        $booking = Booking::find($request->booking_id);
        $service = Service::find($booking->service_id);
        $vendor = Vendor::find($service->vendor_id);
        $secretKey = $vendor->stripe_test_secret_key;
        Stripe::setApiKey($secretKey);
        $slots = json_decode($booking->bookslots, true);
        $totalAmount = 0;
        $serviceName = $service->name; 
        if (!empty($slots)) {
            foreach ($slots as $slot) {
                $price = floatval(str_replace(['$'], '', $slot['price']));
                $totalAmount += $price;
            }
            $serviceName = "Booking Payment (" . count($slots) . " Services)";
        } else {
            $totalAmount = $service->price;
        }
        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'USD',
                    'unit_amount' => intval($totalAmount * 100),
                    'product_data' => [
                        'name' => $serviceName,
                    ],
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('stripe.checkout.success')
                . '?session_id={CHECKOUT_SESSION_ID}'
                . '&vendor_id=' . $vendor->id
                . '&booking_id=' . $booking->id,
            'cancel_url' => session('return_form_url') ?? url('/'),
        ]);
        return redirect()->away($session->url);
    }
    public function stripeCheckoutSuccess(Request $request)
    {
        $sessionId = $request->session_id;
        $vendorId  = $request->vendor_id;
        $bookingId = $request->booking_id;
        $booking = Booking::find($bookingId);
        $service = Service::find($booking->service_id);
        $vendor  = Vendor::find($vendorId);
        $secretKey = $vendor->stripe_test_secret_key;
        $stripe = new StripeClient($secretKey);
        $session = $stripe->checkout->sessions->retrieve($sessionId);
        $paymentId = $session->payment_intent;
        $paidAmount = $session->amount_total / 100;
        $booking->update([
            'status' => 'completed',
            'amount' => $paidAmount,
        ]);
        Transaction::create([
            'booking_id'   => $booking->id,
            'customer_id'  => $booking->customer_id,
            'template_id' =>  $booking->booking_template_id,
            'vendor_id'    => $service->vendor_id,  
            'payment_id'   => $paymentId,
            'status'       => $booking->status,
            'amount'       => $paidAmount,
            'total_balance'=> $paidAmount,
            'currency'     => 'USD',
            'response'     => $session,
        ]);
        return redirect(session('return_form_url') ?? '/')
            ->with('success', 'Your booking is confirmed successfully!');
    }
    public function stripeRefund(Request $request)
    {
        $transaction = Transaction::find($request->id);
        $remainingAmount = $transaction->amount;
        $refundedAmount  = $transaction->refunded_amount;
        if ($remainingAmount <= 0) {
            return response()->json([
                'success' => false,
                'message' => 'Transaction already refunded'
            ]);
        }
        $refundAmount = $remainingAmount;
        if ($request->refund_type === 'partial') {
            $refundAmount = $request->refund_amount;
            if ($refundAmount <= 0 || $refundAmount > $remainingAmount) {
                return response()->json([
                    'success' => false,
                    'message' => 'Refund amount cannot be greater than remaining balance'
                ]);
            }
        } else if ($request->refund_type === 'full') {
            $refundAmount = $remainingAmount;
        }
        $vendor = Vendor::find($transaction->vendor_id);
        if (!$vendor || !$vendor->stripe_test_secret_key) {
            return response()->json([
                'success' => false,
                'message' => 'Stripe credentials not found'
            ]);
        }
        $stripe = new StripeClient($vendor->stripe_test_secret_key);
        $refund = $stripe->refunds->create([
            'payment_intent' => $transaction->payment_id,
            'amount' => intval($refundAmount * 100),
        ]);
        if ($refund->status === 'succeeded') {
            $transaction->refunded_amount = $refundedAmount + $refundAmount;
            $transaction->amount = $remainingAmount - $refundAmount;
            if ($transaction->amount <= 0) {
                $transaction->status = 'refunded';
            } else {
                $transaction->status = 'partial_refund';
            }
            $transaction->save();
            $user = User::find($transaction->customer_id);
            if ($user) {
                $macros = [
                    '{SITE_TITLE}' => $this->siteTitle,
                    '{USER_NAME}' => $user->name,
                    '{ORDER_ID}' => $transaction->id,
                    '{REFUND_AMOUNT}' => number_format($refundAmount, 2),
                    '{AMOUNT}' => number_format($transaction->amount, 2),
                    '{TOTAL_AMOUNT}' => number_format($transaction->total_balance, 2),
                ];
                if ($request->refund_type === 'partial') {
                    $templateSlug = 'refund_partial_payment';
                } else {
                    $templateSlug = 'refund_full_payment';
                }

                refundmailtemplate($templateSlug, $user->email, $macros);
            }

            return response()->json([
                'success' => true,
                'message' => 'Refund successful',
                'remaining_amount' => $transaction->amount,
                'refunded_amount' => $transaction->refunded_amount,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Refund failed'
        ]);
    }
}
