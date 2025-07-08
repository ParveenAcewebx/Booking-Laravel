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
                                <li class="breadcrumb-item">
                                    <a href="{{ route('dashboard') }}"><i class="feather icon-home"></i></a>
                                </li>
                                <li class="breadcrumb-item"><a href="/user">Users</a></li>
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
                    <!-- [ Form Validation ] start -->

                    <!-- Avatar Column -->
                    <div class="col-md-4 order-md-1">
                        <div class="card">
                            <div class="card-header d-flex align-items-center justify-content-between">
                                <h5 class="mb-0">Avatar</h5>
                            </div>
                            <div class="card-body">
                                <ul class="list-inline">
                                    <li class="list-inline-item">
                                        <img
                                            src="{{ !empty($user->avatar) ? asset('storage/' . $user->avatar) : asset('assets/images/no-image-available.png') }}"
                                            alt="user image"
                                            class="img-radius mb-2 wid-80 hei-80"
                                            data-toggle="tooltip"
                                            title="{{ old('username', $user->name) }}">
                                    </li>
                                </ul>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Upload</span>
                                    </div>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" name="avatar" id="inputGroupFile01">
                                        <label class="custom-file-label" for="inputGroupFile01">Choose file</label>
                                    </div>
                                    @error('avatar')
                                    <div class="error text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- User Info Column -->
                    <div class="col-md-8 order-md-2">
                        <div class="card">
                            <div class="card-header">
                                <h5>User Information</h5>

                                <!-- Flash Messages -->
                            </div>

                            <div class="card-body">
                                <div class="row">

                                    <!-- Name -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Name</label>
                                            <input type="text" class="form-control" name="username" value="{{ old('username', $user->name) }}" placeholder="Name">
                                            @error('username')
                                            <div class="error text-danger mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Email -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Email</label>
                                            <input type="email" class="form-control" name="email" value="{{ old('email', $user->email) }}" placeholder="Email" readonly>
                                            @error('email')
                                            <div class="error text-danger mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Password -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Password</label>
                                            <input type="password" class="form-control" name="password" placeholder="Password">
                                        </div>
                                    </div>

                                    <!-- Confirm Password -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Confirm Password</label>
                                            <input type="password" class="form-control" name="password_confirmation" placeholder="Confirm Password">
                                            @error('password')
                                            <div class="error text-danger mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <!-- Phone Number -->                                    
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Phone Number</label>
                                            <div class="input-group">
                                                <select class="form-control" id="country-select" name="code" style="max-width: 100px;">
                                                    @foreach($phoneCountries as $country)
                                                        <option value="{{ $country['code'] }}"
                                                            @if(
                                                                (!old('phone_number', $user->phone_number ?? null) && $country['code'] == '+91') ||
                                                                (old('phone_number', $user->phone_number ?? null) && Str::startsWith(old('phone_number', $user->phone_number ?? null), $country['code']))
                                                            ) selected @endif>
                                                            {{ $country['code'] }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <input type="text" class="form-control" id="phone_number" name="phone_number"
                                                    placeholder="Enter phone number"
                                                    value="{{ old('phone_number', $user->phone_number ?? null) }}" required>
                                            </div>
                                            @error('phone_number')
                                                <div class="error">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <!-- Role -->
                                    @php
                                    $hideFields = Str::contains(request()->path(), 'profile') && Auth::check() && Auth::id() == $user->id;
                                    @endphp

                                    <!-- Role -->
                                    <div class="col-md-6" @if($hideFields) style="display:none;" @endif>
                                        <div class="form-group">
                                            <label class="form-label">Role</label>
                                            <select class="form-control" name="role">
                                                @foreach($allRoles as $role)
                                                <option value="{{ $role->id }}" {{ $role->id == $currentRole ? 'selected' : '' }}>
                                                    {{ $role->name }}
                                                </option>
                                                @endforeach
                                            </select>
                                            @error('role')
                                            <div class="error text-danger mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Status Checkbox -->
                                    <div class="col-md-12" @if($hideFields) style="display:none;" @endif>
                                        <div class="form-group">
                                            <label class="form-label d-block">Status</label>

                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox"
                                                    class="custom-control-input"
                                                    name="status"
                                                    id="status"
                                                    value="{{ config('constants.status.active') }}"
                                                    {{ $user->status == config('constants.status.active') ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="status">Active</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Submit button -->
                                <button type="submit" class="btn btn-primary">Update</button>
                            </div>
                        </div>
                    </div>
                    <!-- [ Form Validation ] end -->
                </div>
            </form>
            <!-- [ Main Content ] end -->
        </div>
    </section>
    <script type="text/javascript">
        document.addEventListener("DOMContentLoaded", function() {
            toastr.options = {
                "closeButton": true,
                "progressBar": true,
                "timeOut": "4000",
                "positionClass": "toast-top-right"
            };

            @if(session('success'))
            toastr.success("{{ session('success') }}");
            @endif

            @if(session('error'))
            toastr.error("{{ session('error') }}");
            @endif

            @if(session('info'))
            toastr.info("{{ session('info') }}");
            @endif

            @if(session('warning'))
            toastr.warning("{{ session('warning') }}");
            @endif
        });
    </script>
    @endsection