@extends('auth.layouts.public')

@section('content')

<div class="auth-wrapper">
    <div class="auth-content text-center">
        <img src="assets/images/logo.png" alt="" class="img-fluid mb-4">
        <div class="card borderless">
            <div class="row align-items-center text-center">
                <div class="col-md-12">
                    <div class="card-body">
                        <h4 class="f-w-400">Sign up</h4>
                        <hr>
                        <form method="POST" action="{{ route('register') }}">
                            @csrf
                            <!-- Name Input -->
                            <div class="form-group mb-3">
                                <input 
                                    class="form-control {{ $errors->has('name') ? 'border border-danger' : '' }}" 
                                    type="text" 
                                    name="name" 
                                    placeholder="Name" 
                                    value="{{ old('name') }}" 
                                    id="name"
                                    oninput="removeError('name')">
                                @error('name')
                                    <div class="error-message text-danger text-left" id="name-error">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <!-- Email Input -->
                            <div class="form-group mb-3">
                                <input 
                                    class="form-control {{ $errors->has('email') ? 'border border-danger' : '' }}" 
                                    type="email" 
                                    name="email" 
                                    placeholder="Email" 
                                    value="{{ old('email') }}" 
                                    id="email"
                                    oninput="removeError('email')">
                                @error('email')
                                    <div class="error-message text-danger text-left" id="email-error">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <!-- Password Input -->
                            <div class="form-group mb-4">
                                <input 
                                    class="form-control {{ $errors->has('password') ? 'border border-danger' : '' }}" 
                                    type="password" 
                                    name="password" 
                                    placeholder="Password" 
                                    value="{{ old('password') }}" 
                                    id="password"
                                    oninput="removeError('password')">
                                @error('password')
                                    <div class="error-message text-danger text-left" id="password-error">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <!-- Confirm Password Input -->
                            <div class="form-group mb-4">
                                <input 
                                    class="form-control {{ $errors->has('password_confirmation') ? 'border border-danger' : '' }}" 
                                    type="password" 
                                    name="password_confirmation" 
                                    placeholder="Confirm Password" 
                                    value="{{ old('password_confirmation') }}" 
                                    id="password_confirmation"
                                    oninput="removeError('password_confirmation')">
                                @error('password_confirmation')
                                    <div class="error-message text-danger text-left" id="password_confirmation-error">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group mb-4">
                                <div class="g-recaptcha" data-sitekey="{{ get_setting('recaptcha_site_key') }}"></div>
                                @error('g-recaptcha-response') 
                                    <div class="error-message text-danger text-left">{{ $message }}</div>
                                @enderror
                            </div>
                            <button class="btn btn-primary btn-block mb-4" type="submit">Register</button>
                        </form>
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
                        <hr>
                        <p class="mb-2">Already have an account? <a href="{{route('login.form')}}" class="f-w-400">Signin</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

