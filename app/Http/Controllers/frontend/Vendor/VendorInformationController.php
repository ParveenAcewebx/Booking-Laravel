<?php

namespace App\Http\Controllers\frontend\Vendor;

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
use App\Models\StaffServiceAssociation;


class VendorInformationController extends Controller
{
    public function view()
{
    // Check if user is logged in
    if (Auth::check()) {
        $currentUserId = Auth::user()->id;
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
            $bookingdata = Booking::where('vendor_id', $vendorId)->get();
            $bookingTemplateIds = $bookingdata->pluck('booking_template_id')->unique();

            $bookingtemplatedata = collect();
            if ($bookingTemplateIds->isNotEmpty()) {
                $bookingtemplatedata = BookingTemplate::whereIn('id', $bookingTemplateIds)->get();
            }

            // Constants
            $currencies     = config('constants.currencies');
            $weekDays       = config('constants.week_days');
            $phoneCountries = config('phone_countries');

            return view('frontend.vendor.view', compact(
                'staffdata',
                'StaffworkdaysDayoff',
                'staffServices',
                'weekDays',
                'phoneCountries',
                'servicedata',
                'categories',
                'currencies',
                'bookingdata',
                'bookingtemplatedata'
            ));
        }
    }

    return redirect()->route('login')->with('error', 'Unauthorized access.');
}
}
