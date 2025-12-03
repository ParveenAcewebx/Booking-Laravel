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

        <div class="w-3/4 bg-white shadow rounded-2xl p-6">
            <div class="space-y-4 booking-section">
                <section class="">
                    <div class="">
                        <!-- [ Page Header ] start -->
                        <div class="border-b border-gray-200 pb-4 mb-6">
                            <div class="flex flex-col gap-2">
                                <div class="flex justify-between gap-3 items-center">
                                    <h5 class="text-xl font-semibold text-gray-800">Booking #{{$bookingid}}</h5>
                                      <a href="{{route('vendor.bookings.view')}}" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Cancel</a>
                                </div>
                                
                            </div>
                        </div>

                        <!-- [ Form Section ] start -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Left Column -->
                            <div class="bg-white border rounded-lg shadow">
                                <div class="border-b px-6 py-4">
                                    <h5 class="text-lg font-medium text-gray-800">Booking Information</h5>
                                </div>
                                <div class="p-6">
                                    <div class="space-y-6">
                                        <!-- User Information -->
                                        @if(!empty($userinfo['first_name']) && !empty($userinfo['last_name']))
                                        <div>
                                            <h6 class="text-blue-600 font-semibold mb-2">User Information</h6>
                                            <div class="space-y-2">
                                                <div>
                                                    <strong>Name:</strong> <span>{{ $userinfo['first_name'] }} {{ $userinfo['last_name'] }}</span>
                                                </div>
                                                @if(!empty($userinfo['email']))
                                                <div>
                                                    <strong>Email:</strong>
                                                    <a href="mailto:{{ $userinfo['email'] }}" class="text-blue-500 underline">{{ $userinfo['email'] }}</a>
                                                </div>
                                                @endif
                                                @if(!empty($userinfo['phone']))
                                                <div>
                                                    <strong>Phone No:</strong> <span>{{ $userinfo['phone'] }}</span>
                                                </div>
                                                @endif
                                            </div>
                                        </div>
                                        @endif

                                        <!-- Service/Vendor Information -->
                                        @if(!empty($serviceverndor['serivename']))
                                        <div>
                                            <h6 class="text-blue-600 font-semibold mb-2">Service / Vendor Information</h6>
                                            <div class="space-y-2">
                                                <div>
                                                    <strong>Service Name:</strong> <span>{{ $serviceverndor['serivename'] }}</span>
                                                </div>
                                                @if(!empty($serviceverndor['vendorname']))
                                                <div>
                                                    <strong>Vendor Name:</strong> <span>{{ $serviceverndor['vendorname'] }}</span>
                                                </div>
                                                @else
                                                <div>
                                                    <strong>Vendor Name:</strong>
                                                    <span class="text-red-500">No Vendor Assigned For This Service</span>
                                                </div>
                                                @endif
                                            </div>
                                        </div>
                                        @endif

                                        <!-- Additional Information -->
                                        @if(is_array($AdditionalInformation['AddInfoLabel']) && !empty($AdditionalInformation['AddInfoLabel']))
                                        <div>
                                            <h6 class="text-blue-600 font-semibold mb-2">Additional Information</h6>
                                            <div class="space-y-4">
                                                @foreach($AdditionalInformation['AddInfoLabel'] as $index => $label)
                                                @php
                                                $value = $AdditionalInformation['AddInfoValue'][$index];
                                                $formFields = $AdditionalInformation['formStructureArray'];
                                                $fieldDef = $formFields[$index] ?? null;
                                                $fieldName = $fieldDef['name'] ?? null;
                                                @endphp

                                                <div>
                                                    <strong>{{ $label }}:</strong>
                                                    <div class="mt-1">
                                                        @if (is_string($value) && preg_match('/\.(jpg|jpeg|png|gif|bmp)$/i', $value))
                                                        <img src="{{ asset('storage/' . $value) }}" class="rounded-md w-36">
                                                        @elseif (is_string($value) && preg_match('/\.pdf$/i', $value))
                                                        <a href="{{ asset('storage/' . $value) }}" target="_blank" class="text-red-600 underline">
                                                            <i class="fas fa-file-pdf text-xl"></i>
                                                        </a>
                                                        @elseif (is_array($value))
                                                        <ul class="list-disc list-inside text-sm text-gray-700">
                                                            @foreach($value as $dv)
                                                            <li>{{ $dv }}</li>
                                                            @endforeach
                                                        </ul>
                                                        @else
                                                        <p class="text-sm text-gray-700">{{ $value }}</p>
                                                        @endif
                                                    </div>
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Right Column -->
                            @if(is_array($slotedetail) && count($slotedetail) > 0)
                            <div class="bg-white border rounded-lg shadow">
                                <div class="border-b px-6 py-4">
                                    <h5 class="text-lg font-medium text-gray-800">Booked Slot Information</h5>
                                </div>
                                <div class="p-6">
                                    <div class="space-y-4 text-sm text-gray-700">
                                        <div>
                                            <strong>Total number of slots:</strong> {{ count($slotedetail) }}
                                        </div>

                                        @foreach($slotedetail as $index => $slotededata)
                                        <div class="border p-3 rounded bg-gray-50">
                                            <p class="mb-1"><strong>Slot {{ $index + 1 }}:</strong></p>
                                            <p><strong>Date:</strong> {{ $slotededata->date }}</p>
                                            <p><strong>Time:</strong> {{ $slotededata->start }} To {{ $slotededata->end }}</p>
                                            <p><strong>Duration:</strong> {{ $slotededata->duration }}</p>
                                            <p><strong>Price:</strong> {{ str_replace(' ', '', $slotededata->price) }}</p>
                                        </div>
                                        @endforeach
                                    </div>

                                    <div class="mt-6 text-right">
                                        @php
                                            $totalPrice = 0;
                                            $currencySymbol = '';
                                            foreach($slotedetail as $slotededata) {
                                                if($slotededata->price){
                                                    preg_match('/[^\d.,]+/', $slotededata->price, $matches);
                                                    $currencySymbol = $matches[0] ?? '';
                                                    $price = preg_replace('/[^0-9.]/', '', $slotededata->price);
                                                    $totalPrice += floatval($price);
                                                }
                                            }
                                        @endphp
                                        <h5 class="text-lg font-bold text-green-600">
                                            Total Price: {{ $currencySymbol }}{{ number_format($totalPrice, 2) }}
                                        </h5>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </section>
                
            </div>
        </div>
    </div>
</div>
@endsection
