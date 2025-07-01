<nav class="pcoded-navbar menupos-fixed @if($isNavCollapsed ?? false) navbar-collapsed @endif">
    <div class="navbar-wrapper d-flex flex-column" style="height: 92vh;">
        <div class="navbar-content scroll-div flex-grow-1 d-flex flex-column">

            <div>
                <!-- User Profile Header -->
                <div class="main-menu-header">
                    <img class="img-radius hei-40"
                        src="{{ Auth::user()->avatar ? Storage::url(Auth::user()->avatar) : asset('assets/images/no-image-available.png') }}"
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


                    <li class="nav-item">
                        <a href="{{ route('dashboard') }}" class="nav-link">
                            <span class="pcoded-micon"><i class="feather icon-home"></i></span>
                            <span class="pcoded-mtext">Dashboard</span>
                        </a>
                    </li>
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
                    {{-- Form Management --}}
                    @canany(['view forms', 'create forms', 'edit forms', 'delete forms'])
                    <li class="nav-item pcoded-hasmenu">
                        <a href="#!" class="nav-link">
                            <span class="pcoded-micon"><i class="feather icon-file-text"></i></span>
                            <span class="pcoded-mtext">Booking Template</span>
                        </a>
                        <ul class="pcoded-submenu">
                            @can('create forms')
                            <li><a href="{{ route('template.add') }}">Add Booking Template</a></li>
                            @endcan
                            @can('view forms')
                            <li><a href="{{ route('template.list') }}">All Booking Template</a></li>
                            @endcan
                        </ul>
                    </li>
                    @endcanany
                    {{-- Service Management --}}
                    @canany(['view services', 'create services', 'edit services', 'delete services'])
                    <li class="nav-item pcoded-hasmenu">
                        <a href="#!" class="nav-link">
                            <span class="pcoded-micon"><i class="fas fa-tools"></i></span>
                            <span class="pcoded-mtext">Manage Service</span>
                        </a>
                        <ul class="pcoded-submenu">
                            @can('create services')
                            <li><a href="{{ route('service.add') }}">Add Service</a></li>
                            @endcan
                            @can('view services')
                            <li><a href="{{ route('service.list') }}">All Services</a></li>
                            @endcan
                            @can('view categories')
                            <li><a href="{{ route('category.list') }}">All Category</a></li>
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



                </ul>
            </div>

            <!-- Switch Back Button pushed to bottom -->
            @php
            $isImpersonating = session()->has('impersonate_original_user') || Cookie::get('impersonate_original_user');
            $currentUser = Auth::user();
            @endphp

            @if ($isImpersonating && $currentUser && isset($loginUser))
            <div class="mt-auto">
                <form method="POST" action="{{ route('user.switch.back') }}">
                    @csrf
                    <button type="submit" class="btn btn-danger btn-block d-flex align-items-center navbar-switch-button">
                        <i class="feather icon-log-out"></i>
                        <span id="switchBackText" class="ml-2">Switch Back</span>
                    </button>
                </form>
            </div>
            @endif

        </div>
    </div>
</nav>