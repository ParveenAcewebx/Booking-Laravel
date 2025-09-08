@extends('frontend.layouts.app')

@section('content')
    {{-- Page Header --}}
    <div class="mt-8 text-center">
        <h1 class="text-3xl font-bold text-gray-800">Vendor Dashboard</h1>
        <p class="text-gray-600 mt-2">Manage services, staff, and bookings in one place</p>
    </div>

    {{-- Main Layout --}}
    <div class="container mx-auto px-4 py-8" x-data="{ tab: 'booking' }">
        <div class="flex gap-6">

            <!-- Sidebar -->
            <x-vendor-sidebar />

            <!-- Main Content -->
            <div class="w-3/4 bg-white shadow rounded-2xl p-6">

                {{-- Booking Template Modal --}}
                @include('frontend.vendor.booking-template')

                @if(Request::routeIs('vendor.bookings.view'))

                    {{-- Header + Button --}}
                    <div class="flex justify-between items-center mb-4 booking-header">
                        <h2 class="text-xl font-semibold text-gray-800">Bookings</h2>
                        <a href="#" 
                           class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 open-popup">
                            + Create New Template
                        </a>
                    </div>

                    {{-- Bookings List --}}
                    <div class="space-y-4 booking-section">
                        @if($bookingdata->count() > 0)
                            @foreach($bookingdata as $booking)
                                <div class="p-4 border rounded-lg hover:shadow">
                                    <div class="flex justify-between items-center">
                                        <div>
                                            <h3 class="font-medium text-gray-800">
                                                {{ $booking->first_name ?? json_decode($booking->booking_data)->first_name ?? 'Unknown' }}
                                                {{ $booking->last_name ?? json_decode($booking->booking_data)->last_name ?? '' }}
                                                - {{ $booking->service->name ?? '' }}
                                            </h3>
                                            <p class="text-sm text-gray-500">
                                                Date: {{ \Carbon\Carbon::parse($booking->booking_datetime)->format('Y-m-d') }} |
                                                Time: {{ \Carbon\Carbon::parse($booking->booking_datetime)->format('h:i A') }}
                                            </p>
                                            <p class="text-sm text-gray-500">
                                                Email: {{ $booking->email ?? json_decode($booking->booking_data)->email ?? 'N/A' }} |
                                                Phone: {{ $booking->phone_number ?? json_decode($booking->booking_data)->phone ?? 'N/A' }}
                                            </p>
                                            <span class="inline-block mt-2 px-2 py-1 text-xs rounded 
                                                {{ $booking->status === 'pending' 
                                                    ? 'bg-yellow-100 text-yellow-700' 
                                                    : 'bg-green-100 text-green-700' }}">
                                                {{ ucfirst($booking->status) }}
                                            </span>
                                        </div>

                                        {{-- Actions --}}
                                        <div class="flex gap-2">
                                            <form action="{{ route('vendor.booking.destroy', $booking->id) }}" 
                                                  method="POST" 
                                                  onsubmit="return confirm('Are you sure?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="px-3 py-1 text-sm bg-red-500 text-white rounded hover:bg-red-600">
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            {{-- No Bookings --}}
                            <p class="text-gray-500 no-bookings">No bookings found.</p>
                        @endif
                    </div>

                @endif
            </div>
        </div>
    </div>
@endsection
