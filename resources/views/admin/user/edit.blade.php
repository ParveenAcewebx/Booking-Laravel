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
                            <h5 class="m-b-10">User Edit</h5>
                        </div>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="feather icon-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="#!">User</a></li>
                            <li class="breadcrumb-item"><a href="#!">User Edit</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- [ breadcrumb ] end -->

        <!-- [ Main Content ] start -->
        <form action="{{ route('user.update', $user->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <!-- User Info Column -->
                <div class="col-md-8 order-md-1">
                    <div class="card">
                        <div class="card-header">
                            <h5>User Information</h5>
                        </div>

                        <div class="card-body">
                            <div class="row">
                                <!-- Name -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('username') is-invalid @enderror" name="username" value="{{ old('username', $user->name) }}" placeholder="Name">
                                        @error('username')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Email -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Email <span class="text-danger">*</span></label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email', $user->email) }}" placeholder="Email" readonly>
                                        @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Password -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Password <span class="text-danger">*</span></label>
                                        <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="Password">
                                        @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Confirm Password -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Confirm Password <span class="text-danger">*</span></label>
                                        <input type="password" class="form-control" name="password_confirmation" placeholder="Confirm Password">
                                    </div>
                                </div>

                                <!-- Phone Number -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Phone Number <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <select class="form-control @error('code') is-invalid @enderror" name="code" style="max-width: 100px;">
                                                @foreach($phoneCountries as $country)
                                                <option value="{{ $country['code'] }}"
                                                    {{ old('code', $user->phone_code ?? '+91') == $country['code'] ? 'selected' : '' }}>
                                                    {{ $country['code'] }}
                                                </option>
                                                @endforeach
                                            </select>
                                            <input type="text" class="form-control @error('phone_number') is-invalid @enderror" name="phone_number" placeholder="Enter phone number" value="{{ old('phone_number', $user->phone_number ?? null) }}">
                                        </div>
                                        @error('code')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        @error('phone_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Role -->
                                @php
                                $hideFields = Str::contains(request()->path(), 'profile') && Auth::check() && Auth::id() == $user->id;
                                @endphp
                                <div class="col-md-6" @if($hideFields) style="display:none;" @endif>
                                    <div class="form-group">
                                        <label class="form-label">Role</label>

                                        {{-- Select dropdown (disabled if primary_staff == 1) --}}
                                        <select class="form-control select-user @error('role') is-invalid @enderror"
                                            name="{{ optional($primaryStaff)->primary_staff == 1 ? '' : 'role' }}"
                                            @if(optional($primaryStaff)->primary_staff == 1) disabled @endif>
                                            @foreach($allRoles as $role)
                                            <option value="{{ $role->id }}" {{ old('role', $currentRole) == $role->id ? 'selected' : '' }}>
                                                {{ $role->name }}
                                            </option>
                                            @endforeach
                                        </select>

                                        {{-- Hidden input ONLY if disabled --}}
                                        @if(optional($primaryStaff)->primary_staff == 1)
                                        <input type="hidden" name="role" value="{{ old('role', $currentRole) }}">
                                        @endif

                                        @error('role')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Avatar + Settings Column -->
                <div class="col-md-4 order-md-2">
                    <div class="card">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <h5 class="mb-0">Settings</h5>
                        </div>
                        <div class="card-body">
                            <!-- Status -->
                            <div class="form-group mb-3" @if($hideFields) style="display:none;" @endif>
                                <label class="form-label">Status</label>
                                <select name="status" class="form-control select-user @error('status') is-invalid @enderror">
                                    <option value="{{ config('constants.status.active') }}" {{ old('status', $user->status) == config('constants.status.active') ? 'selected' : '' }}>Active</option>
                                    <option value="{{ config('constants.status.inactive') }}" {{ old('status', $user->status) == config('constants.status.inactive') ? 'selected' : '' }}>Inactive</option>
                                </select>
                                @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Avatar Upload -->
                            <div class="form-group">
                                <label class="form-label">Featured Image</label>
                                <div class="input-group mb-1">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Upload</span>
                                    </div>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input @error('avatar') is-invalid @enderror" name="avatar" id="avatarInput" accept=".jpg,.jpeg,.png,.gif,image/jpeg,image/png,image/gif">
                                        <label class="custom-file-label overflow-hidden" for="avatarInput">Choose file...</label>
                                    </div>
                                </div>
                                <small class="form-text text-muted">Supported image types: JPG, JPEG, PNG, or GIF.</small>
                                @error('avatar')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                {{-- Avatar Preview --}}
                                <div id="avatar-preview-container" class="row mt-3 {{ !empty($user->avatar) ? '' : 'd-none' }}">
                                    <div class="col-md-6 position-relative">
                                        <div class="card shadow-sm">
                                            <img id="avatar-preview"
                                                src="{{ !empty($user->avatar) ? asset('storage/' . $user->avatar) : asset('assets/images/no-image-available.png') }}"
                                                class="card-img-top img-thumbnail"
                                                alt="Avatar Preview"
                                                style="object-fit: cover; height: 120px; width: 100%;">
                                            <button type="button"
                                                id="remove-avatar-preview"
                                                class="btn btn-sm btn-dark text-white position-absolute top-0 end-0 m-1 rounded-pill delete-existing-image"
                                                title="Remove avatar">
                                                &times;
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <input type="hidden" name="remove_avatar" id="removeAvatarFlag" value="0">
                            </div>

                            <!-- Submit Button -->
                            <div class="text-right mt-0">
                                <button type="submit" class="btn btn-primary">Update</button>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </form>
        <!-- [ Main Content ] end -->
    </div>
</section>

<script type="text/javascript">
    document.addEventListener("DOMContentLoaded", function() {
        toastr.options = {
            closeButton: true,
            progressBar: true,
            timeOut: "4000",
            positionClass: "toast-top-right"
        };

        @if(session('success')) toastr.success("{{ session('success') }}");
        @endif
        @if(session('error')) toastr.error("{{ session('error') }}");
        @endif
        @if(session('info')) toastr.info("{{ session('info') }}");
        @endif
        @if(session('warning')) toastr.warning("{{ session('warning') }}");
        @endif
    });
</script>

@endsection