<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Service;
use App\Models\Vendor;
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
        $currency = strtolower(trim($service->currency));
        $map = [
            '$'  => 'usd',
            '₹'  => 'inr',
        ];
        if (isset($map[$currency])) {
            $currency = $map[$currency];
        }
        $amount = intval($service->price * 100);
        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => $currency,
                    'unit_amount' => $amount,
                    'product_data' => [
                        'name' => $service->name,
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
        $vendor = Vendor::find($vendorId);
        $secretKey = $vendor->stripe_test_secret_key;
        $stripe = new StripeClient($secretKey);
        Booking::where('id', $bookingId)->update([
            'status' => 'confirmed'
        ]);
        return redirect(session('return_form_url') ?? '/')
            ->with('success', 'Your booking is confirmed successfully!');
    }
}
