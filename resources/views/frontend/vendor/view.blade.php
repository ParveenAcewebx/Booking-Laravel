@extends('frontend.layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8" x-data="{ tab: 'profile' }">

    <!-- Page Header -->
    <div class="mb-8 text-center">
        <h1 class="text-3xl font-bold text-gray-800">Vendor Dashboard</h1>
        <p class="text-gray-600 mt-2">Manage services, staff, and bookings in one place</p>
    </div>

    <!-- Flash Messages -->
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

    <div class="flex gap-6">

        <!-- Sidebar Tabs -->
        <x-vendor-sidebar />

        <!-- Tab Content -->
        <div class="w-3/4 bg-white shadow rounded-2xl p-6">
            {{-- PROFILE TAB --}}
             @if(Request::routeIs('vendor.dashboard.view'))
                  @include('frontend.vendor.tabs.profile.profile', ['user' => auth()->user()])
             @endif
        </div>
    </div>
</div>
@endsection
