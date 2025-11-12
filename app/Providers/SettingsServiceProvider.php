<?php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class SettingsServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if (Schema::hasTable('settings')) {
            config([
                'services.google.client_id' => get_setting('google_client_id'),
                'services.google.client_secret' => get_setting('google_client_secret'),
                'services.google.redirect' => get_setting('google_redirect_uri'),
            ]);
        }
    }

    public function register()
    {
        //
    }
}
