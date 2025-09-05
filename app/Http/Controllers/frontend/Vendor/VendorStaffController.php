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
use App\Models\StaffServiceAssociation;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
class VendorStaffController extends Controller
{

    public function view(){
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

            return view('frontend.vendor.tabs.staff.staff', compact(
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
    }
     public function staffCreate(Request $request) {
         
        $request->validate([
                'name'              => 'required|string|max:255',
                'email'             => 'required|email|unique:users,email',
                'password'          => 'required|string|min:6|confirmed',
                'phone_number'      => 'required|string|max:20',
                'code'              => 'nullable|string|max:10',
                'status'            => 'required|boolean',
                'avatar'            => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
                'assigned_services' => 'nullable|array',
                'assigned_services.*' => 'exists:services,id',
                'working_days'      => 'nullable|array',
                'day_offs'          => 'nullable|array',
            ]);
         

            $avatarPath = null;
            if ($request->hasFile('avatar')) {
                $avatarPath = $request->file('avatar')->store('avatars', 'public');
            }

            $user = User::create([
                'name'         => $request->name,
                'email'        => $request->email,
                'password'     => bcrypt($request->password),
                'avatar'       => $avatarPath,
                'phone_number' => $request->phone_number,
                'phone_code'   => $request->code,
                'status'       => $request->status,
            ]);
            $user->assignRole('Staff');
            $submittedServices = $request->input('assigned_services', []);
            if (is_array($submittedServices)) {
                foreach ($submittedServices as $serviceId) {
                    StaffServiceAssociation::create([
                        'staff_member' => $user->id,
                        'service_id'   => $serviceId,
                    ]);
                }
            }

            $workingHours = [];
            if ($request->has('working_days')) {
                foreach ($request->working_days as $day => $data) {
                    $start = $data['start'] ?? '00:00';
                    $end   = $data['end'] ?? '00:00';
                    if ($start == '00:00' || $end == '00:00') {
                        $start = $end = '00:00';
                    }
                    $workingHours[$day] = [
                        'start'    => $start,
                        'end'      => $end,
                        'services' => $submittedServices ?? [],
                    ];
                }
            }

            $dayOffsGrouped = [];
            if ($request->has('day_offs') && is_array($request->day_offs)) {
                foreach ($request->day_offs as $off) {
                    $label     = $off['offs'] ?? null;
                    $dateRange = $off['date'] ?? null;
                    if ($label && $dateRange) {
                        try {
                            [$start, $end] = explode(' - ', $dateRange);
                            $startDate = Carbon::createFromFormat('F j, Y', trim($start));
                            $endDate   = Carbon::createFromFormat('F j, Y', trim($end));
                            $period    = CarbonPeriod::create($startDate, $endDate);
                            $group = [];
                            foreach ($period as $date) {
                                $group[] = [
                                    'label' => $label,
                                    'date'  => $date->format('F j, Y'),
                                ];
                            }
                            $dayOffsGrouped[] = $group;
                        } catch (\Exception $e) {
                            continue;
                        }
                    }
                }
            }

            Staff::create([
                'user_id'    => $user->id,
                'work_hours' => json_encode($workingHours),
                'days_off'   => json_encode($dayOffsGrouped),
            ]);
            if (Auth::check()) {
                $currentUserId = Auth::user()->id;
                $vendorId = VendorStaffAssociation::where('user_id', $currentUserId)->value('vendor_id');           
                VendorStaffAssociation::create([
                    'user_id'   => $user->id,
                    'vendor_id' => $vendorId,
                ]);
            }
            return redirect()->route('vendor.staff.view')->with('success', 'Staff Created successfully.');
        }

        /*======================================== stafff update ====================================*/
    public function staffUpdate(Request $request, $id){
        $staffUser = User::findOrFail($id);
        $staffData = Staff::where('user_id', $staffUser->id)->first();
        $request->validate([
            'name'              => 'required|string|max:255',
            'email'             => 'required|email|unique:users,email,' . $staffUser->id,
            'password'          => 'nullable|string|min:6|confirmed',
            'phone_number'      => 'required|string|max:20',
            'code'              => 'nullable|string|max:10',
            'status'            => 'required|boolean',
            'avatar'            => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'assigned_services' => 'nullable|array',
            'assigned_services.*' => 'exists:services,id',
            'working_days'      => 'nullable|array',
            'day_offs'          => 'nullable|array',
        ]);
        // handle avatar update
        $avatarPath = $staffUser->avatar;
        if ($request->hasFile('avatar')) {
            if ($avatarPath && Storage::disk('public')->exists($avatarPath)) {
                Storage::disk('public')->delete($avatarPath);
            }
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
        }

        // update user
        $staffUser->update([
            'name'         => $request->name,
            'email'        => $request->email,
            'password'     => $request->filled('password') ? bcrypt($request->password) : $staffUser->password,
            'avatar'       => $avatarPath,
            'phone_number' => $request->phone_number,
            'phone_code'   => $request->code,
            'status'       => $request->status,
        ]);

        // update assigned services
        StaffServiceAssociation::where('staff_member', $staffUser->id)->delete();
        $submittedServices = $request->input('assigned_services', []);
        if (is_array($submittedServices)) {
            foreach ($submittedServices as $serviceId) {
                StaffServiceAssociation::create([
                    'staff_member' => $staffUser->id,
                    'service_id'   => $serviceId,
                ]);
            }
        }

        // working hours
        $workingHours = [];
        if ($request->has('working_days')) {
            foreach ($request->working_days as $day => $data) {
                $start = $data['start'] ?? '00:00';
                $end   = $data['end'] ?? '00:00';
                if ($start == '00:00' || $end == '00:00') {
                    $start = $end = '00:00';
                }
                $workingHours[$day] = [
                    'start'    => $start,
                    'end'      => $end,
                    'services' => $submittedServices ?? [],
                ];
            }
        }

        // day offs
        $dayOffsGrouped = [];
        if ($request->has('day_offs') && is_array($request->day_offs)) {
            foreach ($request->day_offs as $off) {
                $label     = $off['offs'] ?? null;
                $dateRange = $off['date'] ?? null;
                if ($label && $dateRange) {
                    try {
                        [$start, $end] = explode(' - ', $dateRange);
                        $startDate = Carbon::createFromFormat('F j, Y', trim($start));
                        $endDate   = Carbon::createFromFormat('F j, Y', trim($end));
                        $period    = CarbonPeriod::create($startDate, $endDate);
                        $group = [];
                        foreach ($period as $date) {
                            $group[] = [
                                'label' => $label,
                                'date'  => $date->format('F j, Y'),
                            ];
                        }
                        $dayOffsGrouped[] = $group;
                    } catch (\Exception $e) {
                        continue;
                    }
                }
            }
        }

        // update staff table
        if ($staffData) {
            $staffData->update([
                'work_hours' => json_encode($workingHours),
                'days_off'   => json_encode($dayOffsGrouped),
            ]);
        } else {
            Staff::create([
                'user_id'    => $staffUser->id,
                'work_hours' => json_encode($workingHours),
                'days_off'   => json_encode($dayOffsGrouped),
            ]);
        }

        // vendor association
        if (Auth::check()) {
            $currentUserId = Auth::user()->id;
            $vendorId = VendorStaffAssociation::where('user_id', $currentUserId)->value('vendor_id');

            VendorStaffAssociation::updateOrCreate(
                ['user_id' => $staffUser->id],
                ['vendor_id' => $vendorId]
            );
        }

        return redirect()->route('vendor.staff.view')->with('success', 'Staff updated successfully.');
    }
    /*=================== staff delete =======================*/
    public function staffDestroy($id)
    {
        $staff = User::findOrFail($id);
        $staff->delete();               
        return redirect()->route('vendor.staff.view')->with('success', 'Staff member deleted successfully.');
    }


}
