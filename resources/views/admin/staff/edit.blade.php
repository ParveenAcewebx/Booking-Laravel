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
        <!-- [ breadcrumb ] end -->

        <!-- [ Error Messages ] -->
        @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <!-- [ Main Content ] start -->
        <form action="{{ route('staff.update', $staff->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
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
                                        <label>Name:</label>
                                        <input type="text" class="form-control" name="name" value="{{ old('name', $staff->name) }}" required>
                                    </div>
                                </div>

                                <!-- Description -->
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Email:</label>
                                        <input type="email" class="form-control" name="email" value="{{ old('email', $staff->email) }}" required>
                                    </div>
                                </div>

                                {{-- Password --}}
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Password:</label>
                                        <input type="password" class="form-control" name="password" id="password" placeholder="Enter Password">
                                    </div>
                                </div>

                                {{-- Confirm Password --}}
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Confirm Password:</label>
                                        <input type="password" class="form-control" name="password_confirmation" id="password_confirmation" placeholder="Confirm Password">
                                        <div id="password-error" class="text-danger mt-1 d-none">Passwords do not match.</div>
                                    </div>
                                </div>

                                {{-- Phone Number --}}
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Phone Number</label>
                                        <div class="input-group">
                                            <select class="form-control" name="code" style="max-width: 100px;">
                                                @foreach($phoneCountries as $country)
                                                <option value="{{ $country['code'] }}"
                                                    @if(
                                                    (!old('phone_code', $staff->phone_code ?? null) && $country['code'] == '+91') ||
                                                    (old('phone_code', $staff->phone_code ?? null) == $country['code'])
                                                    )
                                                    selected
                                                    @endif
                                                    >
                                                    {{ $country['code'] }}
                                                </option>
                                                @endforeach
                                            </select>

                                            <input type="text" class="form-control" name="phone_number"
                                                value="{{ old('phone_number', $staff->phone_number ?? '') }}" required>
                                        </div>
                                        @error('phone_number')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                @if($roles)
                                <div class="col-md-6 d-none">
                                    <div class="form-group">
                                        <label>Role:</label>
                                        <select class="form-control select-user" name="role" required>
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
                        <div class="card-header">
                            <h5>Settings</h5>
                        </div>
                        <div class="card-body">
                            <!-- Status -->
                            <div class="form-group">
                                <label for="status" class="form-label d-block">Status</label>
                                <select name="status" id="status" class="form-control select-user">
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
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Featured Image Upload -->
                            <div class="form-group">
                                <label>Featured Image</label>
                                <div class="input-group mb-1">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Upload</span>
                                    </div>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" name="avatar" id="addAvatarInput" accept=".jpg,.jpeg,.png,.gif">
                                        <label class="custom-file-label overflow-hidden" for="addAvatarInput">Choose file...</label>
                                    </div>
                                </div>
                                <small class="form-text text-muted">Supported types: JPG, JPEG, PNG, GIF.</small>
                                @error('avatar')
                                <div class="text-danger mt-1">{{ $message }}</div>
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
                                <button type="submit" class="btn btn-primary savebutton">Update</button>
                                <a href="{{ route('staff.list') }}" class="btn btn-secondary">Back</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabs Section -->
            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-body">
                            <ul class="nav nav-tabs mb-3" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" data-toggle="tab" href="#assigned-services" role="tab">
                                        <i class="feather icon-briefcase"></i> Assigned Services
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#working-days" role="tab">
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
                                {{-- TAB: ASSIGNED SERVICES --}}
                                @include('admin.staff.partials.edit.assigned-services')

                                {{-- TAB: WORKING DAYS --}}
                                @include('admin.staff.partials.edit.working-days')

                                {{-- TAB: DAYS OFF --}}
                                @include('admin.staff.partials.edit.days-off')

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