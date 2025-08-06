@extends('admin.layouts.app')

@section('content')
<section class="pcoded-main-container">
    <div class="pcoded-content">
        <!-- [ breadcrumb ] start -->
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <div class="page-header-title">
                            <h5 class="m-b-10">Add Staff</h5>
                        </div>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="feather icon-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="{{ route('staff.list') }}">Staff</a></li>
                            <li class="breadcrumb-item">Add Staff</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- [ breadcrumb ] end -->
        <!-- [ Main Content ] start -->
        <form action="{{ route('staff.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <!-- Left Column -->
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h5>Staff Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <!-- Name -->
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}">
                                        @error('name')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Email -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Email <span class="text-danger">*</span></label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}">
                                        @error('email')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <!-- Phone -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Phone Number <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <select class="form-control @error('code') is-invalid @enderror" name="code" style="max-width: 100px;">
                                                @foreach($phoneCountries as $country)
                                                <option value="{{ $country['code'] }}" {{ old('code', '+91') == $country['code'] ? 'selected' : '' }}>
                                                    {{ $country['code'] }}
                                                </option>
                                                @endforeach
                                            </select>
                                            <input type="text" class="form-control @error('phone_number') is-invalid @enderror" name="phone_number" value="{{ old('phone_number') }}" placeholder="Enter phone number">
                                        </div>
                                        @error('phone_number')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Password -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Password <span class="text-danger">*</span></label>
                                        <input type="password" class="form-control @error('password') is-invalid @enderror" name="password">
                                        @error('password')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Confirm Password -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Confirm Password <span class="text-danger">*</span></label>
                                        <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" name="password_confirmation">
                                        @error('password_confirmation')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Role (Hidden) -->
                                @if($roles)
                                <div class="col-md-6 d-none">
                                    <div class="form-group">
                                        <label>Role:</label>
                                        <select class="form-control select-user" name="role">
                                            <option value="{{ $roles->id }}" selected>{{ $roles->name }}</option>
                                        </select>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <ul class="nav nav-tabs mb-3" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" data-toggle="tab" href="#working-days" role="tab">
                                        <i class="feather icon-clock"></i> Work Hours
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#days-off" role="tab">
                                        <i class="feather icon-calendar"></i> Days Off
                                    </a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                @include('admin.staff.partials.add.working-days')
                                @include('admin.staff.partials.add.days-off')
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h5>Settings</h5>
                        </div>
                        <div class="card-body">
                            <!-- Status -->
                            <div class="form-group">
                                <label>Status</label>
                                <select name="status" class="form-control select-user @error('status') is-invalid @enderror">
                                    <option value="{{ config('constants.status.active') }}" {{ old('status', 1) == config('constants.status.active') ? 'selected' : '' }}>Active</option>
                                    <option value="{{ config('constants.status.inactive') }}" {{ old('status', 1) == config('constants.status.inactive') ? 'selected' : '' }}>Inactive</option>
                                </select>
                                @error('status')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Assigned Services -->
                            <div class="form-group">
                                <label>Assigned Services</label>
                                <select class="form-control select-user" name="assigned_services[]" multiple>
                                    @foreach($services as $service)
                                    <option value="{{ $service->id }}" {{ collect(old('assigned_services'))->contains($service->id) ? 'selected' : '' }}>
                                        {{ $service->name }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('assigned_services')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Assigned Vendor -->
                            <div class="form-group">
                                <label>Assigned Vendor</label>
                                <select class="form-control select-user @error('assigned_vendor') is-invalid @enderror" name="assigned_vendor">
                                    <option value="" disabled {{ old('assigned_vendor') ? '' : 'selected' }}>Please Select Vendor</option>
                                    @foreach($vendorData as $vendor)
                                    <option value="{{ $vendor->id }}" {{ old('assigned_vendor') == $vendor->id ? 'selected' : '' }}>
                                        {{ $vendor->name }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('assigned_vendor')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Avatar Upload -->
                            <div class="form-group">
                                <label>Featured Image</label>
                                <div class="input-group mb-1">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Upload</span>
                                    </div>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input @error('avatar') is-invalid @enderror" name="avatar" id="addAvatarInput" accept=".jpg,.jpeg,.png,.gif">
                                        <label class="custom-file-label overflow-hidden" for="addAvatarInput">Choose file...</label>
                                    </div>
                                </div>
                                <small class="form-text text-muted">Supported types: JPG, JPEG, PNG, GIF.</small>
                                @error('avatar')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror

                                <!-- Image Preview -->
                                <div id="add-avatar-preview-container" class="row d-none mt-3">
                                    <div class="col-md-6 position-relative">
                                        <div class="card shadow-sm">
                                            <img id="add-avatar-preview" class="card-img-top img-thumbnail h-100" alt="Avatar Preview">
                                            <button type="button" id="remove-add-avatar-preview" class="btn btn-sm btn-dark position-absolute top-0 end-0 m-1 rounded-pill delete-existing-image" title="Remove image">
                                                &times;
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="text-right mt-0">
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <!-- [ Main Content ] end -->
    </div>
</section>
@endsection