@extends('admin.layouts.public')

@section('content')
<!-- [ auth-signin ] start -->
<div class="auth-wrapper">
    <div class="auth-content text-center">
        <img src="assets/images/logo.png" alt="" class="img-fluid mb-4">
        <div class="card borderless">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <div class="card-body">
                        <h4 class="mb-3 f-w-400">Forget Password</h4>
                        <hr>
                        @if (session('error'))
                         <div class="alert alert-danger">
                            {{ session('error') }}
                         </div>
                        @endif
                        <form method="POST" action="{{ route('password.email') }}">
                            @csrf
                            <div class="form-group mb-3">
                                <input type="email" name="email" class="form-control" id="Email" placeholder="Email address" value="{{ old('email') }}" required>
                           </div>  
                            @if (session('status'))
                               <div>{{ session('status') }}</div>
                            @endif

                            @error('email')
                                <div>{{ $message }}</div>
                            @enderror
                            <button type="submit" class="btn btn-block btn-primary mb-4">Send mail</button>
                        </form>
                        <hr>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection




  
