<?php

namespace App\Providers;
use App\Helpers\Shortcode;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Shortcode::register('hello', function ($attrs) {
            return 'Hello, World!' .($attrs['junior']);
        });

        Shortcode::register('user', function ($attrs) {
            return 'Hello, ' . ($attrs['name'] ?? 'Guest');
        });
    }
}
