<?php

namespace App\Providers;

use App\Helpers\Shortcode;
use Illuminate\Support\ServiceProvider;
use App\Models\Service;
use Illuminate\Support\Facades\Artisan;

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
        Shortcode::register('services', function ($shortcodeAttrs, $class) {
            $services = Service::all();
            $c = $class;
            if ($services->isEmpty() || $services->every(function ($service) {
                return $service->status !== 1;
            })) {
                return " No services available at the moment";
            }
            $selectedService = $shortcodeAttrs['service'] ?? '';
            $selectedvendor = $shortcodeAttrs['vendor'] ?? '';
            $c = $class;
            $servicesForm  = '';
            $servicesForm .= "<div class='form-group {$c['group']}'>";
            $servicesForm .= "<label for='service' class='{$c['label']} services-show'>Select Service <span class='text-red-500'>*</span></label>";
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
                    <div class="w-full flex items-center justify-between d-flex w-100 justify-content-between">
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
                    <div class="availibility p-4 m-2 border border-gray-300 shadow-md rounded-lg hidden">
                    <div class="timings"></div>
                    </div>
                </div>';
            if ($c['hidden'] === 'd-none') {
                $servicesForm .= '<div class="d-flex justify-content-end"><button type="button" class="remove-all-slots text-white p-2 m-1 bg-danger border-danger d-none">
                        Remove All
                    </button></div>';
            } else {
                $servicesForm .= '<button type="button" class="remove-all-slots hidden bg-red-600 text-white p-2 float-right m-3 rounded mt-3">
                        Remove All
                    </button>';
            }

            $servicesForm .= '<input type="hidden" name="bookslots" id="bookslots">
         
            <div class="slot-list-wrapper space-y-2">
              <p class="hidden pl-4 mt-2 text-sm text-red-600 font-medium p-4  border border-gray-300 shadow-md rounded-lg select_slots d-none">Please select atleast one slot</p>
            </div>
             ';
            return $servicesForm;
        });

        Shortcode::register('user-information', function ($shortcodeAttrs, $class) {
    $c = $class;

    $firstName = old('dynamic.first_name', $shortcodeAttrs['first_name'] ?? '');
    $lastName  = old('dynamic.last_name',  $shortcodeAttrs['last_name'] ?? '');
    $email     = old('dynamic.email',      $shortcodeAttrs['email'] ?? '');
    $phone     = old('dynamic.phone',      $shortcodeAttrs['phone'] ?? '');

    $errors = session('errors');

    // Helper to get Tailwind error class
    $errorClass = function ($field) use ($errors) {
        return ($errors && $errors->has($field))
            ? 'border-red-500 focus:border-red-500 focus:ring-red-500'
            : 'border-gray-300 focus:border-indigo-500 focus:ring-indigo-500';
    };

    // Helper to get error message HTML
    $errorMessage = function ($field) use ($errors) {
        return ($errors && $errors->has($field))
            ? "<p class='mt-1 text-sm text-red-600'>" . e($errors->first($field)) . "</p>"
            : '';
    };

    $userForm = '';

    // First Name
    $userForm .= "<div class='mb-4 {$c['group']}'>";
    $userForm .= "<label for='first_name' class='block font-medium text-gray-700 {$c['label']}'>First Name <span class='text-red-500'>*</span></label>";
    $userForm .= "<input type='text' name='dynamic[first_name]' id='first_name' value='" . e($firstName) . "' placeholder='First Name' 
        class='mt-1 block w-full rounded-md shadow-sm {$c['input']} " . $errorClass('dynamic.first_name') . "'>";
    $userForm .= $errorMessage('dynamic.first_name');
    $userForm .= "</div>";

    // Last Name
    $userForm .= "<div class='mb-4 {$c['group']}'>";
    $userForm .= "<label for='last_name' class='block font-medium text-gray-700 {$c['label']}'>Last Name <span class='text-red-500'>*</span></label>";
    $userForm .= "<input type='text' name='dynamic[last_name]' id='last_name' value='" . e($lastName) . "' placeholder='Last Name' 
        class='mt-1 block w-full rounded-md shadow-sm {$c['input']} " . $errorClass('dynamic.last_name') . "'>";
    $userForm .= $errorMessage('dynamic.last_name');
    $userForm .= "</div>";

    // Email
    $userForm .= "<div class='mb-4 {$c['group']}'>";
    $userForm .= "<label for='email' class='block font-medium text-gray-700 {$c['label']}'>Email <span class='text-red-500'>*</span></label>";
    $userForm .= "<input type='email' name='dynamic[email]' id='email' value='" . e($email) . "' placeholder='Email Address' 
        class='mt-1 block w-full rounded-md shadow-sm {$c['input']} " . $errorClass('dynamic.email') . "'>";
    $userForm .= $errorMessage('dynamic.email');
    $userForm .= "</div>";

    // Phone
    $userForm .= "<div class='mb-4 {$c['group']}'>";
    $userForm .= "<label for='phone' class='block font-medium text-gray-700 {$c['label']}'>Phone <span class='text-red-500'>*</span></label>";
    $userForm .= "<input type='tel' name='dynamic[phone]' id='phone' value='" . e($phone) . "' placeholder='Phone Number' maxlength='10'
        oninput=\"this.value = this.value.replace(/[^0-9]/g, '')\"
        class='mt-1 block w-full rounded-md shadow-sm {$c['input']} " . $errorClass('dynamic.phone') . "'>";
    $userForm .= $errorMessage('dynamic.phone');
    $userForm .= "</div>";

    return $userForm;
});

    }
}
