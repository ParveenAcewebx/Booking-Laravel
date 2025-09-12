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
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

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
        // Clear any previous session data for this slug
        session()->forget('user_data_' . $slug);

        // Get the booking template
        $template = BookingTemplate::findOrFail($slug);

        // Collect dynamic form data
        $bookingData = json_decode($request->input('booking_data'), true) ?? [];
        $inputData   = $request->input('dynamic', []);
        $files       = $request->file('dynamic', []);

        // Merge text inputs
        foreach ($inputData as $key => $val) {
            $bookingData[$key] = $val;
        }

        // Handle file uploads
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

        // Create user if first_name & last_name exist
        $lastInsertedId = 0;
        if (!empty($bookingData['first_name']) && !empty($bookingData['last_name'])) {
            // Check if email exists
            $existingUser = null;
            if (!empty($bookingData['email'])) {
                $existingUser = User::where('email', $bookingData['email'])->first();
            }

            if ($existingUser) {
                // If user already exists, use their ID
                $lastInsertedId = $existingUser->id;
            } else {
                // Otherwise, create a new user
                $randomPassword = Str::random(4) . rand(0, 9) . Str::random(2) . '!@#$%^&*()_+'[rand(0, 11)] . Str::random(2);

                $user = User::create([
                    'name'         => $bookingData['first_name'] . ' ' . $bookingData['last_name'],
                    'email'        => $bookingData['email'] ?? null,
                    'phone_number' => $bookingData['phone'] ?? null,
                    'password'     => Hash::make($randomPassword),
                    'avatar'       => '',
                    'status'       => $request->has('status')
                        ? config('constants.status.active')
                        : config('constants.status.inactive'),
                ]);

                $user->assignRole('customer');
                $lastInsertedId = $user->id;

                $macros = [
                    '{NAME}' => $user->name,
                    '{EMAIL}' => $user->email,
                    '{PASSWORD}' => $randomPassword,
                    '{SITE_TITLE}' => get_setting('site_title'),
                ];

                sendVendorTemplateEmail('vendor_login_email_notification', $user->email, $macros);
                sendAdminTemplateEmail('admin_new_user_notification', get_setting('owner_email'), $macros);
            }
        }

        // Create booking
        $booking = Booking::create([
            'booking_template_id' => $template->id,
            'customer_id'         => auth()->id() ?? $lastInsertedId,
            'booking_datetime'    => $request->input('booking_datetime', now()),
            'selected_staff'      => $request->input('selected_staff', 'Staff User'),
            'first_name'          => $bookingData['first_name'] ?? null,
            'last_name'           => $bookingData['last_name'] ?? null,
            'phone_number'        => $bookingData['phone'] ?? null,
            'email'               => $bookingData['email'] ?? null,
            'booking_data'        => json_encode($bookingData),
            'bookslots'           => $request->input('bookslots'),
            'service_id'          => $bookingData['service'] ?? null,
            'vendor_id'           => $bookingData['vendor'] ?? null,
        ]);
        $bookingId = $booking->id;
        $this->sendmailtocustom($template->id, $bookingId, $bookingData);
        return redirect()
            ->route('form.show', $template->slug)
            ->with([
                'success' => 'Form submitted successfully!',
                'alert-class' => 'frontend-form mb-4 rounded bg-green-100 px-4 py-3 text-green-800' // you can change this to whatever class you need
            ]);
    }


    function getservicesstaff(Request $request)
    {
        $vendor_data = [];
        if ($request) {
            $serviceId = $request->query('service_id');
            $vendorIds = VendorServiceAssociation::where('service_id', $serviceId)->with('vendor')->get();

            foreach ($vendorIds as $vendor) {
                if ($vendor->vendor && ($vendor->vendor->status === 1 || $vendor->vendor->status === null)) {
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
            $vendoraiationsstaff = VendorStaffAssociation::where('vendor_id', $vendor_id)
                ->with('staff')
                ->whereHas('user', function ($query) {
                    $query->where('status', 1);
                })
                ->get();
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
        // dd('sdfsfdsf');
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

        $vendorAssociations = VendorStaffAssociation::with(['staff', 'user'])
            ->where('vendor_id', $request->vendorid)
            ->whereHas('user', function ($q) {
                $q->where('status', 1); // active column in users table
            })
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


    public function storeSession(Request $request)
    {
        $formId = $request->input('formId');
        $data = $request->input('data');
        session()->put('user_data_' . $formId, $data);
        return response()->json(['message' => 'Data saved to session successfully']);
    }


    public function getSession(Request $request)
    {
        $formId = $request->input('formId');
        $data = session()->get('user_data_' . $formId, 'default value');
        return response()->json([
            'status' => 'success',
            'data' => [
                'value' => $data
            ]
        ]);
    }
    public function sendmailtocustom($formid, $bookingId, $bookingData)
    {
        $booking = Booking::with('template')->findOrFail($bookingId);
        $dynamicValues = json_decode($booking->booking_data, true) ?? [];

        // Get related service & vendor
        $service = isset($dynamicValues['service']) ? Service::find($dynamicValues['service']) : null;
        $vendor = isset($dynamicValues['vendor']) ? Vendor::find($dynamicValues['vendor']) : null;
        if ($service && $service->duration !== null) {
            $hours = floor($service->duration / 60);
            $minutes = $service->duration % 60;
            $durationFormatted = $hours > 0
                ? ($hours . ' hr' . ($hours > 1 ? 's' : '') . ($minutes > 0 ? ' ' . $minutes . ' min' : ''))
                : ($minutes . ' min');
        } else {
            $durationFormatted = null;
        }
        // Build service/vendor info
        $serviceverndor = [
            'Service Name'      => $service?->name,
            'Service Price'     => $service?->price,
            'Service Currency'  => $service?->currency,
            'Service Duration' => $durationFormatted,
            'Vendor Name'       => $vendor?->name,
        ];

        // Get booking slots
        $slotedetail = json_decode($booking->bookslots, true) ?? [];

        // Get form structure
        $formStructureJson = $booking->template->data ?? '[]';
        $formStructureArray = array_filter(
            json_decode($formStructureJson, true),
            fn($item) => $item['type'] !== 'shortcodeblock'
        );

        // Build a map of field names to labels and options (for checkboxes, radios, selects)
        $formFieldLabelMap = [];
        $formFieldOptionsMap = []; // name => [value => label]

        foreach ($formStructureArray as $field) {
            if (!empty($field['name'])) {
                $formFieldLabelMap[$field['name']] = $field['label'] ?? $field['name'];

                // Build options map for select, checkbox-group, radio-group
                if (in_array($field['type'], ['checkbox-group', 'radio-group', 'select'])) {
                    $optionsMap = [];
                    if (!empty($field['values']) && is_array($field['values'])) {
                        foreach ($field['values'] as $option) {
                            // Some forms might use 'value' or 'label' keys, handle both
                            $optValue = $option['value'] ?? ($option['label'] ?? '');
                            $optLabel = $option['label'] ?? $optValue;
                            if ($optValue !== '') {
                                $optionsMap[$optValue] = $optLabel;
                            }
                        }
                    }
                    $formFieldOptionsMap[$field['name']] = $optionsMap;
                }
            }
        }

        // Process additional info
        $AdditionalInformation = [];
        $excludedKeys = ['first_name', 'last_name', 'email', 'phone', 'service', 'vendor'];

        $filteredDynamicValues = array_filter(
            $dynamicValues,
            fn($key) => !in_array($key, $excludedKeys),
            ARRAY_FILTER_USE_KEY
        );

        $filteredKeys = array_keys($filteredDynamicValues);

        $matchedFields = array_filter(
            $formStructureArray,
            fn($field) =>
            !empty($field['name']) && in_array($field['name'], $filteredKeys)
        );

        foreach ($matchedFields as $field) {
            $name = $field['name'];
            $label = $formFieldLabelMap[$name] ?? $name;
            $rawValue = $dynamicValues[$name] ?? '';

            $value = '';

            if ($field['type'] === 'checkbox-group') {
                // Value is an array of keys
                $values = (array) $rawValue;

                // Remove 'other' if present, add _other values if any
                if (in_array('other', $values)) {
                    $values = array_diff($values, ['other']);
                    $otherValues = (array) ($dynamicValues[$name . '_other'] ?? []);
                    $values = array_merge($values, $otherValues);
                }

                // Map values to labels using options map if available
                if (isset($formFieldOptionsMap[$name])) {
                    $values = array_map(fn($v) => $formFieldOptionsMap[$name][$v] ?? $v, $values);
                }

                $value = implode(', ', $values);
            } elseif ($field['type'] === 'radio-group' || $field['type'] === 'select') {
                // Single value
                $val = $rawValue;

                if ($val === 'other' && !empty($dynamicValues[$name . '_other'])) {
                    $val = $dynamicValues[$name . '_other'];
                } elseif (isset($formFieldOptionsMap[$name][$val])) {
                    $val = $formFieldOptionsMap[$name][$val];
                }
                $value = $val;
            } else {
                // Other field types use raw value directly
                $value = $rawValue;
            }

            // Skip empty values
            if (empty($value) && $value !== '0') {
                continue;
            }

            $AdditionalInformation[$label] = $value;
        }

        // Fix booking datetime format
        $formattedDateTime = $booking->booking_datetime
            ? date('Y-m-d H:i', strtotime($booking->booking_datetime))
            : '';

        // Staff name → ID (optional)
        $staffUser = User::where('name', $booking->selected_staff)->first();
        $staffName = $staffUser?->name ?? $booking->selected_staff;

        // Combine everything into one table data array
        $bookingDetails = [
            'Booking DateTime' => $formattedDateTime,
        ];

        // Merge service/vendor
        $bookingDetails = array_merge($bookingDetails, $serviceverndor);

        // Merge slot details
        if (!empty($slotedetail) && is_array($slotedetail)) {
            foreach ($slotedetail as $key => $value) {
                $bookingDetails["Slot Detail " . ($key + 1)] = is_array($value) ? implode(', ', $value) : $value;
            }
        }

        // Merge additional info
        $bookingDetails = array_merge($bookingDetails, $AdditionalInformation);

        // Build a set of already added labels to avoid duplicates
        $addedLabels = array_map('strtolower', array_keys($bookingDetails));

        // Add personal details with proper labels, avoiding duplicates and skipping auto-generated keys
        foreach ($bookingData as $key => $value) {
            // Skip keys like checkbox-group-xxxx, select-xxxx etc.
            if (preg_match('/^(checkbox-group|select|radio-group)-\d+/', $key)) {
                continue;
            }

            $label = $formFieldLabelMap[$key] ?? ucwords(str_replace('_', ' ', $key));

            // Skip if label already exists
            if (in_array(strtolower($label), $addedLabels)) {
                continue;
            }

            // Skip empty values
            if (empty($value) && $value !== '0') {
                continue;
            }

            $bookingDetails[$label] = is_array($value) ? implode(', ', $value) : $value;
            $addedLabels[] = strtolower($label);
        }

        // Determine name & email
        if (!empty($bookingData['first_name'])) {
            $name = $bookingData['first_name'] . ' ' . ($bookingData['last_name'] ?? '');
            $email = $bookingData['email'] ?? '';
        } elseif (Auth::check()) {
            $name = Auth::user()->name;
            $email = Auth::user()->email;
        } else {
            $name = '';
            $email = '';
        }

        // Send email if possible
        if (!empty($name) && !empty($email)) {
            $macros = [
                '{NAME}'         => $name,
                '{SITE_TITLE}'   => get_setting('site_title'),
                '{BOOKING_DATA}' => generateBookingDataTable($bookingDetails),
            ];

            newbooking('booking_confirmed_notification', $email, $macros);
        }
    }
}
