<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class UpdateSmtpSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        $settings = [
            [
                'key' => 'mailer',
                'value' => 'smtp',
            ],
            [
                'key' => 'host',
                'value' => 'smtp.gmail.com',
            ],
            [
                'key' => 'port',
                'value' => '587',
            ],
            [
                'key' => 'username',
                'value' => 'keshav.acewebx@gmail.com',
            ],
            [
                'key' => 'password',
                'value' => 'jknqaqfjtadvjmdb',
            ],
            [
                'key' => 'encryption',
                'value' => 'tls',
            ],
            [
                'key' => 'from_address',
                'value' => 'keshav.acewebx@gmail.com',
            ],
            [
                'key' => 'from_name',
                'value' => 'laravel',
            ],
        ];

        foreach ($settings as $setting) {
            DB::table('settings')->updateOrInsert(
                ['key' => $setting['key']],
                [
                    'value' => $setting['value'],
                    'updated_at' => $now,
                    'created_at' => $now, // Optional: only needed if inserting
                ]
            );
        }
    }
}
