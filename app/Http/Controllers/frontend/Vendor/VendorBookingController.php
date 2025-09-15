<?php

namespace App\Http\Controllers\Frontend\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;;

use App\Models\VendorStaffAssociation;
use App\Models\VendorServiceAssociation;
use App\Models\User;
use App\Models\Service;
use App\Models\Booking;
use App\Models\BookingTemplate;
use App\Models\Category;
use App\Models\Role;
use App\Models\Staff;
use App\Models\Vendor;
use App\Models\StaffServiceAssociation;

class VendorBookingController extends Controller
{
    
    public function view()
    {
        // Check if user is logged in
        if (Auth::check()) {
            $currentUserId = Auth::user()->id;
            $activeVendor = Vendor::where('status', config('constants.status.active'))->get();
            // Get vendor ID of logged-in user
            $vendorId = VendorStaffAssociation::where('user_id', $currentUserId)->value('vendor_id');
           
            if ($vendorId) {
                // Get all staff (excluding current user) under the same vendor
                $userIds = VendorStaffAssociation::where('vendor_id', $vendorId)
                    ->where('user_id', '!=', $currentUserId)
                    ->pluck('user_id')
                    ->toArray();

                $staffdata = User::whereIn('id', $userIds)
                    ->orderBy('id', 'desc')
                    ->get();

                $staffid = $staffdata->pluck('id');

                if ($staffid) {
                    $staffServices = StaffServiceAssociation::whereIn('staff_member', $staffid)->with('service:id,name')->get()->groupBy('staff_member')->map(function ($items) {
                        return $items->map(function ($item) {
                            return [
                                'id'   => optional($item->service)->id,
                                'name' => optional($item->service)->name,
                            ];
                        })->filter(function ($service) {
                            return !is_null($service['id']);
                        })->toArray();
                    });

                    $StaffworkdaysDayoff = Staff::whereIn('user_id', $staffid)->get()->map(function ($staff) {
                        return [
                            'user_id'   => $staff->user_id,
                            'work_days' => json_decode($staff->work_hours, true),
                            'days_off'  => json_decode($staff->days_off, true),
                        ];
                    });
                }

                // Get vendor services
                $serviceIds = VendorServiceAssociation::with('vendor')
                    ->where('vendor_id', $vendorId)
                    ->pluck('service_id');

                $servicedata = Service::whereIn('id', $serviceIds)->get();

                // Categories
                $categories = Category::select('id', 'category_name')->get();

                // Bookings
                $bookingdata = Booking::where('vendor_id', $vendorId)->orderBy('created_at', 'desc')->paginate(5);
                $bookingTemplateIds = $bookingdata->pluck('booking_template_id')->unique();

                $bookingtemplatedata = collect();
                if ($bookingTemplateIds->isNotEmpty()) {
                    $bookingtemplatedata = BookingTemplate::whereIn('id', $bookingTemplateIds)->get();
                }

                // Constants
                $currencies     = config('constants.currencies');
                $weekDays       = config('constants.week_days');
                $phoneCountries = config('phone_countries');

                return view('frontend.vendor.tabs.bookings.booking', compact(
                    'staffdata',
                    'StaffworkdaysDayoff',
                    'staffServices',
                    'weekDays',
                    'phoneCountries',
                    'servicedata',
                    'categories',
                    'currencies',
                    'bookingdata',
                    'bookingtemplatedata',
                    'activeVendor'
                ));
            }
        }
    }
    public function bookingview($id){
     $booking = Booking::with('template')->findOrFail($id);
        $dynamicValues = json_decode($booking->booking_data, true) ?? [];

        $servicedata = isset($dynamicValues['service'])
            ? Service::where('id', $dynamicValues['service'])->first()
            : null;

        $vendorname = isset($dynamicValues['vendor'])
            ? Vendor::where('id', $dynamicValues['vendor'])->pluck('name')->first()
            : null;

        $serviceverndor = [
            'serivename'      => $servicedata?->name,
            'serviceprice'    => $servicedata?->price,
            'servicurrency'   => $servicedata?->currency,
            'serviceduration' => $servicedata?->duration,
            'vendorname'      => $vendorname,
        ];

        $slotedetail = json_decode($booking->bookslots);
        $formStructureJson = $booking->template->data ?? '[]';
        $formStructureArray = json_decode($formStructureJson, true);
        $formStructureArray = array_filter($formStructureArray, fn($item) => $item['type'] !== 'shortcodeblock');

        $AdditionalInformation = [];

        if (!empty($dynamicValues)) {
            $excludedKeys = ['first_name', 'last_name', 'email', 'phone', 'service', 'vendor'];

            $filteredDynamicValues = array_filter(
                $dynamicValues,
                fn($key) => !in_array($key, $excludedKeys),
                ARRAY_FILTER_USE_KEY
            );

            $filteredKeys = array_keys($filteredDynamicValues);

            $matchedValues = array_map(function ($field) use ($dynamicValues) {
                $name = $field['name'] ?? null;
                if (!$name) return null;
                $value = $dynamicValues[$name] ?? null;

                if ($field['type'] === 'checkbox-group') {
                    $values = (array) ($value ?? []);
                    if (in_array('other', $values)) {
                        $values = array_diff($values, ['other']);
                        if (!empty($dynamicValues[$name . '_other'])) {
                            $otherValues = (array) $dynamicValues[$name . '_other'];
                            $values = array_merge($values, $otherValues);
                        }
                    }
                    return array_values($values);
                }

                if ($field['type'] === 'radio-group') {
                    if ($value === 'other' && !empty($dynamicValues[$name . '_other'])) {
                        return $dynamicValues[$name . '_other'];
                    }
                    return $value;
                }

                return $value;
            }, array_filter($formStructureArray, function ($field) use ($filteredKeys) {
                return !empty($field['name']) && in_array($field['name'], $filteredKeys);
            }));

            $matchedLabels = array_map(
                function ($field) {
                    return isset($field['label']) ? $field['label'] : '';
                },
                array_filter($formStructureArray, function ($field) use ($filteredKeys) {
                    return !empty($field['name']) && in_array($field['name'], $filteredKeys);
                })
            );


            $AdditionalInformation = [
                'AddInfoLabel'       => $matchedLabels,
                'AddInfoValue'       => $matchedValues,
                'formStructureArray' => $formStructureArray,
            ];
        }

        $bookingid = $id ?: '';

        if (!empty($booking->booking_datetime)) {
            $booking->booking_datetime = date('Y-m-d\TH:i', strtotime($booking->booking_datetime));
        }

        $selectedStaffUser = User::where('name', $booking->selected_staff)->first();
        $booking->selected_staff = $selectedStaffUser?->id;

        $loginId = getOriginalUserId();
        $loginUser = $loginId ? User::find($loginId) : null;

        return view('frontend.Vendor.tabs.bookings.view', [
            'bookingid'            => $id,
            'booking'              => $booking,
            'AdditionalInformation' => $AdditionalInformation,
            'userinfo'             => $dynamicValues,
            'serviceverndor'       => $serviceverndor,
            'slotedetail'          => $slotedetail,
            'loginUser'            => $loginUser,
        ]);
    }

    public function bookingdestroy($id)
    {
        $booking = Booking::findOrFail($id);
        $booking->delete();
        return redirect()->route('vendor.bookings.view')->with('success', 'Booking Deleted Successfully.');
    }
}
