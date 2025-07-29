@extends('admin.layouts.app')

@section('content')
<section class="pcoded-main-container">
    <div class="pcoded-content">

        <!-- Breadcrumb -->
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <div class="page-header-title">
                            <h5 class="m-b-10">Edit Staff</h5>
                        </div>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="feather icon-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="{{ route('staff.list') }}">Staff</a></li>
                            <li class="breadcrumb-item">Edit Staff</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- Form Start -->
        <form action="{{ route('staff.update', $staff->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row">
                <!-- Left Column -->
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header"><h5>Staff Information</h5></div>
                        <div class="card-body">
                            <div class="row">
                                <!-- Name -->
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Name:</label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                                            name="name" value="{{ old('name', $staff->name) }}" >
                                        @error('name')
                                            <div class="text-danger mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Email -->
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Email:</label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                                            name="email" value="{{ old('email', $staff->email) }}" >
                                        @error('email')
                                            <div class="text-danger mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Password -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Password:</label>
                                        <input type="password" class="form-control @error('password') is-invalid @enderror"
                                            name="password" id="password" placeholder="Enter Password">
                                        @error('password')
                                            <div class="text-danger mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Confirm Password -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Confirm Password:</label>
                                        <input type="password" class="form-control" name="password_confirmation" id="password_confirmation" placeholder="Confirm Password">
                                    </div>
                                </div>

                                <!-- Phone -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Phone Number</label>
                                        <div class="input-group">
                                            <select class="form-control" name="code" style="max-width: 100px;">
                                                @foreach($phoneCountries as $country)
                                                <option value="{{ $country['code'] }}"
                                                    {{ old('code', $staff->phone_code ?? '+91') == $country['code'] ? 'selected' : '' }}>
                                                    {{ $country['code'] }}
                                                </option>
                                                @endforeach
                                            </select>
                                            <input type="text" class="form-control" name="phone_number" placeholder="Phone Number"
                                                value="{{ old('phone_number', $staff->phone_number) }}" >
                                        </div>
                                        @error('phone_number')
                                            <div class="text-danger mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Role (hidden) -->
                                @if($roles)
                                <div class="col-md-6 d-none">
                                    <div class="form-group">
                                        <label>Role:</label>
                                        <select class="form-control" name="role" >
                                            <option value="{{ $roles->id }}" selected>{{ $roles->name }}</option>
                                        </select>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header"><h5>Settings</h5></div>
                        <div class="card-body">
                            <!-- Status -->
                            <div class="form-group">
                                <label>Status</label>
                                <select name="status" class="form-control">
                                    <option value="{{ config('constants.status.active') }}"
                                        {{ old('status', $staff->status) == config('constants.status.active') ? 'selected' : '' }}>
                                        Active
                                    </option>
                                    <option value="{{ config('constants.status.inactive') }}"
                                        {{ old('status', $staff->status) == config('constants.status.inactive') ? 'selected' : '' }}>
                                        Inactive
                                    </option>
                                </select>
                                @error('status')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Assigned Services -->
                            <div class="form-group">
                                <label>Assigned Services</label>
                                <select class="form-control select-user" name="assigned_services[]" multiple >
                                    @foreach($services as $service)
                                    <option value="{{ $service->id }}"
                                        {{ collect(old('assigned_services', $assignedServices->pluck('id')->toArray()))->contains($service->id) ? 'selected' : '' }}>
                                        {{ $service->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Assigned Vendor -->
                            <div class="form-group">
                                <label>Assigned Vendor</label>
                                <select class="form-control select-user" name="assigned_vendor" {{ $IsUserPrimaryStaff ? 'disabled' : '' }} >
                                    <option value="">Select Vendor</option>
                                    @foreach($vendorData as $vendor)
                                    <option value="{{ $vendor->id }}"
                                        {{ old('assigned_vendor', $staffMeta->vendor_id ?? '') == $vendor->id ? 'selected' : '' }}>
                                        {{ $vendor->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Avatar Upload -->
                            <div class="form-group">
                                <label>Featured Image</label>
                                <div class="input-group mb-1">
                                    <div class="input-group-prepend"><span class="input-group-text">Upload</span></div>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" name="avatar" id="avatarInput" accept=".jpg,.jpeg,.png,.gif">
                                        <label class="custom-file-label overflow-hidden" for="avatarInput">Choose file...</label>
                                    </div>
                                </div>
                                @error('avatar')
                                <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror

                                @if(!empty($staff->avatar))
                                <div class="mt-3">
                                    <img src="{{ asset('storage/' . $staff->avatar) }}" alt="Avatar" class="img-thumbnail" style="max-width: 120px;">
                                    <input type="hidden" name="remove_avatar" id="removeAvatarFlag" value="0">
                                </div>
                                @endif
                            </div>

                            <div class="text-right">
                                <button type="submit" class="btn btn-primary">Update</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabs -->
            <div class="row mt-4">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-body">
                            <ul class="nav nav-tabs mb-3">
                                <li class="nav-item">
                                    <a class="nav-link active" data-toggle="tab" href="#working-days">Work Hours</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#days-off">Days Off</a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                @include('admin.staff.partials.edit.working-days')
                                @include('admin.staff.partials.edit.days-off')
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </form>
    </div>
</section>
@endsection
