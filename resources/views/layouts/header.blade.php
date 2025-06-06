<header class="navbar pcoded-header navbar-expand-lg navbar-light headerpos-fixed">


	<div class="m-header">
		<a class="mobile-menu" id="mobile-collapse" href="#!"><span></span></a>
		<a href="#!" class="b-brand">
			<!-- ========   change your logo hear   ============ -->
			<!-- ========   change your logo hear   ============ -->
			<img src="assets/images/logo.png" alt="" class="logo">	
		</a>
		<a href="#!" class="mob-toggler">
			<i class="feather icon-more-vertical"></i>
		</a>
	</div>
	<div class="collapse navbar-collapse">
		<ul class="navbar-nav mr-auto">
			<li class="nav-item">
				<a href="#!" class="pop-search"><i class="feather icon-search"></i></a>
				<div class="search-bar">
					<input type="text" class="form-control border-0 shadow-none" placeholder="search here">
					<button type="button" class="close" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
			</li>
			<li class="nav-item">
				<div class="dropdown">
					<a class="dropdown-toggle h-drop" href="#" data-toggle="dropdown">
						Dropdown
					</a>
					<div class="dropdown-menu profile-notification">
						<ul class="pro-body">
							<li><a href="{{ route('user.edit', Auth::id()) }}" class="dropdown-item"><i class="fas fa-circle"></i> Profile</a></li>
							<li><a href="email_inbox.html" class="dropdown-item"><i class="fas fa-circle"></i> My Messages</a></li>
							<li><a href="auth-signin.html" class="dropdown-item"><i class="fas fa-circle"></i> Lock Screen</a></li>
						</ul>
					</div>
				</div>
			</li>
			<li class="nav-item">
				<div class="dropdown mega-menu">
					<a class="dropdown-toggle h-drop" href="#" data-toggle="dropdown">
						Mega
					</a>
					<div class="dropdown-menu profile-notification ">
						<div class="row no-gutters">
							<div class="col">
								<h6 class="mega-title">UI Element</h6>
								<ul class="pro-body">
									<li><a href="#!" class="dropdown-item"><i class="fas fa-circle"></i> Alert</a></li>
									<li><a href="#!" class="dropdown-item"><i class="fas fa-circle"></i> Button</a></li>
									<li><a href="#!" class="dropdown-item"><i class="fas fa-circle"></i> Badges</a></li>
									<li><a href="#!" class="dropdown-item"><i class="fas fa-circle"></i> Cards</a></li>
									<li><a href="#!" class="dropdown-item"><i class="fas fa-circle"></i> Modal</a></li>
									<li><a href="#!" class="dropdown-item"><i class="fas fa-circle"></i> Tabs & pills</a></li>
								</ul>
							</div>
							<div class="col">
								<h6 class="mega-title">Forms</h6>
								<ul class="pro-body">
									<li><a href="#!" class="dropdown-item"><i class="feather icon-minus"></i> Elements</a></li>
									<li><a href="#!" class="dropdown-item"><i class="feather icon-minus"></i> Validation</a></li>
									<li><a href="#!" class="dropdown-item"><i class="feather icon-minus"></i> Masking</a></li>
									<li><a href="#!" class="dropdown-item"><i class="feather icon-minus"></i> Wizard</a></li>
									<li><a href="#!" class="dropdown-item"><i class="feather icon-minus"></i> Picker</a></li>
									<li><a href="#!" class="dropdown-item"><i class="feather icon-minus"></i> Select</a></li>
								</ul>
							</div>
							<div class="col">
								<h6 class="mega-title">Application</h6>
								<ul class="pro-body">
									<li><a href="#!" class="dropdown-item"><i class="feather icon-mail"></i> Email</a></li>
									<li><a href="#!" class="dropdown-item"><i class="feather icon-clipboard"></i> Task</a></li>
									<li><a href="#!" class="dropdown-item"><i class="feather icon-check-square"></i> To-Do</a></li>
									<li><a href="#!" class="dropdown-item"><i class="feather icon-image"></i> Gallery</a></li>
									<li><a href="#!" class="dropdown-item"><i class="feather icon-help-circle"></i> Helpdesk</a></li>
								</ul>
							</div>
							<div class="col">
								<h6 class="mega-title">Extension</h6>
								<ul class="pro-body">
									<li><a href="#!" class="dropdown-item"><i class="feather icon-file-plus"></i> Editor</a></li>
									<li><a href="#!" class="dropdown-item"><i class="feather icon-file-minus"></i> Invoice</a></li>
									<li><a href="#!" class="dropdown-item"><i class="feather icon-calendar"></i> Full calendar</a></li>
									<li><a href="#!" class="dropdown-item"><i class="feather icon-upload-cloud"></i> File upload</a></li>
									<li><a href="#!" class="dropdown-item"><i class="feather icon-scissors"></i> Image cropper</a></li>
								</ul>
							</div>
						</div>
					</div>
				</div>
			</li>
		</ul>
		<ul class="navbar-nav ml-auto">
			@foreach($allusers as $user)
			<li>
				<div class="dropdown">
					@php
					$isImpersonating = session()->has('impersonate_original_user') || Cookie::get('impersonate_original_user');
					$currentUser = Auth::user();
					@endphp

					@if($isImpersonating && $currentUser->id === $user->id)
					<!-- Show Switch Back only for the impersonated user -->
					<form method="POST" action="{{ route('user.switch.back') }}">
						@csrf
						<button type="submit" class="btn btn-sm btn-warning" data-bs-toggle="tooltip" title="Switch Back to {{ $loginUser->name }}">
							<i class="feather icon-log-out"></i>
						</button>
					</form>
					@endif
				</div>
			</li>
			@endforeach

			<li>
				<div class="dropdown drp-user">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown">
						<i class="feather icon-user"></i>
					</a>
					<div class="dropdown-menu dropdown-menu-right profile-notification">
						<div class="pro-head">
							<img src="{{ Auth::user()->avatar ? Storage::url(Auth::user()->avatar) : asset('assets/images/avatar-2.jpg') }}" class="img-radius" alt="User-Profile-Image">
							<span>{{ Auth::user()->name }}</span>
						</div>
						<ul class="pro-body">
							<li><a href="{{ route('profile') }}" class="dropdown-item"><i class="feather icon-user"></i> Profile</a></li>
							<li><a href="{{ route('logout') }}" class="dropdown-item"><i class="feather icon-power"></i> Logout</a></li>
						</ul>
					</div>
				</div>
			</li>
		</ul>
	</div>
</header>