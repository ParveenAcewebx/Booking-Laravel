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
                'mail.default' => $settings['mailer'] ?? config('mail.default'),
                'mail.mailers.smtp.host' => $settings['host'] ?? config('mail.mailers.smtp.host'),
                'mail.mailers.smtp.port' => $settings['port'] ?? config('mail.mailers.smtp.port'),
                'mail.mailers.smtp.username' => $settings['username'] ?? config('mail.mailers.smtp.username'),
                'mail.mailers.smtp.password' => $settings['password'] ?? config('mail.mailers.smtp.password'),
                'mail.mailers.smtp.encryption' => $settings['encryption'] ?? config('mail.mailers.smtp.encryption'),
                'mail.from.address' => $settings['from_address'] ?? config('mail.from.address'),
                'mail.from.name' => $settings['from_name'] ?? config('mail.from.name'),
            ];
            config($mailConfig);
        }
    }
}
