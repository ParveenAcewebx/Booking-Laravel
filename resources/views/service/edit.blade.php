@extends('layouts.app')

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

                        <form method="POST" action="{{ route('service.update', $service->id) }}" enctype="multipart/form-data">
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
                                        <textarea name="description" id="description" class="d-none">{!! $service->description !!}</textarea>
                                    </div>
                                    <div class="form-group">
                                        <label>Category</label>
                                        <select name="category" class="form-control">
                                            <option value="">-- Select Category --</option>
                                            @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ $service->category == $category->id ? 'selected' : '' }}>{{ $category->category_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                

                                    <div class="form-group">
                                        <label>Thumbnail</label>
                                        <input type="file" name="thumbnail" class="form-control">
                                        @if($service->thumbnail)
                                        <img src="{{ asset('storage/' . $service->thumbnail) }}" alt="Thumbnail" height="80" class="mt-2">
                                        @endif
                                    </div>
                                    <div class="form-group">
                                        <label>Staff Member</label>
                                        <select name="staff_member[]" class="form-control select2" multiple>
                                            @foreach($staffUsers as $user)
                                            <option value="{{ $user->id }}" {{ in_array($user->id, json_decode($service->staff_member, true) ?? []) ? 'selected' : '' }}>{{ $user->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Status</label>
                                        <select name="status" class="form-control" required>
                                            @foreach($statuses as $label => $value)
                                            <option value="{{ $value }}" {{ $service->status == $value ? 'selected' : '' }}>{{ ucfirst($label) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="tab-pane" id="pricing" role="tabpanel">
                                    <div class="form-group">
                                        <label>Currency</label>
                                        <select name="currency" class="form-control">
                                            @foreach($currencies as $code => $currency)
                                            <option value="{{ $code }}" {{ $service->currency == $code ? 'selected' : '' }}>{{ $code }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Price</label>
                                        <input type="number" name="price" class="form-control" value="{{ $service->price }}" min="0" step="1">
                                    </div>
                                </div>

                                <div class="tab-pane" id="gallery" role="tabpanel">
                                    <div class="form-group">
                                        <label>Gallery</label>
                                        <input type="file" name="gallery[]" class="form-control" multiple>
                                        <div class="gallery-preview mt-3">
                                            @foreach(json_decode($service->gallery, true) ?? [] as $img)
                                            <img src="{{ asset('storage/' . $img) }}" height="80" class="mr-2">
                                            @endforeach
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-pane" id="settings" role="tabpanel">
                                    <div class="form-group">
                                        <label>Default Appointment Status</label>
                                        <select name="appointment_status" class="form-control">
                                            @foreach($appointmentStats as $label => $value)
                                            <option value="{{ $value }}" {{ $service->appointment_status == $value ? 'selected' : '' }}>{{ ucfirst($label) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Cancel Unit</label>
                                        <select name="cancelling_unit" class="form-control">
                                            <option value="hours" {{ $service->cancelling_unit == 'hours' ? 'selected' : '' }}>Hours</option>
                                            <option value="days" {{ $service->cancelling_unit == 'days' ? 'selected' : '' }}>Days</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Cancel Value</label>
                                        <input type="number" name="cancelling_value" class="form-control" value="{{ $service->cancelling_value }}">
                                    </div>
                                    <div class="form-group">
                                        <label>Redirect URL</label>
                                        <input type="text" name="redirect_url" class="form-control" value="{{ $service->redirect_url }}">
                                    </div>
                                    <div class="form-group">
                                        <label>Payment Mode</label>
                                        <select name="payment_mode" class="form-control" id="payment_mode">
                                            <option value="on_site" {{ $service->payment_mode == 'on_site' ? 'selected' : '' }}>On Site</option>
                                            <option value="stripe" {{ $service->payment_mode == 'stripe' ? 'selected' : '' }}>Stripe</option>
                                        </select>
                                    </div>

                                    {{-- Stripe Options --}}
                                    <div class="stripe-options {{ $service->payment_mode == 'stripe' ? '' : 'd-none' }}">
                                        <div class="form-check">
                                            <label class="form-check-label">
                                                <input class="form-check-input" type="radio" name="payment_account" value="default" {{ $service->payment_account == 'default' ? 'checked' : '' }}> Use Default Stripe Account
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <label class="form-check-label">
                                                <input class="form-check-input" type="radio" name="payment_account" value="custom" {{ $service->payment_account == 'custom' ? 'checked' : '' }}> Use Different Stripe Account
                                            </label>
                                        </div>

                                        <div class="stripe-credentials mt-3 {{ $service->payment_account == 'custom' ? '' : 'd-none' }}">
                                            <div class="form-group">
                                                <label>Stripe Mode</label><br>
                                                <input type="checkbox" id="payment__is_live" name="payment__is_live" value="1" {{ $service->payment__is_live ? 'checked' : '' }}> <label for="payment__is_live">Live Mode</label>
                                            </div>

                                            <div class="stripe-test {{ $service->payment__is_live ? 'd-none' : '' }}">
                                                <div class="form-group">
                                                    <label>Test Site Key</label>
                                                    <input type="text" name="stripe_test_site_key" class="form-control" value="{{ $service->stripe_test_site_key }}">
                                                </div>
                                                <div class="form-group">
                                                    <label>Test Secret Key</label>
                                                    <input type="text" name="stripe_test_secret_key" class="form-control" value="{{ $service->stripe_test_secret_key }}">
                                                </div>
                                            </div>

                                            <div class="stripe-live {{ $service->payment__is_live ? '' : 'd-none' }}">
                                                <div class="form-group">
                                                    <label>Live Site Key</label>
                                                    <input type="text" name="stripe_live_site_key" class="form-control" value="{{ $service->stripe_live_site_key }}">
                                                </div>
                                                <div class="form-group">
                                                    <label>Live Secret Key</label>
                                                    <input type="text" name="stripe_live_secret_key" class="form-control" value="{{ $service->stripe_live_secret_key }}">
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
@endsection

@push('styles')
<style>
    .gallery-preview img {
        object-fit: cover;
    }
</style>
@endpush