<nav class="pcoded-navbar menupos-fixed ">
		<div class="navbar-wrapper  ">
			<div class="navbar-content scroll-div " >
				
				<div class="">
					<div class="main-menu-header">
						<img class="img-radius" src="{{ asset('assets/images/avatar-2.jpg') }}" alt="User-Profile-Image">
						<div class="user-details">
							<div id="more-details"><span class="mb-0 font-weight-bold">{{ Auth::user()->name }}<i class="fa fa-chevron-down m-l-5"></i></span></div>
						</div>
					</div>
					<div class="collapse" id="nav-user-link">
						<ul class="list-inline">
							<li class="list-inline-item"><a href="{{ route('logout') }}" data-toggle="tooltip" title="Logout" class="text-danger"><i class="feather icon-power"></i></a></li>
						</ul>
					</div>
				</div>
				
				<ul class="nav pcoded-inner-navbar ">
					
					<li class="nav-item pcoded-hasmenu">
						<a href="#!" class="nav-link "><span class="pcoded-micon"><i class="feather icon-users"></i></span><span class="pcoded-mtext">User</span></a>
						<ul class="pcoded-submenu">
							<li><a href="{{ route('user.add') }}">Add User</a></li>
							<li><a href="{{ route('user.list') }}">All Users</a></li>
						</ul>
					</li>
					<li class="nav-item pcoded-hasmenu">
						<a href="#!" class="nav-link "><span class="pcoded-micon"><i class="feather icon-book"></i></span><span class="pcoded-mtext">Form</span></a>
						<ul class="pcoded-submenu">
						    <li><a href="{{ route('form.add') }}">Add Form</a></li>
							<li><a href="{{ route('form.list')}}">All Forms</a></li> 
							
						</ul>
					</li>
					<li class="nav-item">
						<a href="{{ route('todo') }}" class="nav-link "><span class="pcoded-micon"><i class="feather icon-check-square"></i></span><span class="pcoded-mtext">To-Do</span></a>
					</li>
					
					<li class="nav-item pcoded-hasmenu">
						<a href="#!" class="nav-link "><span class="pcoded-micon"><i class="feather icon-book"></i></span><span class="pcoded-mtext">Booking</span></a>
						<ul class="pcoded-submenu">
						    <li><a href="#">Staff</a></li>
							<li><a href="#">Booking</a></li> 
							
						</ul>
					</li>

				</ul>
			</div>
		</div>
	</nav>