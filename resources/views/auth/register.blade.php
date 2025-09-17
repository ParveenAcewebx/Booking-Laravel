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
                                    <div class="error-message text-danger" id="password_confirmation-error">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <button class="btn btn-primary btn-block mb-4" type="submit">Register</button>
                        </form>
                        <hr>
                        <p class="mb-2">Already have an account? <a href="{{route('login.form')}}" class="f-w-400">Signin</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

