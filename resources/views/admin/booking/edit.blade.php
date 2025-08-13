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
        <form action="{{ route('booking.update', $booking->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row">
                <!-- [ Left Column (8) ] start -->
                <div class="col-md-8 order-md-1">
                    <div class="card">
                        <div class="card-header">
                            <h5>Booking Information</h5>
                        </div>
                        <div class="card-body">
                            {{-- Success or Error Messages --}}
                            @if(session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                            @endif
                            @if(session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                            @endif

                            <!-- <h5 class="mb-4">Booking Information</h5> -->

                            {{-- Rendered Dynamic Fields --}}
                            {!! $dynamicFieldHtml !!}
                        </div>
                    </div>
                </div>
                <!-- [ Left Column (8) ] end -->

                <!-- [ Right Column (4) ] start -->
                <div class="col-md-4 order-md-2">
                    <div class="card">
                        <div class="card-header">
                            <h5>Settings</h5>
                        </div>
                        <div class="card-body">
                            <!-- Status -->
                            <div class="row">
                                <!-- Staff Dropdown -->
                               
                                </div>

                                <!-- Booking Date and Time -->
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-label">Booking Date and Time</label>
                                        <input type="datetime-local" class="form-control" name="booking_datetime"
                                            value="{{ old('booking_datetime', $booking->booking_datetime) }}" required>
                                    </div>
                                  {{-- Save Button for Settings --}}
                                    <div class="text-right mt-4">
                                        <button type="submit" class="btn btn-primary">Update</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- [ Right Column (4) ] end -->
            </div>
        </form>
        <!-- [ Form Section ] end -->
    </div>
</section>
@endsection