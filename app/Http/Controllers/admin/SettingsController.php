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

        $datetimeFormats = [
            'd-m-Y H:i' => 'DD-MM-YYYY HH:MM',
            'm-d-Y h:i A' => 'MM-DD-YYYY HH:MM AM/PM',
            'Y-m-d H:i:s' => 'YYYY-MM-DD HH:MM:SS',
        ];

        $timezones = \DateTimeZone::listIdentifiers();

        // Load settings as key => value
        $settings = \App\Models\Setting::pluck('value', 'key')->toArray();

        return view('admin.settings.index', compact('phoneCountries', 'dateFormats', 'datetimeFormats', 'timezones', 'settings'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'date_format' => 'required',
            'datetime_format' => 'required',
            'timezone' => 'required',
            'code' => 'required|string',
            'owner_phone_number' => 'required|string',
            'owner_email' => 'required|email',
            'website_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif',
            'favicon' => 'nullable|image|mimes:jpeg,png,jpg,gif',
        ]);

        $settings = [
            'date_format' => $request->date_format,
            'datetime_format' => $request->datetime_format,
            'timezone' => $request->timezone,
            'owner_country_code' => $request->code,
            'owner_phone_number' => $request->owner_phone_number,
            'owner_email' => $request->owner_email,
            'facebook' => $request->facebook,
            'linkedin' => $request->linkedin,
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

        return redirect()->back()->with('success', 'Settings saved successfully!');
    }
}
