@extends('frontend.layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8" x-data="{ tab: 'bookings' }">
    <!-- Page Header -->
    <div class="mb-8 text-center">
        <h1 class="text-3xl font-bold text-gray-800">Vendor Dashboard</h1>
        <p class="text-gray-600 mt-2">Manage services, staff, and bookings in one place</p>
    </div>

    <div class="flex gap-6">
        <!-- Sidebar Tabs -->
        <div class="w-1/4 bg-white shadow rounded-2xl p-4">
            <ul class="space-y-2">
                <!-- Bookings (now first tab) -->
                <li>
                    <button 
                        @click="tab = 'bookings'" 
                        :class="tab === 'bookings' ? 'bg-blue-500 text-white' : 'bg-gray-100 text-gray-700'" 
                        class="w-full px-4 py-2 rounded-lg text-left">
                        Bookings
                    </button>
                </li>

                <!-- Services -->
                <li>
                    <button 
                        @click="tab = 'services'" 
                        :class="tab === 'services' ? 'bg-blue-500 text-white' : 'bg-gray-100 text-gray-700'" 
                        class="w-full px-4 py-2 rounded-lg text-left">
                       Services
                    </button>
                </li>

                <!-- Staff -->
                <li>
                    <button 
                        @click="tab = 'staff'" 
                        :class="tab === 'staff' ? 'bg-blue-500 text-white' : 'bg-gray-100 text-gray-700'" 
                        class="w-full px-4 py-2 rounded-lg text-left">
                        Staff Members
                    </button>
                </li>
            </ul>
        </div>

        <!-- Tab Content -->
        <div class="w-3/4 bg-white shadow rounded-2xl p-6">
            
            <!-- Bookings Tab (Default) -->
            <div x-show="tab === 'bookings'">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Bookings</h2>
                <div class="space-y-4">
                    @if($bookingdata->count() > 0)
                        @foreach($bookingdata as $booking)
                        <a href="#">
                            <div class="p-4 border rounded-lg hover:shadow mb-3">
                                <h3 class="font-medium text-gray-800">
                                    {{-- Customer Name --}}
                                    {{ $booking->first_name ?? json_decode($booking->booking_data)->first_name ?? 'Unknown' }}
                                    {{ $booking->last_name ?? json_decode($booking->booking_data)->last_name ?? '' }}
                                    
                                    {{-- Service --}}
                                    - {{ $booking->service->name ?? '' }}

                                    {{-- Template Name --}}
                                    @if($booking->template)
                                        <span class="text-sm text-gray-500"> (Template: {{ $booking->template->template_name }})</span>
                                    @endif
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
                                    {{ $booking->status === 'pending' ? 'bg-yellow-100 text-yellow-700' : 'bg-green-100 text-green-700' }}">
                                    {{ ucfirst($booking->status) }}
                                </span>
                            </div>
                        </a>
                        @endforeach
                    @else
                        <p class="text-gray-500">No bookings found.</p>
                    @endif
                </div>
            </div>

            <!-- Services Tab -->
            <div x-show="tab === 'services'">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Services</h2>
                {{-- Services list here --}}
                 @if($servicedata && $servicedata->count() > 0)
                 @foreach($servicedata as $services_data)
                 <a href="{{$services_data->id}}">
                    <div class="space-y-4 mb-4">
                        <div class="p-4 border rounded-lg hover:shadow">
                            <h2 class="mb-2 text-xl text-gray-800">{{$services_data->name}}</h2>
                            @php
                                $desc = $services_data->description;
                                $desc = preg_replace('/^<p>|<\/p>$/', '', $desc);
                                @endphp
                                <p><strong>Description</strong> {!! $desc !!}</p>
                                <p><strong>Category</strong> {{ $services_data->category ? $services_data->category : 'Not assigned' ;}}</p>
                                <p><strong>Status</strong> {{$services_data->status == 1 ? 'Active' : 'Inactive'; }}</p>
                                <p><strong>Price</strong> {{$services_data->currency}}{{$services_data->price}}</p>
                               @php
                                $duration = $services_data->duration; // assume duration is in minutes
                            @endphp
                            <p>
                                <strong>Duration:</strong>
                                @if($duration < 60)
                                    {{ $duration }} minutes
                                @elseif($duration % 60 == 0)
                                    {{ $duration / 60 }} hour{{ $duration >= 120 ? 's' : '' }}
                                @else
                                    {{ intdiv($duration, 60) }} hour{{ intdiv($duration, 60) > 1 ? 's' : '' }}
                                    {{ $duration % 60 }} minutes
                                @endif
                            </p>
                        </div>    
                    </div>
                        </a>
                    @endforeach
                    @else
                      <p class="text-gray-500">No Service found.</p>
                @endif
            </div>

            <!-- Staff Tab -->
            <div x-show="tab === 'staff'">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Staff Members</h2>
                {{-- Staff list here --}}
                <div class="space-y-4">
                  @if($staffdata && $staffdata->count() > 0)
                  @foreach($staffdata as $staff) 
                  <a href="{{$staff->id}}">
                    <div class="flex items-center justify-between p-4 border rounded-lg hover:shadow mb-4">
                        <div class="flex items-center gap-3">
                            <img src="{{$staff->avatar ? $staff->avatar : 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAKQAAACUCAMAAAAqEXLeAAAAMFBMVEXk5ueutLersbTn6eq9wsSzuLvFycvJzc+nrbHh4+TCxsjY29y2u77e4OG6v8HS1dfKF8seAAAEVUlEQVR4nO2c25arIAyGhQjISd7/bQdpO7WtVpQE7Fr+F3vNvppvJeZAINN1ly5dunTp0qVLZxZA+jf9BI1ZFhWpvA1KStMbo1SwvjsbKIA1TjD+FHPO2DNhajtGqk/xobe6NVwSaOUWERMmF8q3NydIsUZ452Sqa4oJXVg14tycoSEm+HEbMWH27XweWB7jJNWEEro+H5GxwTSIc9CZrv53uavP6He4+iH/A4xM1A0fv5Ec11QREfQROyZbVoyenTHzFB+rMcqjjJHS1PksIRxnjPky1KEsYZxCvAbjrkLzKd5XYCxydqKs4HBRyBhFDQmq1JDRlJKastyOjDna2MEw5PRVkkJ6h8DIiGt4cWjfRJrRYURhjF8lIaQekCCZJWPECZskunMZIIXN1LKRNZYei5HRnXcg4DGSpUooaHY/IKlKoy5s0l4kqCDR4iZqIIL0iIZkA1F4W0xITpTOkQr3HZKmfCPWG0YW3mAwIVlPA4mZgcggDw9XFjUSQWIykkH+giVxv0kiyJ+I7p/Ik7iQRAcI3LJIVLtRGwyqLsgjDNSekFRNL2qipDrTIuYgsgH/T5wWO4sHSXjPiBc5dBMMvIM3VSqf5PGmanSQgOVvqtFAksIxJe1VDlLRGQgRsUKHS1JIpFEL3TA6CeMMQX/nrREMSf7Gobw/p0zkD5VOKas8YoLS2CEsNjPKojRUw9lJBRHOKS/sXlRQd+q9XgJ7tITzKh/kg/L8jN3BQUGNdyxzHXlmVZtx8vjO14m8ToJ8o9z3hpKLBozTK8od+ZLTjCNzMHPfcHNR/XOcUXqTkzGHhm/hE6Z1G9bk3DVf0QAIy3suD0QTzrCVA52XfFjk5FzatiskMwFYeV9susPFH8QoT7XadFsRC0r2zgkhnDNSBX8aG84FX/53DsGSWkP9K9FoG6KzjekfMlLe1wIbo8Zfr7VVxon/iHmL7Sl8emW97posMd4CxTi2iPeKOvAY6Mrqupzxt4U+pZzvfC9GFWJUvhJndLFVYjl3b6IOQ18hM8UiKNdXKbM440dKW8u1FEc3cWacjI1UDTDoMB7z8iKoIijqoMvcvIDZ47ZHMd3gPm24YcZ+Hc+aYA2qEWecDgkToC8PllVK7hBiCDTS1c2qBlc65odwcEFxh2L7rgusuXtf9igmKzjx5myYI2EePPRGM1YiTJSHjAmWLqaXMc3+bIT6nCpLw84hws4teCTtG7ztnOrhaci/P9k9H8VT9i3P0ak9DmXeDe7x+w8cypxV26Z2TJTbtmzOmEFZfAWLQrkRPdqdAHLrwqdO17Opb9d7mEtghVq9zC37iweoWk9EmGuJpVoLHuTdhkLxxZYIqE9cO7X46OVMzp606PDzRPZDnxGO+hwfRZ/l8VxRcxN/MyXo8zF+mBL61kSLen2MbjF3L9DE1Qtk/QNslsQL5CkN+dYNtT3WrIvPVvKQ9+gQxWex3ZplXc8e/XzV5qFbqvwDWcU9KP5NGS4AAAAASUVORK5CYII=' }}" alt="" class="w-10 h-10 rounded-full">
                            <div>
                                <h3 class="font-medium text-gray-800">{{$staff->name}}</h3>
                                <p class="text-sm text-gray-500"><strong>Status</strong> {{$staff->status == 1 ? 'active':'inactive'; }}</p>
                                <p class="text-sm text-gray-500"><strong>Number</strong> {{$staff->phone_code}} {{$staff->phone_number}}</p>
                                <p class="text-sm text-gray-500"><strong>Email</strong> {{$staff->email}}</p>
                            </div>
                        </div>
                        <!-- <button class="px-3 py-1 text-sm bg-blue-500 text-white rounded-lg hover:bg-blue-600">Edit</button> -->
                    </div>
                    </a>
                    @endforeach
                    @else
                     <p class="text-gray-500">No Staff Member found.</p>
                @endif
                </div>
                <div class="mt-4">
                    <!-- <button class="w-full py-2 bg-green-500 text-white rounded-lg hover:bg-green-600">+ Add New Staff</button> -->
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
