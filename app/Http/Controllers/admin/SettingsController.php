<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Models\Setting;
use App\Models\User;
class SettingsController extends Controller
{
    public function index()
    {
        $loginId = getOriginalUserId(); 
        $loginUser = $loginId ? User::find($loginId) : null;

        $phoneCountries = config('phone_countries');
        $dateFormats = [
            'd-m-Y' => 'DD-MM-YYYY',
            'm-d-Y' => 'MM-DD-YYYY',
            'Y-m-d' => 'YYYY-MM-DD',
        ];
        $timeFormats = [
            'H:i' => '24-Hour (e.g. 14:30)',
            'h:i A' => '12-Hour (e.g. 02:30 PM)',
        ];
        $timezones = \DateTimeZone::listIdentifiers();
        $settings = Setting::pluck('value', 'key')->toArray();
        return view('admin.settings.index', compact('phoneCountries', 'dateFormats', 'timeFormats', 'timezones', 'settings','loginUser'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'date_format' => 'required',
            'time_format' => 'required',
            'timezone' => 'required',
            'code' => 'required|string',
            'owner_phone_number' => 'required|string',
            'owner_email' => 'required|email',
            'site_title' => 'required|string',
            'website_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif',
            'favicon' => 'nullable|image|mimes:jpeg,png,jpg,gif',

            'mail_mailer' => 'nullable|string',
            'mail_host' => 'nullable|string',
            'mail_port' => 'nullable|integer',
            'mail_username' => 'nullable|string',
            'mail_password' => 'nullable|string',
            'mail_encryption' => 'nullable|string|in:tls,ssl,null',
            'mail_from_address' => 'nullable|email',
            'mail_from_name' => 'nullable|string',
        ]);

        $settings = [
            'date_format' => $request->date_format,
            'time_format' => $request->time_format,
            'timezone' => $request->timezone,
            'owner_country_code' => $request->code,
            'owner_phone_number' => $request->owner_phone_number,
            'owner_email' => $request->owner_email,
            'site_title' => $request->site_title,
            'facebook' => $request->facebook,
            'linkedin' => $request->linkedin,
            'instagram' => $request->instagram,
            'x_twitter' => $request->x_twitter,
        ];

        // Handle logo upload
        if ($request->hasFile('website_logo')) {
            $path = $request->file('website_logo')->store('logos', 'public');
            $settings['website_logo'] = $path;
        }

        // Handle favicon upload
        if ($request->hasFile('favicon')) {
            $path = $request->file('favicon')->store('favicons', 'public');
            $settings['favicon'] = $path;
        }

        // Handle removal of website logo
        if ($request->remove_website_logo) {
            $settings['website_logo'] = '';
        }

        // Handle removal of favicon
        if ($request->remove_favicon) {
            $settings['favicon'] = '';
        }

        // Save each setting
        foreach ($settings as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }
       
        
         $smtpSettings = [
            'mailer' => $request['mail_mailer'],
            'host' => $request['mail_host'],
            'port' => $request['mail_port'],
            'username' => $request['mail_username'],
            'password' => $request['mail_password'],
            'encryption' => $request['mail_encryption'],
            'from_address' => $request['mail_from_address'],
            'from_name' => $request['mail_from_name'],
            'recaptcha_secret_key' => $request['recaptcha_secret_key'],
            'recaptcha_site_key' => $request['recaptcha_site_key'],
            'google_client_id' => $request['google_client_id'],
            'google_client_secret' => $request['google_client_secret'],
            'google_redirect_uri' => $request['google_redirect_uri'],
        ];
        foreach ($smtpSettings as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }
        
        return back()->with('success', 'Settings Updated Successfully.');
    }
    public function updateGoogleLogin(Request $request)
    {
        $request->validate([
            'google_client_id' => 'nullable|string',
            'google_client_secret' => 'nullable|string',
            'google_redirect_uri' => 'nullable|url',
            'google_login_enabled' => 'nullable',
        ]);

        $googleLoginSettings = [
            'google_client_id' => $request->input('google_client_id'),
            'google_client_secret' => $request->input('google_client_secret'),
            'google_redirect_uri' => $request->input('google_redirect_uri'),
        ];

        foreach ($googleLoginSettings as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        $clientId = $request->input('google_client_id');
        $clientSecret = $request->input('google_client_secret');
        $redirectUri = $request->input('google_redirect_uri');

        if (empty($clientId) || empty($clientSecret) || empty($redirectUri)) {
            Setting::updateOrCreate(['key' => 'google_login_enabled'], ['value' => 0]);
            return back()->with('error', 'Please fill in Google login credentials to enable the toggle.');
        }

        if ($clientId !== '' && $clientSecret !== '' && $redirectUri !== '') {
            $enabled = $request->has('google_login_enabled') && $request->input('google_login_enabled');
            Setting::updateOrCreate(['key' => 'google_login_enabled'], ['value' => $enabled]);

            if ($enabled) {
                return back()->with('success', 'Google login enabled successfully.');
            } else {
                return back()->with('success', 'Google login disabled successfully.');
            }
        }

        Setting::updateOrCreate(['key' => 'google_login_enabled'], ['value' => 0]);
        return back()->with('error', 'Please provide all required Google credentials (Client ID, Secret, and Redirect URI) to enable Google login.');
    }
}



