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
        Shortcode::register('hello-world', function () {
            return 'Hello, World!';
        });

        Shortcode::register('user-information', function ($shortcodeAttrs, $class) {
            $c = $class;
            $firstName = $shortcodeAttrs['first_name'] ?? '';
            $lastName  = $shortcodeAttrs['last_name'] ?? '';
            $email     = $shortcodeAttrs['email'] ?? '';
            $phone     = $shortcodeAttrs['phone'] ?? '';

            $userForm  = '';

            $userForm .= "<div class='form-group {$c['group']}'>";
            $userForm .= "<label for='first_name'class='{$c['label']}'>First Name</label>";
            $userForm .= "<input type='text' name='dynamic[first_name]' id='first_name' class='form-control {$c['input']}' value='" . e($firstName) . "' placeholder='First Name' required>";
            $userForm .= "</div>";

            $userForm .= "<div class='form-group {$c['group']}'>";
            $userForm .= "<label for='last_name' class='{$c['label']}'>Last Name</label>";
            $userForm .= "<input type='text' name='dynamic[last_name]' id='last_name' class='form-control {$c['input']}' value='" . e($lastName) . "' placeholder='Last Name' required>";
            $userForm .= "</div>";

            $userForm .= "<div class='form-group {$c['group']}' >";
            $userForm .= "<label for='email'class='{$c['label']}'>Email</label>";
            $userForm .= "<input type='email' name='dynamic[email]' id='email' class='form-control {$c['input']}' value='" . e($email) . "' placeholder='Email Address' required>";
            $userForm .= "</div>";

            $userForm .= "<div class='form-group {$c['group']}'>";
            $userForm .= "<label for='phone' class='{$c['label']}'>Phone</label>";
            $userForm .= "<input type='tel' name='dynamic[phone]' id='phone' class='form-control {$c['input']}' value='" . e($phone) . "' placeholder='Phone Number' maxlength='10' oninput=\"this.value = this.value.replace(/[^0-9]/g, '')\">";
            $userForm .= "</div>";

            return $userForm;
        });
    }
}
