<?php

namespace App\Http\Controllers\frontend;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Subscription;


class SubscriptionsController extends Controller
{

    public function index(Request $request)
    {   
        $request->validate([
            'email' => 'required|email',
        ]);

        Subscription::create([
            'email' => $request->email,
        ]);

        $macros = [
            '{USER_EMAIL}' => $request->email,
            '{SITE_TITLE}' => get_setting('site_title'),
        ];
        sendSubscriptionTemplateEmail('subscription_email_notification', $request->email, $macros);
        return redirect()->back()->with('success', 'Thank you For Subscribing!');
    }   

}
