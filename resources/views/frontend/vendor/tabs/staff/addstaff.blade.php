@extends('frontend.layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8" 
     x-data="{ showForm: true, editStaff: @json($staffdata ?? null), innerTab: 'info' }">
<div class="mb-8 text-center">
    <h1 class="text-3xl font-bold text-gray-800">Vendor Dashboard</h1>
    <p class="text-gray-600 mt-2">Manage services, staff, and bookings in one place</p>
</div>

    <div class="flex gap-6">
        <x-vendor-sidebar />

        <div class="w-3/4 bg-white shadow rounded-2xl p-6">
            <div x-show="showForm" x-transition class="mb-6">
                <h2 class="text-2xl font-bold mb-6 text-gray-800" x-text="editStaff ? 'Edit Staff' : 'Add Staff'"></h2>

                <form 
                    :action="editStaff ? '{{ url('vendor/staff') }}/' + editStaff[0].id : '{{ route('vendor.staff.store') }}'" 
                    method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    <template x-if="editStaff">
                        <input type="hidden" name="_method" value="PUT">
                    </template>

                    <!-- Tabs -->
                    <div class="border-b mb-4">
                        <ul class="flex gap-6 text-sm font-medium text-gray-600">
                            <li>
                                <button type="button" 
                                    @click="innerTab = 'info'" 
                                    :class="innerTab === 'info' 
                                        ? 'border-b-2 border-indigo-600 text-indigo-600 font-semibold' 
                                        : 'hover:text-indigo-600'" 
                                    class="py-2 px-4">Staff Info</button>
                            </li>
                            <li>
                                <button type="button" 
                                    @click="innerTab = 'working'" 
                                    :class="innerTab === 'working' 
                                        ? 'border-b-2 border-indigo-600 text-indigo-600 font-semibold' 
                                        : 'hover:text-indigo-600'" 
                                    class="py-2 px-4">Working Days</button>
                            </li>
                            <li>
                                <button type="button" 
                                    @click="innerTab = 'dayoffs'" 
                                    :class="innerTab === 'dayoffs' 
                                        ? 'border-b-2 border-indigo-600 text-indigo-600 font-semibold' 
                                        : 'hover:text-indigo-600'" 
                                    class="py-2 px-4">Day Offs</button>
                            </li>
                        </ul>
                    </div>

                    <!-- Staff Info -->
                    <div x-show="innerTab === 'info'" class="space-y-4">
                        <div>
                            <label class="block font-medium mb-1">Name</label>
                            <input type="text" name="name" 
                                :value="editStaff ? editStaff[0].name : ''" 
                                class="w-full border p-2 rounded focus:ring focus:ring-indigo-200" required>
                        </div>

                        <div>
                            <label class="block font-medium mb-1">Email</label>
                            <input type="email" name="email" 
                                :value="editStaff ? editStaff[0].email : ''" 
                                class="w-full border p-2 rounded focus:ring focus:ring-indigo-200" required>
                        </div>

                        <div>
                            <label class="block font-medium mb-1">Phone</label>
                            <div class="flex gap-2">
                                <select name="code" class="border p-2 rounded focus:ring focus:ring-indigo-200">
                                    @foreach($phoneCountries as $country)
                                        <option value="{{ $country['code'] }}" 
                                            :selected="editStaff && editStaff[0].code == '{{ $country['code'] }}'">
                                            {{ $country['code'] }}
                                        </option>
                                    @endforeach
                                </select>
                                <input type="text" name="phone_number" 
                                    :value="editStaff ? editStaff[0].phone_number : ''" 
                                    class="flex-1 border p-2 rounded focus:ring focus:ring-indigo-200" required>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block font-medium mb-1">Password</label>
                                <input type="password" name="password" 
                                    :required="!editStaff" class="w-full border p-2 rounded">
                            </div>
                            <div>
                                <label class="block font-medium mb-1">Confirm Password</label>
                                <input type="password" name="password_confirmation" 
                                    :required="!editStaff" class="w-full border p-2 rounded">
                            </div>
                        </div>

                        <div>
                            <label class="block font-medium mb-1">Profile Image</label>
                            <input type="file" name="avatar" class="border p-2 rounded w-full">
                            <template x-if="editStaff && editStaff[0].avatar">
                                <img :src="`/storage/${editStaff[0].avatar}`" 
                                     class="w-20 h-20 rounded mt-2 border" alt="Profile">
                            </template>
                        </div>

                        <div>
                            <label class="block font-medium mb-1">Assigned Services</label>
                            <select name="assigned_services[]" multiple class="assigned-services border rounded w-full">
                                @foreach($vendorservname as $service)
                                    <option value="{{ $service['id'] }}" >
                                        {{ $service['name'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block font-medium mb-1">Status</label>
                            <select name="status" class="border p-2 rounded w-full">
                                <option value="1" 
                                    :selected="!editStaff || (editStaff && editStaff[0].status == 1)">Active</option>
                                <option value="0" 
                                    :selected="editStaff && editStaff[0].status == 0">Inactive</option>
                            </select>
                        </div>
                    </div>

                    <!-- Working Days & Day Offs tabs stay the same -->
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
                                        {{-- Start Time --}}
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

                                        {{-- End Time --}}
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
                 <div x-show="innerTab === 'dayoffs'" 
     x-data="{ dayOffs: editStaff?.days_off ?? [] }" 
     class="space-y-4">
    <div class="flex justify-between items-center">
        <h6 class="font-semibold text-gray-700">Day Offs</h6>
        <button type="button" 
                class="px-3 py-1 text-sm bg-indigo-600 text-white rounded hover:bg-indigo-700"
                @click="dayOffs.push({ offs: '', date: '' })">
            + Add
        </button>
    </div>

    <template x-for="(dayOff, index) in dayOffs" :key="index">
        <div class="bg-gray-50 border rounded p-4">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block font-medium text-gray-700">Day(s) Off</label>
                    <input type="text" 
                        :name="`day_offs[${index}][offs]`" 
                        x-model="dayOff.offs" 
                        class="w-full border rounded p-2" required>
                </div>
                <div>
                    <label class="block font-medium text-gray-700">Date Range</label>
                    <input type="text" 
                        :name="`day_offs[${index}][date]`" 
                        x-model="dayOff.date" 
                        class="w-full border rounded p-2 date-range-picker" required>
                </div>
            </div>
            
            <!-- Delete Button -->
            <button type="button" 
                    class="mt-4 px-3 py-1 text-sm bg-red-500 text-white rounded hover:bg-red-600"
                    @click="dayOffs.splice(index, 1)">
                Delete
            </button>
        </div>
    </template>
</div>


                    <!-- Actions -->
                    <div class="flex justify-end gap-3 pt-4">
                        <a href="{{route('vendor.staff.view')}}" 
                           class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300">Cancel</a>
                        <button type="submit" 
                                class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
                            <span x-text="editStaff ? 'Update Staff' : 'Add Staff'"></span>
                        </button>
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
    $('.assigned-services').select2({
        placeholder: "Select Services",
        allowClear: true,
        width: '100%'
    });
    $('.date-range-picker').each(function() {
        initializeDateRangePicker($(this));
    });

    // Initialize on dynamically added inputs when they are focused
    $(document).on('focus', '.date-range-picker', function() {
        if (!$(this).data('daterangepicker')) { // check if not already initialized
            initializeDateRangePicker($(this));
            $(this).data('daterangepicker').show();
        }
    });

    function initializeDateRangePicker($element) {
        $element.daterangepicker({
            autoUpdateInput: false,
            locale: {
                format: 'MMMM D, YYYY'
            }
        }).on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('MMMM D, YYYY') + ' - ' + picker.endDate.format('MMMM D, YYYY'));
        });
    }
});

</script>
@endpush
