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
        ];

        foreach ($smtpSettings as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }
        
        return back()->with('success', 'Settings Updated Successfully.');
    }
}
