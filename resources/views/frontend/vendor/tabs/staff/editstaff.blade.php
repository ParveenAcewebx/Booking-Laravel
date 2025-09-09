@extends('frontend.layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8" 
     x-data="{ showForm: true, innerTab: 'info' }">
    <div class="mb-8 text-center">
        <h1 class="text-3xl font-bold text-gray-800">Vendor Dashboard</h1>
        <p class="text-gray-600 mt-2">Manage services, staff, and bookings in one place</p>
    </div>

    <div class="flex gap-6">
        <x-vendor-sidebar />

        <div class="w-3/4 bg-white shadow rounded-2xl p-6">
            <div x-show="showForm" x-transition class="mb-6">
                <h2 class="text-2xl font-bold mb-6 text-gray-800">Edit Staff</h2>

                <form action="{{ route('vendor.staff.update',$staffdata[0]->id) }}" 
                      method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Tabs -->
                    <div class="border-b mb-4">
                        <ul class="flex gap-6 text-sm font-medium text-gray-600">
                            <li>
                                <button type="button" @click="innerTab = 'info'" 
                                    :class="innerTab === 'info' ? 'border-b-2 border-indigo-600 text-indigo-600 font-semibold' : 'hover:text-indigo-600'" 
                                    class="py-2 px-4">Staff Info</button>
                            </li>
                            <li>
                                <button type="button" @click="innerTab = 'working'" 
                                    :class="innerTab === 'working' ? 'border-b-2 border-indigo-600 text-indigo-600 font-semibold' : 'hover:text-indigo-600'" 
                                    class="py-2 px-4">Working Days</button>
                            </li>
                            <li>
                                <button type="button" @click="innerTab = 'dayoffs'" 
                                    :class="innerTab === 'dayoffs' ? 'border-b-2 border-indigo-600 text-indigo-600 font-semibold' : 'hover:text-indigo-600'" 
                                    class="py-2 px-4">Day Offs</button>
                            </li>
                        </ul>
                    </div>

                    <!-- Staff Info -->
                    <div x-show="innerTab === 'info'" class="space-y-4">
                        <div>
                            <label class="block font-medium mb-1">Name</label>
                            <input type="text" name="name" value="{{ old('name', $staffdata[0]->name) }}" 
                                   class="w-full border p-2 rounded focus:ring focus:ring-indigo-200" required>
                        </div>
                        <div>
                            <label class="block font-medium mb-1">Email</label>
                            <input type="email" name="email" value="{{ old('email', $staffdata[0]->email) }}" 
                                   class="w-full border p-2 rounded focus:ring focus:ring-indigo-200" required>
                        </div>
                        <div>
                            <label class="block font-medium mb-1">Phone</label>
                            <div class="flex gap-2">
                                <select name="code" class="border p-2 rounded focus:ring focus:ring-indigo-200">
                                    @foreach($phoneCountries as $country)
                                        <option value="{{ $country['code'] }}" 
                                            {{ old('code', $staffdata[0]->code) == $country['code'] ? 'selected' : '' }}>
                                            {{ $country['code'] }}
                                        </option>
                                    @endforeach
                                </select>
                                <input type="text" name="phone_number" value="{{ old('phone_number', $staffdata[0]->phone_number) }}" 
                                       class="flex-1 border p-2 rounded focus:ring focus:ring-indigo-200" required>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block font-medium mb-1">Password</label>
                                <input type="password" name="password" class="w-full border p-2 rounded">
                            </div>
                            <div>
                                <label class="block font-medium mb-1">Confirm Password</label>
                                <input type="password" name="password_confirmation" class="w-full border p-2 rounded">
                            </div>
                        </div>
                        <div>
                            <label class="block font-medium mb-1">Profile Image</label>
                            <input type="file" name="avatar" class="border p-2 rounded w-full">
                            @if($staffdata[0]->avatar)
                                <img src="{{ asset('storage/' . $staffdata[0]->avatar) }}" 
                                     alt="Profile" class="w-20 h-20 rounded mt-2 border">
                            @endif
                        </div>
                        <div>
                            <label class="block font-medium mb-1">Assigned Services</label>
                            @php
                                $assignedServiceIds = $servicedata->pluck('id')->toArray();
                            @endphp
                            <select name="assigned_services[]" multiple class="assigned-services border rounded w-full">
                                @foreach($vendorservname as $service)
                                    <option value="{{ $service['id'] }}" 
                                        {{ in_array($service['id'], $assignedServiceIds) ? 'selected' : '' }}>
                                        {{ $service['name'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block font-medium mb-1">Status</label>
                            <select name="status" class="border p-2 rounded w-full">
                                <option value="1" {{ old('status', $staffdata[0]->status) == 1 ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ old('status', $staffdata[0]->status) == 0 ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                    </div>

                    <!-- Working Days -->
                    <div x-show="innerTab === 'working'" class="space-y-3">
                        @php
                            $workDays = $StaffworkdaysDayoff[0]['work_days'] ?? [];
                        @endphp
                        @foreach($weekDays as $day)
                            @php $daySlug = Str::slug($day); @endphp
                            <div class="border rounded">
                                <button type="button" 
                                        class="flex justify-between w-full px-4 py-2 font-medium text-gray-700 bg-gray-100"
                                        @click="$refs['{{ $daySlug }}'].classList.toggle('hidden')">
                                    {{ $day }} 
                                    <span class="transform transition-transform">▼</span>
                                </button>
                                <div x-ref="{{ $daySlug }}" class="hidden px-4 py-3">
                                    <div class="flex gap-4">
                                        <select name="working_days[{{ $daySlug }}][start]" class="w-full border rounded p-2">
                                            @for($h=0;$h<24;$h++)
                                                @foreach(['00','30'] as $m)
                                                    @php
                                                        $time = str_pad($h,2,'0',STR_PAD_LEFT).':'.$m;
                                                        $selectedStart = isset($workDays[$daySlug]['start']) && $workDays[$daySlug]['start'] == $time ? 'selected' : '';
                                                    @endphp
                                                    <option value="{{ $time }}" {{ $selectedStart }}>{{ $time }}</option>
                                                @endforeach
                                            @endfor
                                        </select>

                                        <select name="working_days[{{ $daySlug }}][end]" class="w-full border rounded p-2">
                                            @for($h=0;$h<24;$h++)
                                                @foreach(['00','30'] as $m)
                                                    @php
                                                        $time = str_pad($h,2,'0',STR_PAD_LEFT).':'.$m;
                                                        $selectedEnd = isset($workDays[$daySlug]['end']) && $workDays[$daySlug]['end'] == $time ? 'selected' : '';
                                                    @endphp
                                                    <option value="{{ $time }}" {{ $selectedEnd }}>{{ $time }}</option>
                                                @endforeach
                                            @endfor
                                        </select>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Day Offs -->
                    <div x-show="innerTab === 'dayoffs'" class="space-y-4">
                        <div class="flex justify-between items-center">
                            <h6 class="font-semibold text-gray-700">Day Offs</h6>
                            <button type="button" class="px-3 py-1 text-sm bg-indigo-600 text-white rounded hover:bg-indigo-700" onclick="addDayOff()">
                                + Add
                            </button>
                        </div>
                        @php
                            // Initialize the daysoff array and extract dayOffsForForm
                            $daysoff = [];
                            $dayOffsForForm = $StaffworkdaysDayoff[0]['days_off'] ?? [];

                            // Flatten the dayOffsForForm array into a single array
                            foreach($dayOffsForForm as $index => $dayOff) {
                                foreach($dayOff as $data) {
                                    $daysoff[] = $data;
                                }
                            }

                            // Merge the day offs by label (ensure no duplicate labels in mergedDaysoff)
                            $mergedDaysoff = [];
                            foreach ($daysoff as $data) {
                                if (isset($mergedDaysoff[$data['label']])) {
                                    $mergedDaysoff[$data['label']][] = $data;
                                } else {
                                    $mergedDaysoff[$data['label']] = [$data];
                                }
                            }

                            // Create an array to hold the start and end dates for each label
                            $dayOffRanges = [];
                            foreach ($mergedDaysoff as $label => $dayOffs) {
                                // Sort the day-offs by date (in case they aren't sorted already)
                                usort($dayOffs, function($a, $b) {
                                    return strtotime($a['date']) - strtotime($b['date']);
                                });

                                // Get the first and last date for each label
                                $startDate = reset($dayOffs)['date'];
                                $endDate = end($dayOffs)['date'];

                                // Store the date range for each label
                                $dayOffRanges[$label] = [
                                    'start_date' => $startDate,
                                    'end_date' => $endDate,
                                ];
                            }

                            // Prepare a final list of unique day off labels with their date ranges
                            $uniqueDayOffs = [];
                            foreach ($mergedDaysoff as $label => $dayOffs) {
                                // Pick the first dayOff's data for each label (we already know all share the same date range)
                                $uniqueDayOffs[] = [
                                    'label' => $label,
                                    'start_date' => $dayOffRanges[$label]['start_date'],
                                    'end_date' => $dayOffRanges[$label]['end_date'],
                                ];
                            }
                        @endphp

                        <div id="dayOffContainer">
                            @foreach($uniqueDayOffs as $index => $dayOff)
                                @php
                                    // Get the corresponding date range
                                    $dateRange = $dayOff['start_date'] && $dayOff['end_date'] 
                                        ? $dayOff['start_date'] . ' - ' . $dayOff['end_date'] 
                                        : $dayOff['start_date'];
                                @endphp

                                <div class="bg-gray-50 border rounded p-4 mb-2 day-off-item"id="{{$index}}">
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="block font-medium text-gray-700">Day(s) Off</label>
                                            <input type="text" name="day_offs[{{ $index }}][offs]" 
                                                value="{{ old('day_offs.'.$index.'.offs', $dayOff['label']) }}" 
                                                class="w-full border rounded p-2" required>
                                        </div>
                                        <div>
                                            <label class="block font-medium text-gray-700">Date Range</label>
                                            <input type="text" name="day_offs[{{ $index }}][date]" 
                                                value="{{ old('day_offs.'.$index.'.date', $dateRange) }}" 
                                                class="w-full border rounded p-2 date-range-picker" required>
                                        </div>
                                    </div>
                                    <button type="button" class="delete-btn bg-red-500 text-white px-4 py-2 mt-4 rounded" 
                                    data-index="{{ $index }}" onclick="deleteRow(this)">Delete</button>
                                </div>
                            @endforeach
                        </div>
                        </div>


                    <!-- Actions -->
                    <div class="flex justify-end gap-3 pt-4">
                        <a href="{{ route('vendor.staff.view') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300">Cancel</a>
                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">Update Staff</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Assigned Services Select2
    $('.assigned-services').select2({
        placeholder: "Select Services",
        allowClear: true,
        width: '100%'
    });

    // Initialize date range picker
    $('.date-range-picker').each(function() {
        initializeDateRangePicker($(this));
    });

    $(document).on('focus', '.date-range-picker', function() {
        if (!$(this).data('daterangepicker')) {
            initializeDateRangePicker($(this));
            $(this).data('daterangepicker').show();
        }
    });

    function initializeDateRangePicker($element) {
        $element.daterangepicker({
            autoUpdateInput: false,
            locale: { format: 'MMMM D, YYYY' }
        }).on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('MMMM D, YYYY') + ' - ' + picker.endDate.format('MMMM D, YYYY'));
        });
    }
});

// Dynamically add Day Off
let dayOffIndex = {{ count($dayOffsForForm) }};
function addDayOff() {
    let container = document.getElementById('dayOffContainer');
    let html = `
    <div class="bg-gray-50 border rounded p-4 mb-2 day-off-item">
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block font-medium text-gray-700">Day(s) Off</label>
                <input type="text" name="day_offs[${dayOffIndex}][offs]" class="w-full border rounded p-2" required>
            </div>
            <div>
                <label class="block font-medium text-gray-700">Date Range</label>
                <input type="text" name="day_offs[${dayOffIndex}][date]" class="w-full border rounded p-2 date-range-picker" required>
            </div>
        </div>
        <button type="button" class="delete-btn bg-red-500 text-white px-4 py-2 mt-4 rounded" 
        data-index="" onclick="deleteRow(this)">Delete</button>
    </div>`;
    container.insertAdjacentHTML('beforeend', html);

    // Initialize new date-range-picker
    $('.date-range-picker').last().daterangepicker({
        autoUpdateInput: false,
        locale: { format: 'MMMM D, YYYY' }
    }).on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('MMMM D, YYYY') + ' - ' + picker.endDate.format('MMMM D, YYYY'));
    });

    dayOffIndex++;
}
function deleteRow(button){
     var row = button.closest('.day-off-item');
       if (row) {
            row.remove();
        }
}
$(document).on('change', 'select[name^="working_days"][name$="[start]"]', function() {
    const $start = $(this);
    const selectedIndex = this.selectedIndex;
    const $end = $start.closest('div').find('select[name$="[end]"]');
    $end.find('option').prop('disabled', false);
    $end.find('option').each(function(index) {
        if (index <= selectedIndex) {
            $(this).prop('disabled', true);
        }
    });
});
</script>
@endpush
