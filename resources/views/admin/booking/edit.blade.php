@extends('admin.layouts.app')

@section('content')
<section class="pcoded-main-container">
    <div class="pcoded-content">
        <!-- [ Page Header ] start -->
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <div class="page-header-title">
                            <h5 class="m-b-10">Edit Booking</h5>
                        </div>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('dashboard') }}">
                                    <i class="feather icon-home"></i>
                                </a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="#!">Booking</a>
                            </li>
                            <li class="breadcrumb-item"><a href="#!">Edit Booking</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- [ Page Header ] end -->

        <!-- [ Form Section ] start -->
        <div class="row">
            <!-- [ Left Column (8) ] start -->
            <div class="col-md-12 order-md-1">
                <div class="card">
                    <div class="card-header text-white" style="background-color: #0073aa;">
                        <h5 class="text-white">Booking Information</h5>
                    </div>
                    <div class="card-body">
                        <!-- User Info Section -->
                        @if(!empty($userinfo['first_name']) || !empty($userinfo['last_name']) || !empty($userinfo['email']) || !empty($userinfo['phone']))
                        <div class="row">
                            <div class="col-md-8">
                                <div class="invoice-box">
                                    <h6 class="ml-2">User Information</h6>
                                    <table class="table table-borderless">
                                      <tbody>
                                            @if(!empty($userinfo['first_name']) && !empty($userinfo['last_name']))
                                                <tr>
                                                    <td><strong>Name:</strong> {{ $userinfo['first_name'] }} {{ $userinfo['last_name'] }}</td>
                                                </tr>
                                            @endif

                                            @if(!empty($userinfo['email']))
                                                <tr>
                                                    <td><strong>Email:</strong> <a href="mailto:{{ $userinfo['email'] }}" class="text-info">{{ $userinfo['email'] }}</a></td>
                                                </tr>
                                            @endif

                                            @if(!empty($userinfo['phone']))
                                                <tr>
                                                    <td><strong>Phone No:</strong> {{ $userinfo['phone'] }}</td>
                                                </tr>
                                            @endif
                                      </tbody>
                                    </table>
                                </div>
                            </div>
                        @endif

                        <!-- Service / Vendor Info Section -->
                        @if(!empty($serviceverndor['serivename']) || !empty($serviceverndor['vendorname']))
                            <div class="col-md-4">
                                <h6 class="ml-2">Service / Vendor Information</h6>
                                <table class="table table-borderless">
                                    <tbody>
                                        @if(!empty($serviceverndor['serivename']))
                                            <tr>
                                                <td><strong>Service Name:</strong> {{ htmlspecialchars($serviceverndor['serivename']) }}</td>
                                            </tr>
                                        @endif

                                        @if(!empty($serviceverndor['vendorname']))
                                            <tr>
                                                <td><strong>Vendor Name:</strong> {{ htmlspecialchars($serviceverndor['vendorname']) }}</td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                     
                        @endif
                        </div>

                    </div>

                    <div class="card-body">
                        <!-- Booking Details Section -->
                        @if(is_array($slotedetail) && count($slotedetail) > 0)
                        <div class="row">
                            <div class="col-md-8">
                                <h6 class="ml-2">Booking Information</h6>
                                <table class="table table-responsive">
                                    <tbody>
                                        <tr>
                                            <td><strong>Total number of slots:</strong> {{ count($slotedetail) }}</td>
                                        </tr>
                                        @foreach($slotedetail as $index => $slotededata)
                                            <tr>
                                                <th>Slot {{ $index + 1 }}:</th>
                                                <td>
                                                    <strong>Date:</strong> {{ $slotededata->date }}<br>
                                                    <strong>Time:</strong> {{ $slotededata->start }} To {{ $slotededata->end }}<br>
                                                    <strong>Duration:</strong> {{ $slotededata->duration }}<br>
                                                    <strong>Price:</strong> {{ $slotededata->price }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Total Price Section -->
                            <div class="col-md-4">
                                <h6 class="m-b-20">Amount</h6>
                                <h5 class="text-uppercase text-primary">Total Price: 
                                    @php
                                        $totalPrice = 0;
                                        foreach($slotedetail as $slotededata) {
                                           $price = str_replace(['$', ' '], '', $slotededata->price);
                                           $totalPrice += (float) $price;
                                        }
                                    @endphp
                                    <span>${{ number_format($totalPrice, 2) }}</span>    
                                </h5>
                            </div>
                        </div>
                        @else
                            <p>No booking slots available.</p> <!-- In case $slotedetail is empty or null -->
                        @endif
                    </div>

                    <div class="card-body">
                        <!-- Additional Fields Section -->
                        @if(is_array($userinfo) && count($userinfo) > 0) <!-- Check if $userinfo is an array and not empty -->
                        <h6>Additional Information</h6>
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Field</th>
                                        <th>Value</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($userinfo as $key => $value)
                                        @if(!in_array($key, ['first_name', 'last_name', 'email', 'phone', 'service', 'vendor']))
                                            <tr>
                                                <th>{{ ucfirst(str_replace('_', ' ', $key)) }}:</th>
                                                <td>
                                                    @if(is_array($value) || is_object($value))
                                                        @foreach($value as $subKey => $subValue)
                                                            <strong>{{ ucfirst(str_replace('_', ' ', $subKey)) }}:</strong> 
                                                            @if (strpos($subKey, 'file-') === 0) <!-- Check if the key starts with 'file-' -->
                                                                <img src="{{ asset('storage/' . $subValue) }}" alt="{{ $subKey }}" style="max-width: 100px; height: auto;">
                                                            @else
                                                                {{ $subValue }}
                                                            @endif
                                                            <br>
                                                        @endforeach
                                                    @else
                                                    @if (preg_match('/\.(jpg|jpeg|png|gif|bmp)$/i', $value)) 
                                                            <img src="{{ asset('storage/' . $value) }}" alt="{{ $key }}" style="max-width: 100px; height: auto;">
                                                        @else
                                                            {{ $value }}
                                                        @endif
                                                    @endif
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                            <p>No additional information available.</p> <!-- In case $userinfo is empty -->
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <!-- [ Form Section ] end -->
    </div>
</section>
@endsection
