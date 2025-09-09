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

<div class="container mx-auto px-4 py-8 flex gap-6">

    <!-- Sidebar -->
    <x-vendor-sidebar />

    <!-- Main Content -->
    <div class="w-3/4 bg-white shadow rounded-2xl p-6" x-data="{ showForm: false, editService: null }">

        {{-- Services Tab --}}
        @if(Request::routeIs('vendor.services.view'))

        <!-- Header -->
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold text-gray-800">Services</h2>
            <a href="{{route('vendor.services.add')}}"
                class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600">+ Add New Service</a>
        </div>



        <!-- Service List -->
        <div x-show="!showForm" class="space-y-4">
            @if($servicedata && $servicedata->count() > 0)
            @foreach($servicedata as $services_data)
            @php
            $categoryObj = $categories->firstWhere('id', $services_data->category);
            @endphp
            <div class="space-y-4 mb-4 p-4 border rounded-lg hover:shadow">
                <h2 class="mb-2 text-xl text-gray-800">{{ $services_data->name }}</h2>
                <p><strong>Description:</strong> {!! $services_data->description !!}</p>
                <p><strong>Category:</strong> {{ $categoryObj ? $categoryObj->category_name : 'Not assigned' }}</p>
                <p><strong>Status:</strong> {{ $services_data->status == 1 ? 'Active' : 'Inactive' }}</p>
                <p><strong>Price:</strong> {{ $services_data->currency }}{{ $services_data->price }}</p>
                <p>
                    <strong>Duration:</strong>
                    @php $duration = $services_data->duration; @endphp
                    @if($duration < 60) {{ $duration }} minutes @elseif($duration % 60==0) {{ $duration / 60 }}
                        hour{{ $duration >= 120 ? 's' : '' }} @else {{ intdiv($duration, 60) }}
                        hour{{ intdiv($duration, 60) > 1 ? 's' : '' }} {{ $duration % 60 }} minutes @endif </p>
                        @if($services_data->thumbnail)
                        <p><strong>Featured Image:</strong></p>
                        <img src="/storage/{{ $services_data->thumbnail }}" class="w-20 h-20 rounded shadow mb-2">
                        @endif
                        @if($services_data->gallery)
                        <p><strong>Gallery:</strong></p>
                        <div class="flex gap-2 flex-wrap">
                            @foreach(json_decode($services_data->gallery, true) as $img)
                            <img src="/storage/{{ $img }}" class="w-16 h-16 rounded shadow">
                            @endforeach
                        </div>
                        @endif
                        <!-- Actions -->
                        <div class="flex gap-2 mt-3">
                            <a href="{{route('vendor.services.edit',$servicedata[0]->id)}}"
                                class="px-3 py-1 text-sm bg-blue-500 text-white rounded hover:bg-blue-600">Edit</a>
                            <form action="{{ route('vendor.services.destroy', $services_data->id) }}" method="POST"
                                onsubmit="return confirm('Are you sure?');">
                                @csrf @method('DELETE')
                                <button type="submit"
                                    class="px-3 py-1 text-sm bg-red-500 text-white rounded hover:bg-red-600">Delete</button>
                            </form>
                        </div>
            </div>
            @endforeach
            <div class="mt-6">
                {{ $servicedata->links('pagination::tailwind') }}
            </div>


            @else
            <p class="text-gray-500">No services found.</p>
            @endif
        </div>

        @endif {{-- End Services Tab --}}

    </div> {{-- End Main Content --}}
</div>
@endsection