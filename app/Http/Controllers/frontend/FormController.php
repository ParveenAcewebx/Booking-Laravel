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
            'bookslots'                 => $request->input('bookslots'),
            'service_id'                => $bookingData['service'],
            'vendor_id'                 => $bookingData['vendor'],
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
        $allStaffSlots = collect();
        $allIntervals = [];
        $allStaffIds = [];

        foreach ($vendorAssociations as $association) {
            $staff = $association->staff;
            if (!$staff) continue;

            $allStaffIds[] = $staff->user_id;

            // Decode days_off
            $daysOff = json_decode($staff->days_off ?? '[]', true);
            if (is_array($daysOff) && isset($daysOff[0]) && is_array($daysOff[0])) {
                $daysOff = $daysOff[0];
            }

            // Decode work_hours
            $workHours = json_decode($staff->work_hours ?? '{}', true);
            if (!isset($workHours[$weekday])) {
                $staff->slots = [];
                $staff->day_start = null;
                $staff->day_end = null;
                $staffAvailability->push($staff);
                continue;
            }

            // Skip if this date is a day off
            $dateIsOff = collect($daysOff)->contains(function ($dayOff) use ($formattedDate) {
                return isset($dayOff['date']) && $dayOff['date'] === $formattedDate;
            });
            if ($dateIsOff) {
                $staff->slots = [];
                $staff->day_start = null;
                $staff->day_end = null;
                $staffAvailability->push($staff);
                continue;
            }

            $intervals = isset($workHours[$weekday][0]['start'])
                ? $workHours[$weekday]
                : [$workHours[$weekday]];

            $slots = [];
            $startTimes = [];
            $endTimes = [];

            foreach ($intervals as $interval) {
                $startTime = $interval['start'] ?? null;
                $endTime = $interval['end'] ?? null;

                if (!$startTime || !$endTime || ($startTime === '00:00' && $endTime === '00:00')) {
                    continue;
                }

                $start = Carbon::createFromFormat('H:i', $startTime);
                $end = Carbon::createFromFormat('H:i', $endTime);

                $startTimes[] = $start;
                $endTimes[] = $end;
                $allIntervals[] = ['start' => $start, 'end' => $end];

                $totalMinutes = $start->diffInMinutes($end);
                $fullSlotMinutes = floor($totalMinutes / $serviceDuration) * $serviceDuration;
                $adjustedEnd = $start->copy()->addMinutes($fullSlotMinutes);

                if ($fullSlotMinutes >= $serviceDuration) {
                    $slotStartTime = $start->copy();
                    while ($slotStartTime->lessThan($adjustedEnd)) {
                        $slotEndTime = $slotStartTime->copy()->addMinutes($serviceDuration);

                        $slots[] = [
                            'start_time' => $slotStartTime->format('H:i'),
                            'end_time' => $slotEndTime->format('H:i'),
                        ];

                        $allStaffSlots->push([
                            'start_time' => $slotStartTime->format('H:i'),
                            'end_time'   => $slotEndTime->format('H:i'),
                            'staff_id'   => $staff->user_id,
                        ]);

                        $slotStartTime = $slotEndTime;
                    }
                }
            }

            $staff->day_start = count($startTimes) ? min($startTimes)->format('H:i') : null;
            $staff->day_end = count($endTimes) ? max($endTimes)->format('H:i') : null;
            $staff->slots = $slots;
            $staffAvailability->push($staff);
        }

        // Get IDs of staff who are off for this date
        $staffIdsOff = $vendorAssociations->filter(function ($association) use ($formattedDate) {
            $staff = $association->staff;
            if (!$staff) return false;

            $daysOff = json_decode($staff->days_off ?? '[]', true);
            if (is_array($daysOff) && isset($daysOff[0]) && is_array($daysOff[0])) {
                $daysOff = $daysOff[0];
            }

            return collect($daysOff)->contains(function ($dayOff) use ($formattedDate) {
                return isset($dayOff['date']) && $dayOff['date'] === $formattedDate;
            });
        })->pluck('staff.user_id')->filter()->values()->all();

        // Merge overlapping intervals
        $mergedIntervals = $this->mergeIntervals($allIntervals);

        $mergedSlots = collect();
        foreach ($mergedIntervals as $interval) {
            $slotStart = $interval['start']->copy();
            $slotEnd   = $interval['end']->copy();

            $totalMinutes = $slotStart->diffInMinutes($slotEnd);
            $fullSlotMinutes = floor($totalMinutes / $serviceDuration) * $serviceDuration;
            $adjustedEnd = $slotStart->copy()->addMinutes($fullSlotMinutes);

            while ($slotStart->lessThan($adjustedEnd)) {
                $currentEnd = $slotStart->copy()->addMinutes($serviceDuration);

                $availableStaffIds = $allStaffSlots
                    ->filter(function ($slot) use ($slotStart, $currentEnd) {
                        return $slot['start_time'] <= $slotStart->format('H:i') &&
                            $slot['end_time']   >= $currentEnd->format('H:i');
                    })
                    ->pluck('staff_id')
                    ->unique()
                    ->values()
                    ->all();

                // If no one has a day off, show ALL staff IDs
                if (empty($staffIdsOff)) {
                    $availableStaffIds = $allStaffIds;
                } else {
                    // Remove only those who are off
                    $availableStaffIds = array_values(array_diff($availableStaffIds, $staffIdsOff));
                }

                $mergedSlots->push([
                    'start_time'          => $slotStart->format('H:i'),
                    'end_time'            => $currentEnd->format('H:i'),
                    'available_staff'     => count($availableStaffIds),
                    'available_staff_ids' => $availableStaffIds,
                ]);

                $slotStart = $currentEnd;
            }
        }

        return response()->json([
            'date'            => $formattedDate,
            'price'           => $servicePrice,
            'serviceCurrency' => $serviceCurrency,
            'slotleft'        => $mergedSlots->count(),
            'duration'        => $serviceDuration,
            'staffdata'       => $staffAvailability->values()->toArray(),
            'merged_slots'    => $mergedSlots->values(),
            'staff_off_ids'   => implode(',', $staffIdsOff),
        ]);
    }

    private function mergeIntervals(array $intervals)
    {
        if (empty($intervals)) return [];
        usort($intervals, fn($a, $b) => $a['start']->lt($b['start']) ? -1 : 1);
        
        $merged = [];
        $current = $intervals[0];

        for ($i = 1; $i < count($intervals); $i++) {
            $next = $intervals[$i];
            if ($current['end']->gte($next['start'])) {
                $current['end'] = max($current['end'], $next['end']);
            } else {
                $merged[] = $current;
                $current = $next;
            }
        }
        $merged[] = $current;
        return $merged;
    }
}
