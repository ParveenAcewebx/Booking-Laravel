<?php

namespace App\Providers;

use App\Helpers\Shortcode;
use Illuminate\Support\ServiceProvider;
use App\Models\Service;

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
        Shortcode::register('services', function ($shortcodeAttrs, $class) {
            $services = Service::all();
            $selectedService = $shortcodeAttrs['service'] ?? '';
            $selectedvendor = $shortcodeAttrs['vendor'] ?? '';
            $c = $class;
            $servicesForm  = '';
            $servicesForm .= "<div class='form-group {$c['group']}'>";
            $servicesForm .= "<label for='service' class='{$c['label']}'>Select Service <span class='text-red-500'>*</span></label>";
            $servicesForm .= "<select name='dynamic[service]' class='get_service_staff {$c['select']}' required>";
            $servicesForm .= '<option>---Select Service---</option>';
            foreach ($services as $service) {
                $attributes = $service->getAttributes();
                if ($attributes['status'] === 1) {
                    $selected = $attributes['id'] == $selectedService ? 'selected' : '';  // Check if the service is selected
                    $servicesForm .= "<option value='{$attributes['id']}' {$selected}>{$attributes['name']}</option>";
                }
            }
            $servicesForm .= "</select>";
            $servicesForm .= "</div>";
            $servicesForm .= "<div class='form-group {$c['group']} select_service_vendor {$c['hidden']}'>";
            $servicesForm .= "<label for='staff' class='{$c['label']}'>Select Vendor <span class='text-red-500'>*</span></label>";
            $servicesForm .= "<input type='hidden' class='selected_vendor' value='" . $selectedvendor . "'>";
            $servicesForm .= "<select name='dynamic[vendor]' id='service_vendor_form' class='{$c['select']} service_vendor_form' required>";
            $servicesForm .= '<option value="">---Select Vendor---</option>';

            $servicesForm .= "</select>";
            $servicesForm .= "</div>";

            return $servicesForm;
        });

        Shortcode::register('user-information', function ($shortcodeAttrs, $class) {
            $c = $class;
            // dd($shortcodeAttrs);
            $firstName = $shortcodeAttrs['first_name'] ?? '';
            $lastName  = $shortcodeAttrs['last_name'] ?? '';
            $email     = $shortcodeAttrs['email'] ?? '';
            $phone     = $shortcodeAttrs['phone'] ?? '';
            $userForm  = '';

            $userForm .= "<div class='form-group {$c['group']}'>";
            $userForm .= "<label for='first_name'class='{$c['label']}'>First Name <span class='text-red-500'>*</span></label>";
            $userForm .= "<input type='text' name='dynamic[first_name]' id='first_name' class='form-control {$c['input']}' value='" . e($firstName) . "' placeholder='First Name' required>";
            $userForm .= "</div>";

            $userForm .= "<div class='form-group {$c['group']}'>";
            $userForm .= "<label for='last_name' class='{$c['label']}'>Last Name <span class='text-red-500'>*</span></label>";
            $userForm .= "<input type='text' name='dynamic[last_name]' id='last_name' class='form-control {$c['input']}' value='" . e($lastName) . "' placeholder='Last Name' required>";
            $userForm .= "</div>";

            $userForm .= "<div class='form-group {$c['group']}' >";
            $userForm .= "<label for='email'class='{$c['label']}'>Email <span class='text-red-500'>*</span></label>";
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
