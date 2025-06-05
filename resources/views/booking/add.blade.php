@extends('layouts.app')
@section('content')
<section class="pcoded-main-container">
    <div class="pcoded-content">
        <!-- [ Main Content ] start -->
        <div class="row">
            <!-- [ Booking Validation ] start -->
            <div class="col-sm-12">
        <div class="row">
            <div class="col-sm-12">
                <div class="page-header">
                    <div class="page-block">
                        <div class="row align-items-center">
                            <div class="col-md-12">
                                <div class="page-header-title">
                                    <h5 class="m-b-10">Add Booking</h5>
                                </div>
                                <ul class="breadcrumb">
                                    <li class="breadcrumb-item"><i class="feather icon-home"></i></li>
                                    <li class="breadcrumb-item"><a href="{{ route('booking.list') }}">Bookings</a></li>
                                    <li class="breadcrumb-item"><a href="">Add Booking</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h5>Add Booking</h5>
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
                        <form action="{{ route('booking.save') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                    <div class="card-body">
                        <!-- Modal -->
                        <div class="modal fade" id="bookingTemplateModal" tabindex="-1" aria-labelledby="bookingTemplateModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="bookingTemplateModalLabel">Select Booking Template</h5>
                                    </div>
                                    <div class="modal-body">
                                        <select class="form-control" id="bookingTemplateselect">
                                            <option value="">Select a template</option>
                                            @foreach($alltemplates as $template)
                                                <option value="{{ $template->data }}" data-id="{{ $template->id }}">{{ $template->template_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" id="loadTemplateBtn" class="btn btn-primary">Load Template</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Booking Form -->
                        <form action="{{ route('booking.save') }}" method="POST">
                            @csrf
                            <div id="dynamictemplateFields"></div>
                            <input type="hidden" name="booking_template_id" id="bookingTemplateId">
                            <input type="hidden" name="booking_data" id="bookingData">
                            <input type="hidden" name="customer_id" id="customer_id" value="{{ Auth::id() }}">  
                            <div class="row">
                                <div class="col-md-4">                          
                                    <div class="form-group mt-3">
                                        <label>Staff List</label>
                                        <select class="form-control" name="selected_staff">
                                            <option value="">Select Staff</option>
                                            @foreach($alluser as $user)
                                                <option value="{{ $user->name }}" data-customer_id="{{ $user->id }}">{{ $user->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('selected_staff')
                                        <div class="error">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4"> 
                                    <div class="form-group mt-3">
                                        <label>Booking Date/Time</label>
                                        <input type="datetime-local" id="booking_datetime" name="booking_datetime" class="form-control">
                                        @error('booking_datetime')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
            <!-- [ Booking Validation ] end -->
        </div>
        <!-- [ Main Content ] end -->
    </div>
</section>
@endsection
