@extends('layouts.app')
@section('content')

<section class="pcoded-main-container">
    <div class="pcoded-content">
        <!-- [ breadcrumb ] start -->
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <div class="page-header-title">
                            <h5 class="m-b-10">Booking Edit</h5>
                        </div>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('home') }}"><i class="feather icon-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="/user">Bookings</a></li>
                            <li class="breadcrumb-item"><a href="#!">Booking Edit</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- [ breadcrumb ] end -->
        <!-- [ Main Content ] start -->
        <form action="{{ route('booking.update', $booking->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-md-12 order-md-2">
                    <div class="card">
                        <div class="card-header">
                            <h5>Booking Information</h5>
                            @if(session('success'))
                                <div class="alert alert-success" role="alert">
                                    {{ session('success') }}   
                                </div>
                            @endif
                            @if(session('error'))
                                <div class="alert alert-danger" role="alert">
                                    {{ session('error') }}
                                </div>
                            @endif
                        </div>
                        <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Name</label>
                                            <input type="text" class="form-control" name="service" value="{{ old('service', $booking->service) }}" placeholder="service">
                                            @error('service')
                                            <div class="error">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </div>	
                </div>
                <!-- [ Form Validation ] end -->
            </div>
        </form>
        <!-- [ Main Content ] end -->
    </div>
</section>

@endsection