<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;;

use App\Models\VendorStaffAssociation;
use App\Models\VendorServiceAssociation;
use App\Models\User;
use App\Models\Service;
use App\Models\Booking;
use App\Models\BookingTemplate;


class VendorInformationController extends Controller
{
    public function view()
    {
        // Check if user is logged in
        if (Auth::check()) {
            $currentUserId = Auth::user()->id;
            $vendorId = VendorStaffAssociation::where('user_id', $currentUserId)->value('vendor_id');
            if ($vendorId) {
                $userIds = VendorStaffAssociation::where('vendor_id', $vendorId)
                    ->where('user_id', '!=', $currentUserId)
                    ->pluck('user_id')
                    ->toArray();

                $staffdata = User::whereIn('id', $userIds)->get();

                // Get vendor services
                $serviceIds = VendorServiceAssociation::with('vendor')
                    ->where('vendor_id', $vendorId)
                    ->pluck('service_id');
                $servicedata = Service::whereIn('id', $serviceIds)->get();
                $bookingdata = Booking::where('vendor_id', $vendorId)->get();
                $bookingTemplateIds = $bookingdata->pluck('booking_template_id')->unique();

                $bookingtemplatedata = collect();
                if ($bookingTemplateIds->isNotEmpty()) {
                    $bookingtemplatedata = BookingTemplate::whereIn('id', $bookingTemplateIds)->get();
                }

                return view(
                    'frontend.vendor.view',
                    compact('staffdata', 'servicedata', 'bookingdata', 'bookingtemplatedata')
                );
            }
        }
        return redirect()->route('login')->with('error', 'Unauthorized access.');
    }
}
