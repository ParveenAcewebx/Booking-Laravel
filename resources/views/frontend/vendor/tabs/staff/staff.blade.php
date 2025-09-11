@extends('frontend.layouts.app')

@section('content')

    <div class="container mx-auto px-4 py-8" x-data="{ showForm: false, editStaff: null, innerTab: 'info' }">
<div class="mb-8 text-center">
    <h1 class="text-3xl font-bold text-gray-800">Vendor Dashboard</h1>
    <p class="text-gray-600 mt-2">Manage services, staff, and bookings in one place</p>
</div>


    <div class="flex gap-6">
        <!-- Sidebar -->
        <x-vendor-sidebar />

        <div class="w-3/4 bg-white shadow rounded-2xl p-6">
            <!-- Header -->
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold text-gray-800">Staff Members</h2>
                <a href="{{route('vendor.staff.add')}}"class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600">+ Add New Staff</a>

            </div>
            <!-- Staff List -->
            <div x-show="!showForm" class="space-y-4">
                @forelse($staffdata as $staff)
                    <div class="flex items-center justify-between p-4 border rounded-lg hover:shadow mb-4">
                        <div class="flex items-center gap-3">
                            <img src="{{ $staff->avatar ? '/storage/'.$staff->avatar : asset('assets/images/no-image-available.png') }}" alt="" class="w-10 h-10 rounded-full">
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
                            <a href="{{route('vendor.staff.edit',$staff->id)}}"class="px-3 py-1 text-sm bg-blue-500 text-white rounded hover:bg-blue-600">Edit</a>
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
                 <div class="mt-6">
                    {{ $staffdata->links('pagination::tailwind') }}
                </div>
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

toastr.options = {
        closeButton: true,
        progressBar: true,
        positionClass: "toast-top-right",
        preventDuplicates: false,
        onclick: null,
        showDuration: "300",
        hideDuration: "500",
        timeOut: "3000",
    };
    @foreach (['success', 'error'] as $msg)
        @if(session($msg))
            toastr.{{ $msg }}("{{ session($msg) }}");
        @endif
    @endforeach
</script>
@endpush
