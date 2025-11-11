<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Setting;
use Illuminate\Support\Facades\Config;
class SmtpConfigServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        if ($this->app->runningInConsole()) { return; }
             $settings = Setting::pluck('value', 'key')->toArray();
        if ($settings) {
        $mailConfig = [
               'mail.default' => $settings['mailer'],
                'mail.mailers.smtp.host' => $settings['host'],
                'mail.mailers.smtp.port' => $settings['port'],
                'mail.mailers.smtp.username' => $settings['username'] ,
                'mail.mailers.smtp.password' => $settings['password'] ,
                'mail.mailers.smtp.encryption' => $settings['encryption'],
                'mail.from.address' => $settings['from_address'],
                'mail.from.name' => $settings['from_name'],
            ];
            config($mailConfig);
        }
    }
}
