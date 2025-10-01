<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class GoogleRecapticha_key extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       $settings=[
            [
            'key'=>'recaptcha_site_key',
            'value'=>'6Lf8mdorAAAAAOOUl_9NqlDH8vRf5M6gs3L9LYux',
            ],
            [
                'key'=>'recaptcha_secret_key',
                'value'=>'6Lf8mdorAAAAACI62WSy4f69HyHPySpagxNaAfEa',
            ]
        ];
         foreach ($settings as $setting) {
            DB::table('settings')->updateOrInsert(
                ['key' => $setting['key']],
                ['value' => $setting['value']],
            );
        }
    }
}
