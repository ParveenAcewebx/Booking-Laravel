<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class StripePaymentController extends Controller
{
    public function stripe()
    {
        return redirect()->back()->with('success', 'Booking successful!');
    }



    public function stripeCheckout(Request $request)
    {
        session(['return_form_url' => url()->previous()]);

        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

        $productName = "Service Booking";
        $amount = 500;

        $session = \Stripe\Checkout\Session::create([
            'line_items' => [[
                'price_data' => [
                    'currency' => 'usd',
                    'unit_amount' => $amount * 100,
                    'product_data' => [
                        'name' => $productName,
                    ],
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('stripe.checkout.success') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => url()->previous(),
        ]);

        return redirect($session->url);
    }


    public function stripeCheckoutSuccess(Request $request)
    {
        if (!$request->session_id) {
            return redirect('/')->with('error', 'Missing session ID');
        }

        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
        $session = $stripe->checkout->sessions->retrieve($request->session_id);

        $formUrl = session('return_form_url', '/');

        return redirect($formUrl)->with('success', 'Booking successful! Your payment was processed.');
    }
}
