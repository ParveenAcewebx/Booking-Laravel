@extends('layouts.app')
@section('content')

<section class="pcoded-main-container">
    <div class="pcoded-content">
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <div class="page-header-title">
                            <h5 class="m-b-10">Booking Edit</h5>
                        </div>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="feather icon-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="/user">Bookings</a></li>
                            <li class="breadcrumb-item">Edit Booking</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- [ Main Content ] start -->
        <form action="{{ route('booking.update', $booking->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <!-- @method('PUT') -->
            <div class="row">
                <div class="col-md-12 order-md-2">
                    <div class="card">
                        <div class="card-header">
                            <h5>User Information</h5>
                            @if(session('success'))
                                <div class="alert alert-success">{{ session('success') }}</div>
                            @endif
                            @if(session('error'))
                                <div class="alert alert-danger">{{ session('error') }}</div>
                            @endif
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Service</label>
                                        <input type="text" class="form-control" name="service" value="{{ old('service', $booking->service) }}" placeholder="Service">
                                        @error('service')
                                            <div class="error text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <h5>Booking Information</h5>

                            <!-- Dynamic Fields -->
                            <div id="dynamic-form-fields">
                                @if(!empty($dynamicFields) && is_array($dynamicFields))
                                    @foreach($dynamicFields as $key => $value)
                                        <div class="form-group">
                                            <label class="form-label">{{ ucfirst($key) }}</label>
                                            @if(is_array($value))
                                                @foreach($value as $subKey => $subValue)
                                                    <div class="form-check">
                                                        <input 
                                                            type="{{ is_bool($subValue) ? 'checkbox' : 'radio' }}" 
                                                            class="form-check-input" 
                                                            name="dynamic[{{ $key }}][{{ $subKey }}]" 
                                                            value="{{ $subValue }}" 
                                                            @if(in_array($subValue, old('dynamic.' . $key, []))) checked @endif>
                                                        <label class="form-check-label">{{ ucfirst($subValue) }}</label>
                                                    </div>
                                                @endforeach
                                            @elseif(is_string($value))
                                                <input type="text" class="form-control" name="dynamic[{{ $key }}]" value="{{ old('dynamic.' . $key, $value) }}">
                                            @endif
                                            @error("dynamic.{$key}")
                                                <div class="error text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    @endforeach
                                @else
                                    <p>No dynamic fields available.</p>
                                @endif
                            </div>

                            <!-- Staff Field -->
                            <div class="form-group">
                                <label class="form-label">Staff</label>
                                <select class="form-control" name="staff">
                                    @foreach($staffList as $staff)
                                        <option value="{{ $staff->id }}" {{ $booking->staff_id == $staff->id ? 'selected' : '' }}>
                                            {{ $staff->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Booking Date -->
                            <div class="form-group">
                                <label class="form-label">Booking Date and Time</label>
                                <input type="datetime-local" class="form-control" name="booking_datetime" value="{{ old('booking_datetime', $booking->booking_datetime) }}">
                            </div>

                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </div>    
                </div>
            </div>
        </form>
        <!-- [ Main Content ] end -->
    </div>
</section>

@endsection
