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
use App\Models\Service;
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
                if ($vendor->vendor->status === 1) {
                    $vendor_data[] = [
                        'id' => $vendor->vendor->id,
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
                $workingDates = $vendoraiationsstaff->map(function ($association) {
                    $formattedWorkHours = [];
                    $formattedDayOff = [];
                    $workHours = json_decode($association->staff->work_hours, true);
                    $workOff = json_decode($association->staff->days_off, true);
                    if ($workHours) {
                        foreach ($workHours as $day => $times) {
                            $startTime = Carbon::createFromFormat('H:i', $times['start']);
                            $endTime = Carbon::createFromFormat('H:i', $times['end']);
                            $formattedWorkHours[$day] = [
                                'start' => $startTime,
                                'end' => $endTime
                            ];
                        }
                    }
                    if ($workOff) {
                        $formattedDayOff = collect($workOff)->map(function ($daysOff) {
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

    public function getBookingSlot(Request $request)
    {
        $date = $request->input('dates');
        $formattedDate = Carbon::createFromFormat('Y-m-d', $date)->format('F j, Y');
        $weekday = strtolower(Carbon::createFromFormat('Y-m-d', $date)->format('l'));

        $service = Service::find($request->serviceid);
        if (!$service) {
            return response()->json(['error' => 'Service not found.'], 404);
        }

        $serviceDuration = $service->duration;
        $servicePrice = $service->price ?? 0;
        $serviceCurrency = $service->currency;

        $vendorAssociations = VendorStaffAssociation::with('staff')
            ->where('vendor_id', $request->vendorid)
            ->get();

        $staffAvailability = collect();
        $allStaffSlots = collect(); // all individual staff slots, for availability count
        $allIntervals = []; // to gather all intervals from all staff for merging

        foreach ($vendorAssociations as $association) {
            $staff = $association->staff;
            if (!$staff) continue;

            $daysOffRaw = $staff->days_off ?? '[]';
            $daysOff = json_decode($daysOffRaw, true);
            if (is_array($daysOff) && isset($daysOff[0]) && is_array($daysOff[0])) {
                $daysOff = $daysOff[0];
            }

            $workHoursRaw = $staff->work_hours ?? '{}';
            $workHours = json_decode($workHoursRaw, true);

            if (!isset($workHours[$weekday])) {
                $staff->slots = [];
                $staff->day_start = null;
                $staff->day_end = null;
                $staffAvailability->push($staff);
                continue;
            }

            $intervals = [];
            if (isset($workHours[$weekday][0]) && is_array($workHours[$weekday][0]) && isset($workHours[$weekday][0]['start'])) {
                $intervals = $workHours[$weekday];
            } else {
                $intervals = [$workHours[$weekday]];
            }

            // Check date off
            $dateIsOff = false;
            if (is_array($daysOff)) {
                foreach ($daysOff as $dayOff) {
                    $offDate = is_array($dayOff) && isset($dayOff['date']) ? $dayOff['date'] : (is_string($dayOff) ? $dayOff : null);
                    if ($offDate === $formattedDate) {
                        $dateIsOff = true;
                        break;
                    }
                }
            }

            if ($dateIsOff) {
                $staff->slots = [];
                $staff->day_start = null;
                $staff->day_end = null;
                $staffAvailability->push($staff);
                continue;
            }

            $slots = [];
            $intervalStartTimes = [];
            $intervalEndTimes = [];

            // Save staff intervals also for global merge
            foreach ($intervals as $interval) {
                $startTime = $interval['start'] ?? null;
                $endTime = $interval['end'] ?? null;

                if (!$startTime || !$endTime || ($startTime === '00:00' && $endTime === '00:00')) {
                    continue;
                }

                $intervalStart = Carbon::createFromFormat('H:i', $startTime);
                $intervalEnd = Carbon::createFromFormat('H:i', $endTime);

                $intervalStartTimes[] = $intervalStart;
                $intervalEndTimes[] = $intervalEnd;

                // Collect for global intervals merge (just timestamps)
                $allIntervals[] = [
                    'start' => $intervalStart,
                    'end' => $intervalEnd,
                ];
            }

            // Generate slots for this staff's intervals
            foreach ($intervals as $interval) {
                $startTime = $interval['start'] ?? null;
                $endTime = $interval['end'] ?? null;
                if (!$startTime || !$endTime || ($startTime === '00:00' && $endTime === '00:00')) {
                    continue;
                }
                $intervalStart = Carbon::createFromFormat('H:i', $startTime);
                $intervalEnd = Carbon::createFromFormat('H:i', $endTime);

                $totalMinutes = $intervalStart->diffInMinutes($intervalEnd);
                $fullSlotMinutes = floor($totalMinutes / $serviceDuration) * $serviceDuration;
                $adjustedEnd = $intervalStart->copy()->addMinutes($fullSlotMinutes);

                if ($fullSlotMinutes >= $serviceDuration) {
                    $slotStartTime = $intervalStart->copy();

                    while ($slotStartTime->lessThan($adjustedEnd)) {
                        $slotEndTime = $slotStartTime->copy()->addMinutes($serviceDuration);

                        $slots[] = [
                            'start_time' => $slotStartTime->format('H:i'),
                            'end_time' => $slotEndTime->format('H:i'),
                        ];

                        $allStaffSlots->push([
                            'start_time' => $slotStartTime->format('H:i'),
                            'end_time' => $slotEndTime->format('H:i'),
                            'staff_id' => $staff->id,
                        ]);

                        $slotStartTime = $slotEndTime;
                    }
                }
            }

            if (count($intervalStartTimes) > 0 && count($intervalEndTimes) > 0) {
                $staff->day_start = min($intervalStartTimes)->format('H:i');
                $staff->day_end = max($intervalEndTimes)->format('H:i');
            } else {
                $staff->day_start = null;
                $staff->day_end = null;
            }

            $staff->slots = $slots;
            $staffAvailability->push($staff);
        }

        // Now merge all collected intervals (from all staff) into continuous intervals
        $mergedIntervals = $this->mergeIntervals($allIntervals);

        // Generate merged slots from mergedIntervals
        $mergedSlots = collect();
        foreach ($mergedIntervals as $interval) {
            $intervalStart = $interval['start'];
            $intervalEnd = $interval['end'];

            $totalMinutes = $intervalStart->diffInMinutes($intervalEnd);
            $fullSlotMinutes = floor($totalMinutes / $serviceDuration) * $serviceDuration;
            $adjustedEnd = $intervalStart->copy()->addMinutes($fullSlotMinutes);

            $slotStartTime = $intervalStart->copy();

            while ($slotStartTime->lessThan($adjustedEnd)) {
                $slotEndTime = $slotStartTime->copy()->addMinutes($serviceDuration);

                // Count how many staff available in this slot by intersecting their intervals
                $availableStaffCount = $allStaffSlots
                    ->filter(function ($slot) use ($slotStartTime, $slotEndTime) {
                        return $slot['start_time'] <= $slotStartTime->format('H:i') && $slot['end_time'] >= $slotEndTime->format('H:i');
                    })
                    ->pluck('staff_id')
                    ->unique()
                    ->count();

                $mergedSlots->push([
                    'start_time' => $slotStartTime->format('H:i'),
                    'end_time' => $slotEndTime->format('H:i'),
                    'available_staff' => $availableStaffCount,
                ]);

                $slotStartTime = $slotEndTime;
            }
        }

        return response()->json([
            'date' => $formattedDate,
            'price' => $servicePrice,
            'serviceCurrency' => $serviceCurrency,
            'slotleft' => $mergedSlots->count(),
            'duration' => $serviceDuration,
            'staffdata' => $staffAvailability->values()->toArray(),
            'merged_slots' => $mergedSlots->values(),
        ]);
    }

    /**
     * Merge overlapping intervals into continuous intervals.
     * Input: array of ['start' => Carbon, 'end' => Carbon]
     * Output: merged array of intervals
     */
    private function mergeIntervals(array $intervals)
    {
        if (count($intervals) === 0) return [];

        // Sort intervals by start time
        usort($intervals, function ($a, $b) {
            return $a['start']->lt($b['start']) ? -1 : 1;
        });

        $merged = [];
        $current = $intervals[0];

        for ($i = 1; $i < count($intervals); $i++) {
            $interval = $intervals[$i];

            // If current interval end >= next interval start, merge
            if ($current['end']->gte($interval['start'])) {
                // Extend end if needed
                if ($interval['end']->gt($current['end'])) {
                    $current['end'] = $interval['end'];
                }
            } else {
                $merged[] = $current;
                $current = $interval;
            }
        }

        $merged[] = $current;
        return $merged;
    }
}
