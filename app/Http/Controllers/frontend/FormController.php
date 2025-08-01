<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use App\Helpers\Shortcode;

use Illuminate\Http\Request;
use App\Models\BookingTemplate;
use App\Models\Booking;
use App\Models\User;
use App\Helpers\FormHelper;
use Illuminate\Support\Facades\Storage;
use App\Models\service;
use App\Models\StaffServiceAssociation;
use App\Models\VendorStaffAssociation;
use App\Models\VendorServiceAssociation;
use App\Models\Vendor;
use App\Models\Staff;
use Carbon\Carbon;




class FormController extends Controller
{
    public function show(Request $request, $slug)
    {
        $template = BookingTemplate::where('slug', $slug)->firstOrFail();
        $formHtml = FormHelper::renderDynamicFieldHTML($template->data, [], 'tailwind');
        // Check if request has source=iframe
        $isIframe = $request->query('source') === 'iframe';
        return view('frontend.form.show', compact('formHtml', 'template', 'isIframe'));
    }

    public function store(Request $request, $slug)
    {
        $template = BookingTemplate::findOrFail($slug);
        $bookingData = json_decode($request->input('booking_data'), true) ?? [];
        $inputData = $request->input('dynamic', []);
        $files = $request->file('dynamic', []);
        foreach ($inputData as $key => $val) {
            $bookingData[$key] = $val;
        }

        if (is_array($files)) {
            foreach ($files as $key => $fileInput) {
                if (is_array($fileInput)) {
                    $paths = [];
                    foreach ($fileInput as $file) {
                        if ($file && $file->isValid()) {
                            $paths[] = $file->store('bookings', 'public');
                        }
                    }
                    $bookingData[$key] = $paths;
                } elseif ($fileInput && $fileInput->isValid()) {
                    $bookingData[$key] = $fileInput->store('bookings', 'public');
                }
            }
        }

        $lastInsertedId = '0';
        if (!empty($bookingData['first_name']) && !empty($bookingData['last_name'])) {

            $user = User::create([
                'name' => $bookingData['first_name'] . ' ' . $bookingData['last_name'],
                'email' => $bookingData['email'],
                'phone_number' => $bookingData['phone'],
                'password' => bcrypt($request->password),
                'avatar' => '',
                'status' => $request->has('status') ? config('constants.status.active') : config('constants.status.inactive'),
            ]);
            $user->assignRole('customer');
            $lastInsertedId = $user->id;
        }

        Booking::create([
            'booking_template_id'       => $template->id,
            'customer_id'               => auth()->id() ?? $lastInsertedId,
            'booking_datetime'          => $request->input('booking_datetime', now()),
            'selected_staff'            => $request->input('selected_staff', 'Staff User'),
            'first_name'                => $bookingData['first_name'] ?? NULL,
            'last_name'                 => $bookingData['last_name'] ?? NULL,
            'phone_number'              => $bookingData['phone'] ?? NULL,
            'email'                     => $bookingData['email'] ?? NULL,
            'booking_data'              => json_encode($bookingData),

        ]);

        return redirect()
            ->route('form.show', $template->slug)
            ->with('success', 'Form submitted successfully!');
    }

    function getservicesstaff(Request $request)
    {
        $vendor_data = [];
        if ($request) {
            $serviceId = $request->query('service_id');
            $vendorIds = VendorServiceAssociation::where('service_id', $serviceId)->with('vendor')->get();
            foreach ($vendorIds as $vendor) {
                if($vendor->vendor->status===1){   
                    $vendor_data[] = [
                        'id' =>$vendor->vendor->id,
                        'name' => $vendor->vendor->name,
                    ];

                }
            }
        }
        return $vendor_data;
    }
    function getBookingCalender(Request $request)
    {
        if ($request) {
            $workingDates = [];
            $vendor_id = $request['vendor_id'];
            $vendoraiationsstaff = VendorStaffAssociation::where('vendor_id', $vendor_id)->with('staff')->get();
            if ($vendoraiationsstaff->isNotEmpty()) {
                 $workingDates = $vendoraiationsstaff->map(function($association) {
                    $formattedWorkHours = [];
                    $formattedDayOff = [];
                    $workHours = json_decode($association->staff->work_hours, true);
                    $workOff = json_decode($association->staff->days_off, true);       
                    if($workHours){
                        foreach ($workHours as $day => $times) {
                            $startTime = Carbon::createFromFormat('H:i', $times['start']);
                            $endTime = Carbon::createFromFormat('H:i', $times['end']);
                            $formattedWorkHours[$day] = [
                                'start' => $startTime,
                                'end' => $endTime
                            ];
                        }
                    }
                     if($workOff){
                            $formattedDayOff = collect($workOff)->map(function($daysOff) {
                            return $daysOff;  
                            });
                     }
                    return [
                    'Working_day' => $formattedWorkHours,
                    'Dayoff' => $formattedDayOff,
                ];
            })->toArray();
              
               return response()->json([
                'success' => true,
                'data' => $workingDates
            ]);
        }
    }
        return response()->json(['success' => false, 'message' => 'Invalid request']);      
    }
}
