<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Contact;
use Illuminate\Support\Facades\Http;

class ContactController extends Controller
{
    public function listing()
    {
        return view('frontend.ContactPage');
    }

    public function submit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'    => 'required|string|max:255',
            'email'   => 'required|email|max:255',
            'phone'   => 'required|string|max:20',
            'message' => 'required|string|max:2000',
            'g-recaptcha-response' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret'   => get_setting('recaptcha_secret_key'),
            'response' => $request->input('g-recaptcha-response'),
            'remoteip' => $request->ip(),
        ]);

        $result = $response->json();

        if (!($result['success'] ?? false)) {
            return redirect()->back()
                ->withErrors(['captcha' => 'reCAPTCHA verification failed, please try again.'])
                ->withInput();
        }

        Contact::create([
            'name'    => $request->name,
            'email'   => $request->email,
            'phone'   => $request->phone,
            'message' => $request->message
        ]);

        $macros = [
            '{USER_NAME}'  => $request->name,
            '{USER_EMAIL}' => $request->email,
            '{PHONE}'      => $request->phone,
            '{MESSAGE}'    => $request->message,
            '{SITE_TITLE}' => get_setting('site_title'),
        ];

        sendEnquiryCustomerTemplateEmail('enquiry_email_notification', $request->email, $macros);
        sendAdminEnquiryTemplateEmail('admin_enquiry_email_notification', get_setting('owner_email'), $macros);

        return redirect()->back()->with('success', 'Enquiry submitted successfully.');
    }

    public function refreshCaptcha()
    {
        return response()->json(['captcha' => captcha_img('flat')]);
    }
}
