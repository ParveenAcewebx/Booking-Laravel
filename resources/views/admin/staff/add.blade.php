@extends('admin.layouts.app')

@section('content')
<div class="pcoded-main-container">
    <div class="pcoded-content">
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
                    {{-- ✅ One single card-body --}}
                    <div class="card-body">
                        <ul class="nav nav-tabs mb-3" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab" href="#user-details" role="tab">
                                    <i class="feather icon-info"></i> User Details
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#assigned-services" role="tab">
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

                        <form method="POST" action="{{ route('staff.store') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="tab-content">

                                {{-- TAB: USER DETAILS --}}
                                <div class="tab-pane fade show active" id="user-details" role="tabpanel">
                                    <div class="row">
                                        {{-- Avatar --}}
                                        <div class="col-md-12 mb-3 text-center">
                                            <img src="{{ asset('assets/images/no-image-available.png') }}" class="img-radius mb-3" width="100" alt="User Avatar">
                                            <div class="custom-file mx-auto">
                                                <input type="file" class="custom-file-input" name="avatar" id="avatar">
                                                <label class="custom-file-label" for="avatar">Choose file...</label>
                                            </div>
                                        </div>

                                        {{-- Name --}}
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Name:</label>
                                                <input type="text" class="form-control" name="name" required>
                                            </div>
                                        </div>

                                        {{-- Email --}}
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Email:</label>
                                                <input type="email" class="form-control" name="email" required>
                                            </div>
                                        </div>

                                        {{-- Password --}}
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Password:</label>
                                                <input type="password" class="form-control" name="password" required>
                                            </div>
                                        </div>

                                        {{-- Confirm Password --}}
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Confirm Password:</label>
                                                <input type="password" class="form-control" name="password_confirmation" required>
                                                <div id="password-error"></div>
                                            </div>
                                        </div>

                                        {{-- Phone Number --}}
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">Phone Number</label>
                                                <div class="input-group">
                                                    <select class="form-control" name="code" style="max-width: 100px;">
                                                        @foreach($phoneCountries as $country)
                                                        <option value="{{ $country['code'] }}" {{ $country['code'] == '+91' ? 'selected' : '' }}>
                                                            {{ $country['code'] }}
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                    <input type="text" class="form-control" name="phone_number" placeholder="Enter phone number" required>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Role --}}
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Role:</label>
                                                <select class="form-control select2" name="role" required>
                                                    <option value="">Select Role</option>
                                                    @foreach($roles as $role)
                                                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        {{-- Status --}}
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="form-label d-block">Status</label>
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input" name="status" id="status" value="{{ config('constants.status.active') }}" checked>
                                                    <label class="custom-control-label" for="status">Active</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                  {{-- TAB: ASSIGNED SERVICES --}}
                                <div class="tab-pane fade" id="assigned-services" role="tabpanel">
                                    ASSIGNED SERVICES
                                </div>

                                {{-- TAB: WORK HOURS --}}
                                <div class="tab-pane fade" id="working-days" role="tabpanel">
                                    WORK HOURS
                                </div>

                                {{-- TAB: DAYS OFF --}}
                                <div class="tab-pane fade" id="days-off" role="tabpanel">
                                    DAYS OFF
                                </div>

                            </div>

                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary savebutton">Save</button>
                                <a href="{{ route('staff.list') }}" class="btn btn-secondary">Back</a>
                            </div>
                        </form>
                    </div> {{-- end of card-body --}}
                </div> {{-- end of card --}}
            </div>
        </div>
    </div>
</div>
@endsection