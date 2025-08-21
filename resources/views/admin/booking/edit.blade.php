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
            <!-- [ Left Column (8) ] start -->
            @if(!empty($userinfo['first_name']) || !empty($userinfo['last_name']) || !empty($userinfo['email']) || !empty($userinfo['phone']))
                <div class="col-md-6 col-xl-4">
                    <div class="card">
                        <div class="card-header"><h5>User Information</h5></div>
                        <div class="card-body">
                            <div class="invoice-box">
                                <div class="user-info">
                                    @if(!empty($userinfo['first_name']) && !empty($userinfo['last_name']))
                                        <div class="info-item">
                                            <strong>Name:</strong> {{ $userinfo['first_name'] }} {{ $userinfo['last_name'] }}
                                        </div>
                                    @endif

                                    @if(!empty($userinfo['email']))
                                        <div class="info-item">
                                            <strong>Email:</strong> <a href="mailto:{{ $userinfo['email'] }}" class="text-info">{{ $userinfo['email'] }}</a>
                                        </div>
                                    @endif

                                    @if(!empty($userinfo['phone']))
                                        <div class="info-item">
                                            <strong>Phone No:</strong> {{ $userinfo['phone'] }}
                                        </div>
                                    @endif
                                </div>
                            </div>              
                        </div>
                    </div>
                </div>
            @endif

            @if(!empty($serviceverndor['serivename']) || !empty($serviceverndor['vendorname']))
                <div class="col-md-6 col-xl-4">
                    <div class="card">
                        <div class="card-header"><h5>Service / Vendor Information</h5></div>
                        <div class="card-body">
                            <div class="invoice-box">
                                <div class="service-vendor-info">
                                    @if(!empty($serviceverndor['serivename']))
                                        <div class="info-item">
                                            <strong>Service Name:</strong> {{ htmlspecialchars($serviceverndor['serivename']) }}
                                        </div>
                                    @endif

                                    @if(!empty($serviceverndor['vendorname']))
                                        <div class="info-item">
                                            <strong>Vendor Name:</strong> {{ htmlspecialchars($serviceverndor['vendorname']) }}
                                        </div>
                                    @endif
                                </div>
                            </div>              
                        </div>
                    </div>
                </div>
            @endif

            @if(is_array($slotedetail) && count($slotedetail) > 0)
                <div class="col-md-6 col-xl-4">
                    <div class="card">
                        <div class="card-header"><h5>Booking Information</h5></div>
                        <div class="card-body">
                            <div class="invoice-box">
                                <div class="booking-info">
                                    <div class="info-item">
                                        <strong>Total number of slots:</strong> {{ count($slotedetail) }}
                                    </div>
                                    @foreach($slotedetail as $index => $slotededata)
                                        <div class="info-item">
                                            <p><strong>Slot {{ $index + 1 }}:</strong></p>
                                            <strong>Date:</strong> {{ $slotededata->date }}<br>
                                            <strong>Time:</strong> {{ $slotededata->start }} To {{ $slotededata->end }}<br>
                                            <strong>Duration:</strong> {{ $slotededata->duration }}<br>
                                            <strong>Price:</strong> {{ $slotededata->price }}
                                        </div>
                                    @endforeach
                                </div>
                            </div>              
                        </div>
                          @if(is_array($slotedetail) && count($slotedetail) > 0)
                                <div class="card-body">
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
                        @endif
                    </div>
                </div>
            @endif
           @if(is_array($AdditionalInformation['AddInfoLabel']) && !empty($AdditionalInformation['AddInfoLabel']))
            <div class="col-md-6 col-xl-4">
                <div class="card">
                    <div class="card-header"><h5>Additional Information</h5></div>
                    <div class="card-body">
                        <div class="additional-info">
                            <div class="info-item">
                                <div class="field-name">
                                    @foreach($AdditionalInformation['AddInfoLabel'] as $index => $label)
                                        @php
                                            $value = $AdditionalInformation['AddInfoValue'][$index];
                                        @endphp
                                        <div>
                                            <strong>{{ $label }}:</strong>
                                            <div class="field-value">
                                                @if (is_string($value) && preg_match('/\.(jpg|jpeg|png|gif|bmp)$/i', $value))  
                                                    <img src="{{ asset('storage/' . $value) }}" alt="" style="max-width: 100px; height: auto;">
                                                @elseif (is_string($value) && preg_match('/\.pdf$/i', $value))
                                                    <a href="{{ asset('storage/' . $value) }}" target="_blank">
                                                        <i class="fas fa-file-pdf" style="font-size: 30px; color: red;"></i>
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
                        </div>
                    </div>
                </div>
            </div>
        @endif
        </div>
  </div>
</section>
@endsection
