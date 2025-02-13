@extends('layouts.app')
@section('content')
<section class="pcoded-main-container">
    <div class="pcoded-content">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <h5>Add Booking</h5>
                        @if(session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif
                        @if(session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif
                    </div>
                    <div class="card-body">
                        <!-- Modal -->
                        <div class="modal fade" id="formTemplateModal" tabindex="-1" aria-labelledby="formTemplateModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="formTemplateModalLabel">Select Form Template</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <select class="form-control" id="formTemplateSelect">
                                            <option value="">Select a template</option>
                                            @foreach($allforms as $form)
                                                <option value="{{ $form->data }}" data-id="{{ $form->id }}">{{ $form->form_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        <button type="button" id="loadTemplateBtn" class="btn btn-primary">Load Template</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Booking Form -->
                        <form action="{{ route('booking.save') }}" method="POST">
                            @csrf
                            <div id="dynamicFormFields"></div>
                            <input type="hidden" name="booking_form_id" id="bookingFormId">
                            <input type="hidden" name="booking_data" id="bookingData">
                            <input type="hidden" name="customer_id" id="customer_id" value="{{ Auth::id() }}">                            
                            <div class="form-group mt-3">
                                <label>Staff List</label>
                                <select class="form-control" name="selected_staff">
                                    <option value="">Select Staff</option>
                                    @foreach($alluser as $user)
                                        <option value="{{ $user->name }}" data-customer_id="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group mt-3">
                                <label>Service</label>
                                <input type="text" class="form-control" name="service" placeholder="Enter Service">
                                @error('service')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group mt-3">
                                <label>Booking Date/Time</label>
                                <input type="datetime-local" id="booking_datetime" name="booking_datetime" class="form-control">
                                @error('booking_datetime')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-success mt-3">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
