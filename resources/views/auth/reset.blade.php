@extends('auth.layouts.public')

@section('content')
<!-- [ auth-signin ] start -->
<div class="auth-wrapper">
    <div class="auth-content text-center">
        <img src="assets/images/logo.png" alt="" class="img-fluid mb-4">
        <div class="card borderless">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <div class="card-body">
                        <h4 class="mb-3 f-w-400">Reset Password</h4>
                        <hr>
                        <form method="POST" action="{{ route('password.update') }}">
                            @csrf
                            <input type="hidden" name="token" value="{{ $token }}">
                            
                            <!-- Password Field -->
                            <div class="form-group mb-3">
                                <input type="password" class="form-control {{ $errors->has('password') ? 'border border-danger' : '' }}" id="password" name="password" placeholder="Password" oninput="removeError('password')">
                                @error('password')
                                    <div class="error-message text-danger text-left" id="password-error">{{ $message }}</div>
                                @enderror
                            </div>  

                            <!-- Confirm Password Field -->
                            <div class="form-group mb-3">
                                <input type="password" class="form-control {{ $errors->has('password_confirmation') ? 'border border-danger' : '' }}" id="password_confirmation" name="password_confirmation" placeholder="Confirm password"  oninput="removeError('password_confirmation')"> 
                                @error('password_confirmation')
                                    <div class="error-message text-danger text-left" id="password_confirmation-error">{{ $message }}</div>
                                @enderror
                            </div>   

                            @if (session('status'))
                               <div>{{ session('status') }}</div>
                            @endif

                            <button type="submit" class="btn btn-block btn-primary mb-4">Update Password</button>
                        </form>
                        <hr>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
