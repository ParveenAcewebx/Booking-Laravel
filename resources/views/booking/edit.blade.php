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
                            <h5>Booking Information</h5>
                            <!-- Dynamic Fields -->
                            <div id="dynamic-form-fields">
                                @if(!empty($fieldsWithValues) && is_array($fieldsWithValues))
                                    @foreach($fieldsWithValues as $field)
                                        <div class="form-group">
                                            <label class="form-label">{{ $field['label'] ?? ucfirst($field['name']) }}</label>

                                            @if($field['type'] === 'text')
                                                <input type="text" class="form-control" name="dynamic[{{ $field['name'] }}]" value="{{ old('dynamic.' . $field['name'], $field['value']) }}">

                                            @elseif($field['type'] === 'textarea')
                                                <textarea class="form-control" name="dynamic[{{ $field['name'] }}]">{{ old('dynamic.' . $field['name'], $field['value']) }}</textarea>

                                            @elseif($field['type'] === 'select')
                                                <select class="form-control" name="dynamic[{{ $field['name'] }}]">
                                                    @foreach($field['values'] as $option)
                                                        <option value="{{ $option['value'] }}" {{ $option['value'] == $field['value'] ? 'selected' : '' }}>
                                                            {{ $option['label'] }}
                                                        </option>
                                                    @endforeach
                                                </select>

                                            @elseif($field['type'] === 'radio-group')
                                                @foreach($field['values'] as $option)
                                                    <div class="form-check">
                                                        <input 
                                                            type="radio" 
                                                            class="form-check-input" 
                                                            name="dynamic[{{ $field['name'] }}]" 
                                                            value="{{ $option['value'] }}" 
                                                            {{ $option['value'] == $field['value'] ? 'checked' : '' }}>
                                                        <label class="form-check-label">{{ $option['label'] }}</label>
                                                    </div>
                                                @endforeach
                                            @endif

                                            @error("dynamic.{$field['name']}")
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
