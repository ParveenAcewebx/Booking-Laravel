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
                            <h5 class="m-b-10">Change Password</h5>
                        </div>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="feather icon-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="#!">Password</a></li>
                            <li class="breadcrumb-item"><a href="#!">Change Password</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <form action="{{ route('changepassword.update') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-12 order-md-1">
                    <div class="card">
                        <div class="card-header">
                            <h5>Change Password</h5>
                        </div>

                        <div class="card-body">
                            <div class="row">

                                <!-- Old Password -->
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-label">Old Password <span class="text-danger">*</span></label>
                                        <input type="password" class="form-control @error('old_password') is-invalid @enderror" name="old_password" value = "{{ old('old_password') }}" placeholder="Old Password">
                                        @error('old_password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- New Password -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">New Password <span class="text-danger">*</span></label>
                                        <input type="password" class="form-control @error('new_password') is-invalid @enderror" name="new_password"  value = "{{ old('new_password') }}" placeholder="New Password">
                                        @error('new_password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Confirm Password -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Confirm Password <span class="text-danger">*</span></label>
                                        <input type="password" class="form-control @error('new_password_confirmation') is-invalid @enderror" name="new_password_confirmation" value = "{{ old('new_password_confirmation') }}" placeholder="Confirm Password">
                                        @error('new_password_confirmation')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                            </div>

                            <button type="submit" class="btn btn-primary mt-3">Update Password</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>

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

        @if(session('success'))
            toastr.success("{{ session('success') }}");
        @endif

        @if(session('error'))
            toastr.error("{{ session('error') }}");
        @endif
    });
</script>
@endsection
