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
            if ($services->isEmpty()) {
             return "";
            }
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

            $servicesForm .=  '<div class="calendar-wrap hidden d-none">
                    <div class="w-full flex items-center justify-between">
                        <div class="pre-button flex items-center justify-center">
                            <i class="fa fa-chevron-left"></i>
                        </div>
                        <h5 id="month-name" class="text-center mt-[-10px]"></h5>
                        <div class="next-button flex items-center justify-center">
                            <i class="fa fa-chevron-right"></i>
                        </div>
                    </div>
                    <table id="calendar" class="table-auto w-full mt-4">
                        <thead>
                            <tr>
                                <th class="px-2 py-1 text-sm font-medium text-center">Sun</th>
                                <th class="px-2 py-1 text-sm font-medium text-center">Mon</th>
                                <th class="px-2 py-1 text-sm font-medium text-center">Tue</th>
                                <th class="px-2 py-1 text-sm font-medium text-center">Wed</th>
                                <th class="px-2 py-1 text-sm font-medium text-center">Thu</th>
                                <th class="px-2 py-1 text-sm font-medium text-center">Fri</th>
                                <th class="px-2 py-1 text-sm font-medium text-center">Sat</th>
                            </tr>
                        </thead>
                        <tbody>
                                <tr>
                                    <td class="px-4 py-2 text-center"></td>
                                    <td class="px-4 py-2 text-center"></td>
                                    <td class="px-4 py-2 text-center"></td>
                                    <td class="px-4 py-2 text-center"></td>
                                    <td class="px-4 py-2 text-center"></td>
                                    <td class="px-4 py-2 text-center"></td>
                                    <td class="px-4 py-2 text-center"></td>
                                </tr>
                                <tr>
                                    <td class="px-4 py-2 text-center"></td>
                                    <td class="px-4 py-2 text-center"></td>
                                    <td class="px-4 py-2 text-center"></td>
                                    <td class="px-4 py-2 text-center"></td>
                                    <td class="px-4 py-2 text-center"></td>
                                    <td class="px-4 py-2 text-center"></td>
                                    <td class="px-4 py-2 text-center"></td>
                                </tr>
                                <tr>
                                    <td class="px-4 py-2 text-center"></td>
                                    <td class="px-4 py-2 text-center"></td>
                                    <td class="px-4 py-2 text-center"></td>
                                    <td class="px-4 py-2 text-center"></td>
                                    <td class="px-4 py-2 text-center"></td>
                                    <td class="px-4 py-2 text-center"></td>
                                    <td class="px-4 py-2 text-center"></td>
                                </tr>
                                <tr>
                                    <td class="px-4 py-2 text-center"></td>
                                    <td class="px-4 py-2 text-center"></td>
                                    <td class="px-4 py-2 text-center"></td>
                                    <td class="px-4 py-2 text-center"></td>
                                    <td class="px-4 py-2 text-center"></td>
                                    <td class="px-4 py-2 text-center"></td>
                                    <td class="px-4 py-2 text-center"></td>
                                </tr>
                                <tr>
                                    <td class="px-4 py-2 text-center"></td>
                                    <td class="px-4 py-2 text-center"></td>
                                    <td class="px-4 py-2 text-center"></td>
                                    <td class="px-4 py-2 text-center"></td>
                                    <td class="px-4 py-2 text-center"></td>
                                    <td class="px-4 py-2 text-center"></td>
                                    <td class="px-4 py-2 text-center"></td>
                                </tr>
                                <tr>
                                    <td class="px-4 py-2 text-center"></td>
                                    <td class="px-4 py-2 text-center"></td>
                                    <td class="px-4 py-2 text-center"></td>
                                    <td class="px-4 py-2 text-center"></td>
                                    <td class="px-4 py-2 text-center"></td>
                                    <td class="px-4 py-2 text-center"></td>
                                    <td class="px-4 py-2 text-center"></td>
                                </tr>
                        </tbody>
                    </table>
                </div>';


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
