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
                        <h4 class="mb-3 f-w-400">Sign in</h4>
                        <hr>
                        @if (session('error'))
                         <div class="alert alert-danger">
                            {{ session('error') }}
                         </div>
                        @endif
                        @if (session('success'))
                         <div class="alert alert-success">
                            {{ session('success') }}
                         </div>
                        @endif
                        <form method="POST" action="{{ route('login') }}">
                            @csrf
                            <div class="form-group mb-3">
                                <input type="email" name="email" class="form-control {{ $errors->has('email') ? 'border border-danger' : '' }}" id="email" placeholder="Email address" oninput="removeError('email')" value="{{ old('email') }}">
                                @error('email')
                                  <div class="error-message text-danger text-left" id="email-error">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group mb-4">
                                <input type="password" name="password" class="form-control {{ $errors->has('password') ? 'border border-danger' : '' }}" id="password" placeholder="Password" oninput="removeError('password')" value="{{ old('password') }}">
                                @error('password')
                                  <div class="error-message text-danger text-left" id="password-error">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="g-recaptcha" data-sitekey="{{ get_setting('recaptcha_site_key') }}"></div>
                            @error('g-recaptcha-response') 
                                  <div class="error-message text-danger text-left">{{ $message }}</div>
                            @enderror
                            <div class="custom-control custom-checkbox text-left mb-4 mt-2">
                                <input type="checkbox" name="rememberme" class="custom-control-input" id="customCheck1">
                                <label class="custom-control-label" for="customCheck1">Save credentials.</label>
                            </div>
                            <button type="submit" class="btn btn-block btn-primary mb-4">Sign in</button>
                            <hr>
                                <div class="d-flex justify-content-between mt-3 mb-2" style="gap: 14px;">
                                @if(get_setting('google_login_enabled'))
                                    <a href="{{ route('auth.google.redirect') }}" class="btn border-danger d-flex align-items-center"
                                       style="height:48px;">
                                        <i class="fab fa-google mr-2" style="color:#DB4437; font-size:19px;"></i>
                                        <span class="text-dark">Login with Google</span>
                                    </a>
                                @endif
                                @if(get_setting('facebook_login_enabled'))
                                    <a href="{{ route('auth.facebook.redirect') }}" class="btn border-primary d-flex align-items-center"
                                       style="height:48px;">
                                        <i class="fab fa-facebook mr-2" style="color:#1877f3; font-size:19px;"></i>
                                        <span class="text-dark">Login with Facebook</span>
                                    </a>
                                @endif
                            </div>
                        </form>
                        <hr>
                        <p class="mb-2 text-muted">Forgot password? <a href="{{route('password.request')}}" class="f-w-400">Reset</a></p>
                        <p class="mb-2 text-muted">Don't have an account? <a href="{{route('register')}}" class="f-w-400">Signup</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
