

@extends('layouts.public')

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
						<div class="form-group mb-3">
                        <input  class="form-control" type="text" name="name" placeholder="Name">
						</div>
						<div class="form-group mb-3">
                        <input  class="form-control" type="email" name="email" placeholder="Email">
						</div>
						<div class="form-group mb-4">
                        <input  class="form-control" type="password" name="password" placeholder="Password">
						</div>
						<button class="btn btn-primary btn-block mb-4" type="submit">Register</button>
						</form>
						<hr>
						<p class="mb-2">Already have an account? <a href="auth-signin.html" class="f-w-400">Signin</a></p>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

@endsection