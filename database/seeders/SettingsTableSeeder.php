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

        DB::table('settings')->insert([
            [
                'key' => 'date_format',
                'value' => 'd-m-Y',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'key' => 'time_format',
                'value' => 'h:i A',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'key' => 'timezone',
                'value' => 'Asia/Kolkata',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'key' => 'owner_country_code',
                'value' => '+91',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'key' => 'owner_phone_number',
                'value' => '1234567890',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'key' => 'owner_email',
                'value' => 'admin@example.com',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'key' => 'site_title',
                'value' => 'Universal booking',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'key' => 'facebook',
                'value' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'key' => 'linkedin',
                'value' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'key' => 'twitter',
                'value' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'key' => 'website_logo',
                'value' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'key' => 'favicon',
                'value' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}
