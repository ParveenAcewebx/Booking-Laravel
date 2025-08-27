@extends('admin.layouts.app')

@section('content')
<div class="pcoded-main-container">
    <div class="pcoded-content">
        <!-- [ Breadcrumb ] start -->
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <div class="page-header-title">
                            <h5>Add Booking</h5>
                        </div>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><i class="feather icon-home"></i></li>
                            <li class="breadcrumb-item"><a href="#!">Booking</a></li>
                            <li class="breadcrumb-item"><a href="#!">Add Booking</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- [ Breadcrumb ] end -->

        <!-- Form for Booking -->
        <form action="{{ route('booking.save') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-md-12 order-md-1">
                    <div class="card">
                        <div class="card-header">
                            <h5>Booking Information</h5>
                        </div>
                        <div class="card-body">
                            <!-- Modal for Template Selection -->
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
                                                <option value="{{ $template->data }}" data-id="{{ $template->id }}">
                                                    {{ $template->template_name }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="modal-footer">
                                            <a href="{{ route('booking.list') }}" class="btn btn-secondary">Back</a>
                                            <button type="button" id="loadTemplateBtn" class="btn btn-primary">Load Template</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Dynamic Fields for Booking -->
                            <div id="dynamictemplateFields"></div>
                            <input type="hidden" name="booking_template_id" id="bookingTemplateId">
                            <input type="hidden" name="booking_data" id="bookingData">
                            <input type="hidden" name="customer_id" id="customer_id" value="{{ Auth::id() }}">

                            <!-- Submit Button (will only work after template is selected) -->
                        </div>
                    </div>
                </div>
                <!-- Loader -->
                <div class="loader-block d-none">
                <div class="loader-content">
                    <div class="loader-circle"></div>
                </div>
                </div>

                <!-- Right Column -->
                <!-- <div class="col-md-4 order-md-2">
                    <div class="card">
                        <div class="card-header">
                            <h5>Settings</h5>
                        </div>
                        <div class="card-body">
                            <div class="form-group mt-3">
                                <label>Booking Date/Time</label>
                                <input type="datetime-local" id="booking_datetime" name="booking_datetime" class="form-control">
                                @error('booking_datetime')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="row mt-4">
                            <div class="col-md-12 text-right">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </div>
                        </div>
                        
                    </div>
                </div> -->
            </div>
        </form>
    </div>
</div>
@endsection