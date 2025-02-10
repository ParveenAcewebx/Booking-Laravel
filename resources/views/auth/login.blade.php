@extends('layouts.public')

@section('content')
<!-- [ auth-signin ] start -->
<div class="auth-wrapper">
    <div class="auth-content text-center">
        <img src="assets/images/logo.png" alt="" class="img-fluid mb-4">
        <div class="card borderless">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <div class="card-body">
                        <h4 class="mb-3 f-w-400">Signin</h4>
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
                                <input type="email" name="email" class="form-control" id="Email" placeholder="Email address" required>
                                @error('email')
                                  <div class="error-message">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group mb-4">
                                <input type="password" name="password" class="form-control" id="Password" placeholder="Password" required>
                                @error('password')
                                  <div class="error-message">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="custom-control custom-checkbox text-left mb-4 mt-2">
                                <input type="checkbox" name="rememberme" class="custom-control-input" id="customCheck1">
                                <label class="custom-control-label" for="customCheck1">Save credentials.</label>
                               
                            </div>
                            <button type="submit" class="btn btn-block btn-primary mb-4">Signin</button>
                        </form>
                        <hr>
                        <p class="mb-2 text-muted">Forgot password? <a href="{{route('password.request')}}" class="f-w-400">Reset</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection




  
