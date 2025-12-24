<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SettingsTableSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        $settings = [
            ['key' => 'date_format', 'value' => 'd-m-Y'],
            ['key' => 'time_format', 'value' => 'h:i A'],
            ['key' => 'timezone', 'value' => 'Asia/Kolkata'],
            ['key' => 'owner_country_code', 'value' => '+91'],
            ['key' => 'owner_phone_number', 'value' => '1234567890'],
            ['key' => 'owner_email', 'value' => 'admin@example.com'],
            ['key' => 'site_title', 'value' => 'Universal booking'],
            ['key' => 'facebook', 'value' => null],
            ['key' => 'linkedin', 'value' => null],
            ['key' => 'twitter', 'value' => null],
            ['key' => 'website_logo', 'value' => null],
            ['key' => 'favicon', 'value' => null],
            ['key' => 'mailer', 'value' => 'smtp'],
            ['key' => 'host', 'value' => 'smtp.gmail.com'],
            ['key' => 'port', 'value' => '587'],
            ['key' => 'username', 'value' => 'ashish.acewebx@gmail.com'],
            ['key' => 'password', 'value' => 'ozdrhjwasqwrorks'],
            ['key' => 'encryption', 'value' => 'tls'],
            ['key' => 'from_address', 'value' => 'ashish.acewebx@gmail.com'],
            ['key' => 'from_name', 'value' => 'Booking-laravel'],
            ['key' => 'google_login_enabled', 'value' => '0'],
            ['key' => 'facebook_login_enabled', 'value' => '0'],
        ];

        foreach ($settings as $setting) {
            DB::table('settings')->updateOrInsert(
                ['key' => $setting['key']],
                [
                    'value' => $setting['value'],
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            );
        }
    }
}
