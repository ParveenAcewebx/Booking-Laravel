@extends('frontend.layouts.app')

@section('content')
   @foreach (['success' => 'green', 'error' => 'red'] as $msg => $color)
        @if(session($msg))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" x-transition
                class="fixed top-4 right-4 bg-{{ $color }}-500 text-white px-6 py-3 rounded-lg shadow-lg flex items-center gap-2 z-50">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    @if($msg === 'success')
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    @else
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    @endif
                </svg>
                <span>{{ session($msg) }}</span>
            </div>
        @endif
    @endforeach
<div class="mb-8 text-center">
    <h1 class="text-3xl font-bold text-gray-800">Vendor Dashboard</h1>
    <p class="text-gray-600 mt-2">Manage services, staff, and bookings in one place</p>
</div>

<div class="container mx-auto px-4 py-8" x-data="{ showForm: false, editStaff: null, innerTab: 'info' }">
    <div class="flex gap-6">
        <!-- Sidebar -->
        <x-vendor-sidebar />

        <div class="w-3/4 bg-white shadow rounded-2xl p-6">
            <!-- Header -->
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold text-gray-800">Staff Members</h2>
                <button 
                    @click="showForm = !showForm; editStaff = null; innerTab = 'info'; 
                            setTimeout(() => { $('.assigned-services').val(null).trigger('change'); }, 200)"
                    class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600">
                    <span x-text="showForm ? 'Cancel' : '+ Add New Staff'"></span>
                </button>
            </div>

            <!-- Add/Edit Staff Form -->
            <div x-show="showForm" x-transition class="mb-6">
                <form 
                    :action="editStaff ? '{{ url('staff') }}/' + editStaff.id : '{{ route('vendor.staff.store') }}'" 
                    method="POST" enctype="multipart/form-data" 
                    class="space-y-4 p-4 border rounded-lg bg-gray-50">
                    
                    @csrf
                    <template x-if="editStaff">
                        <input type="hidden" name="_method" value="PUT">
                    </template>

                    <!-- Inner Tabs -->
                    <div class="border-b mb-4">
                        <ul class="flex gap-6 text-sm font-medium text-gray-600">
                            <li>
                                <button type="button" @click="innerTab = 'info'" 
                                        :class="innerTab === 'info' ? 'border-b-2 border-indigo-600 text-indigo-600' : 'hover:text-indigo-600'" 
                                        class="py-2 px-4">Staff Info</button>
                            </li>
                            <li>
                                <button type="button" @click="innerTab = 'working'" 
                                        :class="innerTab === 'working' ? 'border-b-2 border-indigo-600 text-indigo-600' : 'hover:text-indigo-600'" 
                                        class="py-2 px-4">Working Days</button>
                            </li>
                            <li>
                                <button type="button" @click="innerTab = 'dayoffs'" 
                                        :class="innerTab === 'dayoffs' ? 'border-b-2 border-indigo-600 text-indigo-600' : 'hover:text-indigo-600'" 
                                        class="py-2 px-4">Day Offs</button>
                            </li>
                        </ul>
                    </div>

                    <!-- Staff Info Tab -->
                    <div x-show="innerTab === 'info'" class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Name</label>
                            <input type="text" name="name" x-model="editStaff?.name"
                                class="w-full mt-1 p-3 border rounded-md focus:ring-2 focus:ring-indigo-500" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Email</label>
                            <input type="email" name="email" x-model="editStaff?.email"
                                class="w-full mt-1 p-3 border rounded-md focus:ring-2 focus:ring-indigo-500" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Phone Number</label>
                            <div class="flex gap-2">
                                <select class="form-control" name="code" style="max-width: 100px;">
                                    @foreach($phoneCountries as $country)
                                        <option value="{{ $country['code'] }}" {{ old('code', '+91') == $country['code'] ? 'selected' : '' }}>
                                            {{ $country['code'] }}
                                        </option>
                                    @endforeach
                                </select>
                                <input type="text" name="phone_number" placeholder="1234567890" 
                                    x-model="editStaff?.phone_number"
                                    class="w-3/4 p-3 border rounded-md focus:ring-2 focus:ring-indigo-500">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Password</label>
                            <input type="password" name="password" 
                                class="w-full mt-1 p-3 border rounded-md focus:ring-2 focus:ring-indigo-500"
                                x-bind:required="!editStaff">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Confirm Password</label>
                            <input type="password" name="password_confirmation" 
                                class="w-full mt-1 p-3 border rounded-md focus:ring-2 focus:ring-indigo-500"
                                x-bind:required="!editStaff">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Profile Image</label>
                            <input type="file" name="avatar" class="w-full mt-1 p-3 border rounded-md focus:ring-2 focus:ring-indigo-500">
                            <template x-if="editStaff?.avatar">
                                <img :src="`/storage/${editStaff.avatar}`" alt="Preview" class="mt-2 w-16 h-16 rounded-full">
                            </template>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Assigned Services</label>
                            <select name="assigned_services[]" multiple
                                class="assigned-services w-full mt-1 p-3 border rounded-md focus:ring-2 focus:ring-indigo-500">
                                @foreach($servicedata as $service)
                                    <option value="{{ $service->id }}">{{ $service->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Status</label>
                            <select name="status" x-model="editStaff?.status ?? '1'" 
                                class="w-full mt-1 p-3 border rounded-md focus:ring-2 focus:ring-indigo-500">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                    </div>

                    <!-- Working Days Tab -->
                    <div x-show="innerTab === 'working'" class="space-y-2">
                        @foreach($weekDays as $day)
                            @php $daySlug = Str::slug($day); @endphp
                            <div class="border rounded">
                                <button type="button" class="flex justify-between w-full px-4 py-2 font-medium text-gray-700 bg-gray-100"
                                        @click="$refs['{{ $daySlug }}'].classList.toggle('hidden')">
                                    {{ $day }} <span>▼</span>
                                </button>
                                <div x-ref="{{ $daySlug }}" class="hidden px-4 py-3">
                                    <div class="flex gap-4">
                                        <select name="working_days[{{ $daySlug }}][start]" x-model="editStaff?.work_days?.{{ $daySlug }}?.start" class="w-full border rounded p-2">
                                            @for($h=0;$h<24;$h++) @foreach(['00','30'] as $m)
                                                @php $time=str_pad($h,2,'0',STR_PAD_LEFT).':'.$m; @endphp
                                                <option value="{{ $time }}">{{ $time }}</option>
                                            @endforeach @endfor
                                        </select>
                                        <select name="working_days[{{ $daySlug }}][end]" x-model="editStaff?.work_days?.{{ $daySlug }}?.end" class="w-full border rounded p-2">
                                            @for($h=0;$h<24;$h++) @foreach(['00','30'] as $m)
                                                @php $time=str_pad($h,2,'0',STR_PAD_LEFT).':'.$m; @endphp
                                                <option value="{{ $time }}">{{ $time }}</option>
                                            @endforeach @endfor
                                        </select>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Day Offs Tab -->
                    <div x-show="innerTab === 'dayoffs'" x-data="{ dayOffs: editStaff?.days_off ?? [] }" class="space-y-4">
                        <div class="flex justify-between items-center">
                            <h6 class="font-semibold text-gray-700">Day Offs</h6>
                            <button type="button" class="px-3 py-1 text-sm bg-blue-500 text-white rounded hover:bg-blue-600"
                                    @click="dayOffs.push({ offs: '', date: '' })">Add Day Off</button>
                        </div>
                        <template x-for="(dayOff, index) in dayOffs" :key="index">
                            <div class="bg-white shadow rounded p-4">
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block font-medium text-gray-700">Day(s) Off</label>
                                        <input type="text" :name="`day_offs[${index}][offs]`" x-model="dayOff.offs" class="w-full border rounded p-2" required>
                                    </div>
                                    <div>
                                        <label class="block font-medium text-gray-700">Date Range</label>
                                        <input type="text" :name="`day_offs[${index}][date]`" x-model="dayOff.date" class="w-full border rounded p-2 date-range-picker" required>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>

                    <div class="flex justify-end gap-3 mt-6">
                        <button type="button" @click="showForm=false; editStaff=null; innerTab='info'" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Cancel</button>
                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700" x-text="editStaff ? 'Update Staff' : 'Save Staff'"></button>
                    </div>
                </form>
            </div>

            <!-- Staff List -->
            <div x-show="!showForm" class="space-y-4">
                @forelse($staffdata as $staff)
                    <div class="flex items-center justify-between p-4 border rounded-lg hover:shadow mb-4">
                        <div class="flex items-center gap-3">
                            <img src="/storage/{{ $staff->avatar ?: 'https://via.placeholder.com/40' }}" alt="" class="w-10 h-10 rounded-full">
                            <div>
                                <h3 class="font-medium text-gray-800">{{ $staff->name }}</h3>
                                <p class="text-sm text-gray-500"><strong>Status:</strong> {{ $staff->status ? 'Active' : 'Inactive' }}</p>
                                <p class="text-sm text-gray-500"><strong>Phone:</strong> {{ $staff->phone_code }} {{ $staff->phone_number }}</p>
                                <p class="text-sm text-gray-500"><strong>Email:</strong> {{ $staff->email }}</p>
                                <p class="text-sm text-gray-500"><strong>Services:</strong> 
                                    @if(!empty($staffServices[$staff->id]))
                                        {{ implode(', ', array_column($staffServices[$staff->id], 'name')) }}
                                    @else No services assigned @endif
                                </p>
                            </div>
                        </div>
                        <div class="flex gap-2">
                     <button type="button"
                        @click='
                            showForm = true; 
                            editStaff = Object.assign({}, 
                                @json($staff), 
                                {
                                    services: @json($staffServices[$staff->id] ?? []),
                                    work_days: @json(collect($StaffworkdaysDayoff)->firstWhere("user_id", $staff->id)["work_days"] ?? []),
                                    days_off: @json(collect($StaffworkdaysDayoff)->firstWhere("user_id", $staff->id)["days_off"] ?? [])
                                }
                            ); 
                            setTimeout(() => { 
                                $(".assigned-services").val((editStaff.services ?? []).map(s => s.id)).trigger("change"); 
                            }, 200)
                        '
                        class="px-3 py-1 text-sm bg-blue-500 text-white rounded hover:bg-blue-600">Edit</button>
                            <form action="{{ route('vendor.staff.destroy', $staff->id) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-3 py-1 text-sm bg-red-500 text-white rounded hover:bg-red-600">Delete</button>
                            </form>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-gray-500">No Staff Found</p>
                @endforelse
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
});

</script>
@endpush
