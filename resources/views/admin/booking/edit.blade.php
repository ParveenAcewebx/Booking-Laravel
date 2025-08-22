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
                            <h5 class="m-b-10">Booking #{{$bookingid}}</h5>
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
                            <li class="breadcrumb-item"><a href="#!">Booking #{{$bookingid}}</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- [ Page Header ] end -->

        <!-- [ Form Section ] start -->
        <div class="row">
         <!-- [ Left Column (6) ] start -->
<div class="col-md-6">
    <div class="card shadow-lg rounded-lg">
        <div class="card-header">
                <h5>Booking Information</h5>
        </div>
        <div class="card-body">
            <div class="invoice-box">
                <!-- User Information Section -->
                <div class="info-section mb-4">
                    <h6 class="info-title text-primary">User Information</h6>
                    <div class="user-info">
                        @if(!empty($userinfo['first_name']) && !empty($userinfo['last_name']))
                        <div class="info-item">
                            <strong>Name:</strong> <span class="font-weight-bold">{{ $userinfo['first_name'] }} {{ $userinfo['last_name'] }}</span>
                        </div>
                        @endif

                        @if(!empty($userinfo['email']))
                        <div class="info-item">
                            <strong>Email:</strong> 
                            <a href="mailto:{{ $userinfo['email'] }}" class="text-info">{{ $userinfo['email'] }}</a>
                        </div>
                        @endif

                        @if(!empty($userinfo['phone']))
                        <div class="info-item">
                            <strong>Phone No:</strong> <span>{{ $userinfo['phone'] }}</span>
                        </div>
                        @endif
                    </div>
                </div>
                
                <!-- Service/Vendor Information Section -->
                @if(!empty($serviceverndor['serivename']) || !empty($serviceverndor['vendorname']))
                <div class="info-section mb-4">
                    <h6 class="info-title text-primary">Service / Vendor Information</h6>
                    <div class="service-vendor-info">
                        @if(!empty($serviceverndor['serivename']))
                        <div class="info-item">
                            <strong>Service Name:</strong> <span class="font-weight-bold">{{ htmlspecialchars($serviceverndor['serivename']) }}</span>
                        </div>
                        @endif

                        @if(!empty($serviceverndor['vendorname']))
                        <div class="info-item">
                            <strong>Vendor Name:</strong> <span class="font-weight-bold">{{ htmlspecialchars($serviceverndor['vendorname']) }}</span>
                        </div>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Additional Information Section -->
                @if(is_array($AdditionalInformation['AddInfoLabel']) && !empty($AdditionalInformation['AddInfoLabel']))
                <div class="info-section mb-4">
                    <h6 class="info-title text-primary">Additional Information</h6>
                    <div class="additional-info">
                        @foreach($AdditionalInformation['AddInfoLabel'] as $index => $label)
                            @php
                            $value = $AdditionalInformation['AddInfoValue'][$index];
                            @endphp
                            <div class="info-item">
                                <strong>{{ $label }}:</strong>
                                <div class="field-value">
                                    @if (is_string($value) && preg_match('/\.(jpg|jpeg|png|gif|bmp)$/i', $value))
                                    <img src="{{ asset('storage/' . $value) }}" alt="Image" class="img-fluid rounded" style="max-width: 150px;">
                                    @elseif (is_string($value) && preg_match('/\.pdf$/i', $value))
                                    <a href="{{ asset('storage/' . $value) }}" target="_blank" class="text-danger">
                                        <i class="fas fa-file-pdf" style="font-size: 30px;"></i>
                                    </a>
                                    @elseif (is_array($value))
                                    @php
                                    $formattedOptions = implode(', ', $value);
                                    @endphp
                                    <label class="info-label">{{ $formattedOptions }}</label>
                                    @else
                                    @php
                                    $matchedValues = (array) $value;
                                    $formfileddata = $AdditionalInformation['formStructureArray'];
                                    $valueinputlabel = [];
                                    foreach ($formfileddata as $item) {
                                        if (isset($item['values']) && is_array($item['values'])) {
                                            foreach ($item['values'] as $subvalue) {
                                                if (in_array($subvalue['value'], $matchedValues)) {
                                                    $valueinputlabel[] = $subvalue['label'];
                                                }
                                            }
                                        }
                                    }
                                    @endphp
                                    @if(count($valueinputlabel) > 0)
                                    <p>{{ implode(', ', $valueinputlabel) }}</p>
                                    @else
                                    <p>{{ $value }}</p>
                                    @endif
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
</div>
<!-- [ Left Column (6) ] end -->
            <!-- [ Right Column (6) ] start -->
            @if(is_array($slotedetail) && count($slotedetail) > 0)
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5>Booked Slot Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="invoice-box">
                                <div class="booking-info">
                                    <div class="info-item">
                                        <strong>Total number of slots:</strong> {{ count($slotedetail) }}
                                    </div>
                                    @foreach($slotedetail as $index => $slotededata)
                                        <div class="info-item">
                                            <p class="mt-4 mb-0"><strong>Slot {{ $index + 1 }}:</strong></p>
                                            <strong>Date:</strong> {{ $slotededata->date }}<br>
                                            <strong>Time:</strong> {{ $slotededata->start }} To {{ $slotededata->end }}<br>
                                            <strong>Duration:</strong> {{ $slotededata->duration }}<br>
                                            <strong>Price:</strong>{{str_replace(' ', '', $slotededata->price)}}
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div class="card-body">
                            <h5 class="text-uppercase text-primary">Total Price:
                                @php
                                $totalPrice = 0;
                                foreach($slotedetail as $slotededata) {
                                    $get_currencyprice = explode(' ', $slotededata->price);
                                    $price = $get_currencyprice[1];
                                    $totalPrice += (float) $price;
                                }
                                @endphp
                                <span>{{$get_currencyprice[0]}}{{number_format($totalPrice, 2) }}</span>
                            </h5>
                        </div>
                    </div>
                </div>
            @endif
            <!-- [ Right Column (6) ] end -->
        </div>
    </div>
</section>
@endsection
