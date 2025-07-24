<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SettingsController extends Controller
{
    public function index()
    {
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
        // Load settings as key => value
        $settings = \App\Models\Setting::pluck('value', 'key')->toArray();
        return view('admin.settings.index', compact('phoneCountries', 'dateFormats', 'timeFormats', 'timezones', 'settings'));
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

        if ($request->hasFile('website_logo')) {
            $path = $request->file('website_logo')->store('logos', 'public');
            $settings['website_logo'] = $path;
        }

        if ($request->hasFile('favicon')) {
            $path = $request->file('favicon')->store('favicons', 'public');
            $settings['favicon'] = $path;
        }

        foreach ($settings as $key => $value) {
            \App\Models\Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        return back()->with('success', 'Settings Updated Successfully.');
    }
}
