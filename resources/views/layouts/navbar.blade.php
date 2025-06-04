<nav class="pcoded-navbar menupos-fixed">
    <div class="navbar-wrapper">
        <div class="navbar-content scroll-div">
            <div>
                <!-- User Profile Header -->
                <div class="main-menu-header">
                    <img class="img-radius"
                         src="{{ Auth::user()->avatar ? Storage::url(Auth::user()->avatar) : asset('assets/images/avatar-2.jpg') }}"
                         alt="User-Profile-Image">
                    <div class="user-details">
                        <div id="more-details">
                            <span class="mb-0 font-weight-bold">
                                {{ Auth::user()->name }}
                                <i class="fa fa-chevron-down m-l-5"></i>
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Collapsible User Actions -->
                <div class="collapse" id="nav-user-link">
                    <ul class="list-inline">
                        <li class="list-inline-item">
                            <a href="{{ route('profile') }}" data-toggle="tooltip" title="Profile">
                                <i class="feather icon-user"></i>
                            </a>
                        </li>
                        <li class="list-inline-item">
                            <a href="{{ route('logout') }}" data-toggle="tooltip" title="Logout" class="text-danger">
                                <i class="feather icon-power"></i>
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Navigation Menu -->
                <ul class="nav pcoded-inner-navbar">

                    {{-- User Management --}}
                    @canany(['view users', 'create users', 'edit users', 'delete users'])
                    <li class="nav-item pcoded-hasmenu">
                        <a href="#!" class="nav-link">
                            <span class="pcoded-micon"><i class="feather icon-users"></i></span>
                            <span class="pcoded-mtext">User</span>
                        </a>
                        <ul class="pcoded-submenu">
                            @can('create users')
                            <li><a href="{{ route('user.add') }}">Add User</a></li>
                            @endcan
                            @can('view users')
                            <li><a href="{{ route('user.list') }}">All Users</a></li>
                            @endcan
                        </ul>
                    </li>
                    @endcanany

                    {{-- Form Management --}}
                    @canany(['view forms', 'create forms', 'edit forms', 'delete forms'])
                    <li class="nav-item pcoded-hasmenu">
                        <a href="#!" class="nav-link">
                            <span class="pcoded-micon"><i class="feather icon-file-text"></i></span>
                            <span class="pcoded-mtext">Form</span>
                        </a>
                        <ul class="pcoded-submenu">
                            @can('create forms')
                            <li><a href="{{ route('form.add') }}">Add Form</a></li>
                            @endcan
                            @can('view forms')
                            <li><a href="{{ route('form.list') }}">All Forms</a></li>
                            @endcan
                        </ul>
                    </li>
                    @endcanany

                    {{-- Booking Management --}}
                    @canany(['view bookings', 'create bookings', 'edit bookings', 'delete bookings'])
                    <li class="nav-item pcoded-hasmenu">
                        <a href="#!" class="nav-link">
                            <span class="pcoded-micon"><i class="feather icon-book"></i></span>
                            <span class="pcoded-mtext">Booking</span>
                        </a>
                        <ul class="pcoded-submenu">
                            @can('create bookings')
                            <li><a href="{{ route('booking.add') }}">Add Booking</a></li>
                            @endcan
                            @can('view bookings')
                            <li><a href="{{ route('booking.list') }}">All Bookings</a></li>
                            @endcan
                        </ul>
                    </li>
                    @endcanany

                    {{-- Role Management --}}
                    @canany(['view roles', 'create roles', 'edit roles', 'delete roles'])
                    <li class="nav-item pcoded-hasmenu">
                        <a href="#!" class="nav-link">
                            <span class="pcoded-micon"><i class="fas fa-shield-alt"></i></span>
                            <span class="pcoded-mtext">Manage Role</span>
                        </a>
                        <ul class="pcoded-submenu">
                             @can('create roles')
                            <li><a href="{{ route('roles.add') }}">Add Role</a></li>
                            @endcan
                            @can('view roles')
                            <li><a href="{{ route('roles.list') }}">All Roles</a></li>
                            @endcan
                           
                        </ul>
                    </li>
                    @endcanany

                </ul>
            </div>
        </div>
    </div>
</nav>
