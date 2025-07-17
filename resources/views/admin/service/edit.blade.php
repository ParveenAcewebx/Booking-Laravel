@extends('admin.layouts.app')

@section('content')
<div class="pcoded-main-container">
    <div class="pcoded-content">
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <div class="page-header-title">
                            <h5 class="m-b-10">Edit Service</h5>
                        </div>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="feather icon-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="{{ route('service.list') }}">Services</a></li>
                            <li class="breadcrumb-item">Edit Service</li>
                        </ul>
                    </div>
                </div>
            </div>
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
                        @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif
                        <form method="POST" action="{{ route('service.update', $service) }}" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="tab-content">
                                {{-- SERVICE DETAILS --}}
                                <div class="tab-pane active" id="service-details" role="tabpanel">
                                    <div class="form-group">
                                        <label>Name</label>
                                        <input type="text" name="name" class="form-control" value="{{ $service->name }}" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Description</label>
                                        <div id="quill-editor" style="height: 200px;"></div>
                                        <textarea name="description" id="description" class="d-none">{{ old('description', $service->description) }}</textarea>
                                    </div>

                                    <div class="form-group">
                                        <label>Category</label>
                                        <select name="category" class="form-control category">
                                            <option value="">-- Select Category --</option>
                                            @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ $service->category == $category->id ? 'selected' : '' }}>{{ $category->category_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label>Duration</label>
                                        <select name="duration" class="form-control durartion">
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
                                                <option value="{{ $minutes }}" {{ (old('duration', $service->duration ?? '') == $minutes) ? 'selected' : '' }}>
                                                    {{ $label }}
                                                </option>
                                                @endfor
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Staff Member</label>
                                        <select name="staff_member[]" class="form-control select2-mash" multiple required>
                                            @php
                                            $selectedStaff = old('staff_member', $associatedStaffIds ?? []);
                                            @endphp
                                            @foreach($staffUsers as $user)
                                            <option value="{{ $user->id }}" {{ in_array($user->id, $selectedStaff) ? 'selected' : '' }}>
                                                {{ $user->name }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>


                                    <div class="form-group">
                                        <label>Status</label>
                                        <select name="status" class="form-control select-user" required>
                                            @foreach($statuses as $label => $value)
                                            <option value="{{ $value }}" {{ $service->status == $value ? 'selected' : '' }}>{{ ucfirst($label) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="tab-pane" id="pricing" role="tabpanel">
                                    <div class="form-group">
                                        <label>Currency</label>
                                        <select name="currency" class="form-control select-user">
                                            @foreach($currencies as $code => $currency)
                                            <option value="{{ $code }}" {{ $service->currency == $code ? 'selected' : '' }}>{{ $code }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Price</label>
                                        <input
                                            type="text"
                                            name="price"
                                            class="form-control"
                                            value="{{$service->price}}"
                                            oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/^(\d+(\.\d{0,3})?).*$/, '$1');"
                                            placeholder="e.g., 100 or 100.50">
                                    </div>
                                </div>

                                <div class="tab-pane" id="gallery" role="tabpanel">

                                    <div class="form-group">
                                        <label for="thumbnail" class="form-label">Thumbnail</label>

                                        <div class="custom-file">
                                            <input type="file" name="thumbnail" class="custom-file-input" id="thumbnailInput"
                                                accept=".jpg,.jpeg,.png,.gif,image/jpeg,image/png,image/gif">
                                            <label class="custom-file-label" for="thumbnailInput">Choose file...</label>
                                            <div class="invalid-feedback">Please upload a valid thumbnail.</div>
                                        </div>

                                        {{-- Preview Card --}}
                                        <div id="edit-thumbnail-preview-container" class="row mt-3 {{ $service->thumbnail ? '' : 'd-none' }}">
                                            <div class="col-md-3 position-relative">
                                                <div class="card shadow-sm">
                                                    <img id="edit-thumbnail-preview"
                                                        src="{{ $service->thumbnail ? asset('storage/' . $service->thumbnail) : '' }}"
                                                        class="card-img-top img-thumbnail"
                                                        alt="Thumbnail Preview">

                                                    {{-- Remove Button Like Screenshot --}}
                                                    <button type="button"
                                                        id="remove-preview"
                                                        class="btn btn-sm btn-dark text-white position-absolute top-0 end-0 m-1 rounded-pill delete-existing-image"
                                                        title="Remove image">
                                                        &times;
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <input type="hidden" name="remove_thumbnail" id="removeThumbnailFlag" value="0">
                                    </div>

                                    <div class="form-group">
                                        <label class="form-label">Gallery</label>

                                        {{-- Add Image Tile --}}
                                        <div class="col-md-12 mb-3 pr-1 pl-0">
                                            <label for="galleryInput"
                                                class="w-100 h-100 d-flex justify-content-center align-items-center border border-primary border-dashed rounded bg-light"
                                                style="min-height: 150px; cursor: pointer;">
                                                <div class="text-center text-primary">
                                                    <div style="font-size: 2rem;">+</div>
                                                    <div>Add Image</div>
                                                    <small class="d-block text-muted mt-1">Accepted formats: JPG, JPEG, PNG, GIF</small>

                                                </div>
                                            </label>
                                            <input type="file" name="gallery[]" id="galleryInput" class="d-none gallery-input" multiple accept=".jpg,.jpeg,.png,.gif,image/jpeg,image/png,image/gif">
                                        </div>

                                        {{-- Gallery Preview Grid --}}
                                        <div class="row mb-3" id="galleryPreviewContainer">
                                            @foreach(json_decode($service->gallery, true) ?? [] as $img)
                                            <div class="col-md-3 mb-3 position-relative existing-image" data-image="{{ $img }}">
                                                <div class="card shadow-sm">
                                                    <img src="{{ asset('storage/' . $img) }}" class="card-img-top img-thumbnail" alt="Gallery Image">
                                                    <input type="hidden" name="existing_gallery[]" value="{{ $img }}">
                                                    <button type="button" class="btn btn-sm btn-dark text-white position-absolute top-0 end-0 m-1 rounded-pill delete-existing-image" title="Delete image">
                                                        &times;
                                                    </button>
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-pane" id="settings" role="tabpanel">
                                    <div class="form-group">
                                        <label>Default Appointment Status</label>
                                        <select name="appointment_status" class="form-control select-user">
                                            @foreach($appointmentStats as $label => $value)
                                            <option value="{{ $value }}" {{ $service->appointment_status == $value ? 'selected' : '' }}>{{ ucfirst($label) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Minimum Time Required Before Canceling</label>
                                        <div class="d-flex">
                                            <select name="cancelling_unit" class="form-control mr-2 select-user" id="cancelling_unit">
                                                <option value="hours" {{ $service->cancelling_unit == 'hours' ? 'selected' : '' }}>Hours</option>
                                                <option value="days" {{ $service->cancelling_unit == 'days' ? 'selected' : '' }}>Days</option>
                                            </select>

                                            <select name="cancelling_value" class="form-control select-user" id="cancelling_value">
                                                <!-- Options populated by JS -->
                                            </select>
                                            <input type="hidden" id="cancel_value" value="{{ $service->cancelling_value }}">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Redirect URL After Booking</label>
                                        <input type="url" name="redirect_url" class="form-control" value="{{ $service->redirect_url }}" placeholder="https://example.com" pattern="https?://.*" title="Please enter a valid URL starting with http:// or https://">
                                    </div>
                                    <div class="form-group">
                                        <label>Payment Mode</label>
                                        <select name="payment_mode" class="form-control select-user" id="payment_mode">
                                            <option value="on_site" {{ $service->payment_mode == 'on_site' ? 'selected' : '' }}>On Site</option>
                                            <option value="stripe" {{ $service->payment_mode == 'stripe' ? 'selected' : '' }}>Stripe</option>
                                        </select>
                                    </div>

                                    {{-- Stripe Options --}}
                                    <div class="stripe-options {{ $service->payment_mode == 'stripe' ? '' : 'd-none' }}">
                                        {{-- Stripe Account Type Radios --}}
                                        <div class="custom-control custom-radio">
                                            <input type="radio" id="stripeDefault" name="payment_account" value="default"
                                                class="custom-control-input"
                                                {{ $service->payment_account == 'default' ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="stripeDefault">Use Default Stripe Account</label>
                                        </div>

                                        <div class="custom-control custom-radio">
                                            <input type="radio" id="stripeCustom" name="payment_account" value="custom"
                                                class="custom-control-input"
                                                {{ $service->payment_account == 'custom' ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="stripeCustom">Use Different Stripe Account</label>
                                        </div>

                                        {{-- Stripe Credentials Section --}}
                                        <div class="stripe-credentials mt-3 {{ $service->payment_account == 'custom' ? '' : 'd-none' }}">
                                            {{-- Stripe Mode Toggle (Live/Test) --}}
                                            <div class="custom-control custom-checkbox mb-3">
                                                <input type="checkbox" class="custom-control-input" id="payment__is_live"
                                                    name="payment__is_live" value="1"
                                                    {{ $service->payment__is_live ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="payment__is_live">Live Mode</label>
                                            </div>

                                            {{-- Test Mode Keys --}}
                                            <div class="stripe-test {{ $service->payment__is_live ? 'd-none' : '' }}">
                                                <div class="form-group">
                                                    <label for="stripe_test_site_key">Test Site Key</label>
                                                    <input type="text" name="stripe_test_site_key" id="stripe_test_site_key"
                                                        class="form-control" value="{{ $service->stripe_test_site_key }}">
                                                </div>
                                                <div class="form-group">
                                                    <label for="stripe_test_secret_key">Test Secret Key</label>
                                                    <input type="text" name="stripe_test_secret_key" id="stripe_test_secret_key"
                                                        class="form-control" value="{{ $service->stripe_test_secret_key }}">
                                                </div>
                                            </div>

                                            {{-- Live Mode Keys --}}
                                            <div class="stripe-live {{ $service->payment__is_live ? '' : 'd-none' }}">
                                                <div class="form-group">
                                                    <label for="stripe_live_site_key">Live Site Key</label>
                                                    <input type="text" name="stripe_live_site_key" id="stripe_live_site_key"
                                                        class="form-control" value="{{ $service->stripe_live_site_key }}">
                                                </div>
                                                <div class="form-group">
                                                    <label for="stripe_live_secret_key">Live Secret Key</label>
                                                    <input type="text" name="stripe_live_secret_key" id="stripe_live_secret_key"
                                                        class="form-control" value="{{ $service->stripe_live_secret_key }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-4">
                                <button class="btn btn-primary">Update</button>
                                <a href="{{ route('service.list') }}" class="btn btn-secondary">Back</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<style>
    .gallery-preview img {
        object-fit: cover;
    }
</style>

<script>
    function populateCancellingValues(unit, selectedValue = null) {
        const valueSelect = document.getElementById("cancelling_value");
        valueSelect.innerHTML = "";

        let max = unit === "hours" ? 24 : 30;
        for (let i = 1; i <= max; i++) {
            const option = document.createElement("option");
            option.value = i;
            option.text = i;
            if (parseInt(selectedValue) === i) {
                option.selected = true;
            }
            valueSelect.appendChild(option);
        }
    }

    document.addEventListener("DOMContentLoaded", function() {
        const unitSelect = document.getElementById("cancelling_unit");
        const selectedValue = document.getElementById("cancel_value")?.value || null;

        populateCancellingValues(unitSelect.value, selectedValue);

        unitSelect.addEventListener("change", function() {
            populateCancellingValues(this.value);
        });
    });
</script>
@endsection