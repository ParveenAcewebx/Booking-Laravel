<?php

namespace App\Http\Controllers\frontend;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Subscription;
use Illuminate\Support\Facades\Validator;


class SubscriptionsController extends Controller
{

    public function index(Request $request)
{
    $validator = Validator::make($request->all(), [
       'email' => [
            'required',
            'email',
            'regex:/^[\w\.-]+@([\w-]+\.)+[\w-]{2,4}$/'
        ],
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status' => 'error',
            'message' => $validator->errors()->first('email'),
        ], 422);
    }

    $subscription = Subscription::create([
        'email' => $request->email,
    ]);

    $macros = [
        '{USER_NAME}' => $subscription->email,
        '{SITE_TITLE}' => get_setting('site_title'),
    ];
    sendSubscriptionTemplateEmail('subscription_email_notification', $subscription->email, $macros);

    return response()->json([
        'status' => 'success',
        'message' => 'Thank you for subscribing!',
    ]);
}
}
