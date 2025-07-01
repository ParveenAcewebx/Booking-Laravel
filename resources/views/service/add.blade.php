@extends('layouts.app')

@section('content')
<div class="pcoded-main-container">
    <div class="pcoded-content">
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <div class="page-header-title">
                            <h5 class="m-b-10">Add Service</h5>
                        </div>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="feather icon-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="{{ route('service.list') }}">Services</a></li>
                            <li class="breadcrumb-item">Add Service</li>
                        </ul>
                    </div>
                </div>
            </div>
            @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <ul class="nav nav-tabs mb-3" role="tablist">
                            <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#service-details" role="tab"><i class="feather icon-info"></i> Service Details</a></li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#pricing" role="tab">
                                    <i class="feather icon-tag"></i>
                                    Pricing
                                </a>
                            </li>
                            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#gallery" role="tab"><i class="feather icon-image"></i> Gallery</a></li>
                            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#settings" role="tab"><i class="feather icon-settings"></i> Settings</a></li>
                        </ul>

                        <form method="POST" action="{{ route('service.store') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="tab-content">
                                {{-- SERVICE DETAILS --}}
                                <div class="tab-pane active" id="service-details" role="tabpanel">
                                    <div class="form-group">
                                        <label>Name</label>
                                        <input type="text" name="name" class="form-control" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Description</label>
                                        <div id="quill-editor" style="height: 200px;"></div>
                                        <textarea name="description" id="description" class="d-none"></textarea>
                                    </div>

                                    <div class="form-group">
                                        <label>Category</label>
                                        <select name="category" class="form-control">
                                            <option value="">-- Select Category --</option>
                                            @foreach($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label>Duration</label>
                                        <select name="duration" class="form-control">
                                            <option value="">-- Select Duration --</option>
                                            @for ($minutes = 30; $minutes <= 1440; $minutes +=30)
                                                @php
                                                $hrs=floor($minutes / 60);
                                                $mins=$minutes % 60;

                                                $label='' ;
                                                if ($hrs> 0) {
                                                $label .= $hrs . ' hour' . ($hrs > 1 ? 's' : '');
                                                }
                                                if ($hrs > 0 && $mins > 0) {
                                                $label .= ' ';
                                                }
                                                if ($mins > 0) {
                                                $label .= $mins . ' minutes';
                                                }
                                                @endphp
                                                <option value="{{ $minutes }}">{{ $label }}</option>
                                                @endfor
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label>Thumbnail</label>
                                        <input type="file" name="thumbnail" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label>Staff Member</label>
                                        <select name="staff_member[]" class="form-control select2" multiple require>
                                            @foreach($staffUsers as $user)
                                            <option value="{{ $user->id }}">
                                                {{ $user->name }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label>Status</label>
                                        <select name="status" class="form-control" required>
                                            @foreach($defaultStatus as $label => $value)
                                            <option value="{{ $value }}" {{ old('status', $defaultStatus) == $value ? 'selected' : '' }}>
                                                {{ ucfirst($label) }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>

                                </div>

                                <div class="tab-pane" id="pricing" role="tabpanel">
                                    <div class="form-group">
                                        <label>Currency</label>
                                        <select name="currency" class="form-control">
                                            @foreach($currencies as $code => $currency)
                                            <option value="{{ $code }}">{{ $code }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Price</label>
                                        <input
                                            type="text"
                                            name="price"
                                            class="form-control"
                                            value="{{ old('price', $service->price ?? '') }}"
                                            inputmode="decimal"
                                            pattern="^\d*\.?\d{0,3}$"
                                            oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/^(\d+(\.\d{0,3})?).*$/, '$1');"
                                            placeholder="e.g., 100 or 100.50">
                                    </div>

                                </div>

                                <div class="tab-pane" id="gallery" role="tabpanel">
                                    <div class="form-group">
                                        <label class="form-label">Gallery</label>

                                        {{-- Add Image Tile --}}
                                        <div class="col-md-3 mb-3">
                                            <label for="galleryInput"
                                                class="w-100 h-100 d-flex justify-content-center align-items-center border border-primary border-dashed rounded bg-light"
                                                style="min-height: 150px; cursor: pointer;">
                                                <div class="text-center text-primary">
                                                    <div style="font-size: 2rem;">+</div>
                                                    <div>Add Image</div>
                                                </div>
                                            </label>
                                            <input type="file" name="gallery[]" id="galleryInput" class="d-none gallery-input" multiple accept="image/*">
                                        </div>

                                        {{-- Preview Existing + New --}}
                                        <div class="row mb-3" id="galleryPreviewContainer">
                                            @if(isset($service) && $service->gallery)
                                            @foreach(json_decode($service->gallery) as $image)
                                            <div class="col-md-3 mb-3 position-relative existing-image" data-image="{{ $image }}">
                                                <div class="card shadow-sm">
                                                    <img src="{{ asset('storage/' . $image) }}" class="card-img-top img-thumbnail" alt="Gallery Image">
                                                    <input type="hidden" name="existing_gallery[]" value="{{ $image }}">
                                                    <button type="button" class="btn btn-sm btn-dark text-white position-absolute top-0 end-0 m-1 rounded-pill delete-existing-image" title="Delete image">&times;</button>
                                                </div>
                                            </div>
                                            @endforeach
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="settings" role="tabpanel">
                                    <div class="form-group">
                                        <label>Default Appointment Status</label>
                                        <select name="appointment_status" class="form-control">
                                            @foreach($appointmentStats as $label => $value)
                                            <option value="{{ $value }}" {{ old('appointment_status') == $value ? 'selected' : '' }}>
                                                {{ ucfirst($label) }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label>Minimum Time Required Before Canceling</label>
                                        <div class="d-flex">
                                            <select name="cancelling_unit" class="form-control mr-2" id="cancelling_unit">
                                                <option value="hours">Hours</option>
                                                <option value="days">Days</option>
                                            </select>
                                            <select name="cancelling_value" class="form-control" id="cancelling_value">
                                                <!-- Populated dynamically with JS -->
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label>Redirect URL After Booking</label>
                                        <input type="url" name="redirect_url" class="form-control" placeholder="https://example.com" pattern="https?://.*" title="Please enter a valid URL starting with http:// or https://">
                                    </div>


                                    <div class="form-group">
                                        <label>Payment Gateway</label>
                                        <select name="payment_mode" class="form-control" id="payment_mode">
                                            <option value="on_site">On Site</option>
                                            <option value="stripe">Stripe</option>
                                        </select>
                                    </div>

                                    {{-- Stripe Options --}}
                                    <div class="stripe-options d-none">
                                        <div class="form-check">
                                            <label class="form-check-label">
                                                <input class="form-check-input" type="radio" name="payment_account" value="default" checked>
                                                Use Default Stripe Account
                                            </label>
                                        </div>

                                        <div class="form-check">
                                            <label class="form-check-label">
                                                <input class="form-check-input" type="radio" name="payment_account" value="custom">
                                                Use Different Stripe Account
                                            </label>
                                        </div>


                                        <div class="stripe-credentials mt-3 d-none">
                                            <div class="form-group">
                                                <label>Stripe Mode</label><br>
                                                <input type="checkbox" id="payment__is_live" name="payment__is_live" value="1">
                                                <label for="payment__is_live">Live Mode</label>
                                            </div>

                                            <div class="stripe-test">
                                                <div class="form-group">
                                                    <label>Test Site Key</label>
                                                    <input type="text" name="stripe_test_site_key" class="form-control">
                                                </div>
                                                <div class="form-group">
                                                    <label>Test Secret Key</label>
                                                    <input type="text" name="stripe_test_secret_key" class="form-control">
                                                </div>
                                            </div>

                                            <div class="stripe-live d-none">
                                                <div class="form-group">
                                                    <label>Live Site Key</label>
                                                    <input type="text" name="stripe_live_site_key" class="form-control">
                                                </div>
                                                <div class="form-group">
                                                    <label>Live Secret Key</label>
                                                    <input type="text" name="stripe_live_secret_key" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4">
                                <button class="btn btn-primary">Save</button>
                                <a href="{{ route('service.list') }}" class="btn btn-secondary">Back</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('styles')
<style>
    .gallery-preview img {
        object-fit: cover;
    }
</style>
@endpush