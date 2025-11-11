@extends('frontend.layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8" x-data="{ tab: 'profile' }">

    <!-- Page Header -->
    <div class="mb-8 text-center">
        <h1 class="text-3xl font-bold text-gray-800">Vendor Dashboard</h1>
        <p class="text-gray-600 mt-2">Manage services, staff, and bookings in one place</p>
    </div>


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
@push('scripts')
<script>


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
