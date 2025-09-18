<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Contact; 

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
            'captcha' => 'required|captcha',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                             ->withErrors($validator)
                             ->withInput();
        }

        Contact::create([
            'name'    => $request->name,
            'email'   => $request->email,
            'phone'   => $request->phone,
            'message' => $request->message
        ]);

        return redirect()->back()->with('success', 'Enquiry submitted Successfully.');
    }

    public function refreshCaptcha()
    {
        return response()->json(['captcha'=> captcha_img('flat')]);
    }
}
