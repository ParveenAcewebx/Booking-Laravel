<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Service;
use App\Models\Vendor;
use App\Models\Transaction;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Stripe\StripeClient;

class StripePaymentController extends Controller
{
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
            'currency'     => 'USD',
            'response'     => $session,
        ]);
        return redirect(session('return_form_url') ?? '/')
            ->with('success', 'Your booking is confirmed successfully!');
    }
    public function stripeRefund(Request $request)
    {
        $transaction = Transaction::where('id', $request->id)->first();
        if ($transaction->status === 'refunded') {
            return redirect()->back();
        }
        $vendor = Vendor::find($transaction->vendor_id);
        $stripe = new StripeClient($vendor->stripe_test_secret_key);
        $refund = $stripe->refunds->create([
            'payment_intent' => $transaction->payment_id,
        ]);
        if ($refund->status === 'succeeded') {
            $transaction->update([
                'status' => 'refunded',
            ]);
            return redirect()->back()->with('success', 'Payment refunded successfully.');
        }
    }
}
