<nav class="pcoded-navbar menupos-fixed @if($isNavCollapsed ?? false) navbar-collapsed @endif">
    <div class="navbar-wrapper d-flex flex-column" style="height: 92vh;">
        <div class="navbar-content scroll-div flex-grow-1 d-flex flex-column">

            {{-- User Header --}}
            <div>
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

                {{-- Sidebar Menu --}}
                <ul class="nav pcoded-inner-navbar">

                    {{-- Dashboard --}}
                    <li class="nav-item">
                        <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                            <span class="pcoded-micon"><i class="feather icon-home"></i></span>
                            <span class="pcoded-mtext">Dashboard</span>
                        </a>
                    </li>

                    {{-- Users --}}
                    @canany(['view users', 'create users', 'edit users', 'delete users'])
                    <li class="nav-item pcoded-hasmenu {{ request()->routeIs('user.*') ? 'pcoded-trigger' : '' }}">
                        <a href="#!" class="nav-link">
                            <span class="pcoded-micon"><i class="feather icon-users"></i></span>
                            <span class="pcoded-mtext">Users</span>
                        </a>
                        <ul class="pcoded-submenu" @if(request()->routeIs('user.*')) style="display:block;" @endif>
                            @can('create users')
                            <li class="{{ request()->routeIs('user.add') ? 'active' : '' }}">
                                <a href="{{ route('user.add') }}">Add User</a>
                            </li>
                            @endcan
                            @can('view users')
                            <li class="{{ request()->routeIs('user.list') ? 'active' : '' }}">
                                <a href="{{ route('user.list') }}">All Users</a>
                            </li>
                            @endcan
                        </ul>
                    </li>
                    @endcanany

                    {{-- Roles --}}
                    @canany(['view roles', 'create roles', 'edit roles', 'delete roles'])
                    <li class="nav-item pcoded-hasmenu {{ request()->routeIs('roles.*') ? 'pcoded-trigger' : '' }}">
                        <a href="#!" class="nav-link">
                            <span class="pcoded-micon"><i class="fas fa-shield-alt"></i></span>
                            <span class="pcoded-mtext">Manage Roles</span>
                        </a>
                        <ul class="pcoded-submenu" @if(request()->routeIs('roles.*')) style="display:block;" @endif>
                            @can('create roles')
                            <li class="{{ request()->routeIs('roles.add') ? 'active' : '' }}">
                                <a href="{{ route('roles.add') }}">Add Role</a>
                            </li>
                            @endcan
                            @can('view roles')
                            <li class="{{ request()->routeIs('roles.list') ? 'active' : '' }}">
                                <a href="{{ route('roles.list') }}">All Roles</a>
                            </li>
                            @endcan
                        </ul>
                    </li>
                    @endcanany

                    {{-- Booking Templates --}}
                    @canany(['view templates', 'create templates', 'edit templates', 'delete templates'])
                    <li class="nav-item pcoded-hasmenu {{ request()->routeIs('template.*') ? 'pcoded-trigger' : '' }}">
                        <a href="#!" class="nav-link">
                            <span class="pcoded-micon"><i class="feather icon-file-text"></i></span>
                            <span class="pcoded-mtext">Booking Templates</span>
                        </a>
                        <ul class="pcoded-submenu" @if(request()->routeIs('template.*')) style="display:block;" @endif>
                            @can('create templates')
                            <li class="{{ request()->routeIs('template.add') ? 'active' : '' }}">
                                <a href="{{ route('template.add') }}">Add Booking Template</a>
                            </li>
                            @endcan
                            @can('view templates')
                            <li class="{{ request()->routeIs('template.list') ? 'active' : '' }}">
                                <a href="{{ route('template.list') }}">All Booking Templates</a>
                            </li>
                            @endcan
                        </ul>
                    </li>
                    @endcanany

                    {{-- Services --}}
                    @canany([
                    'view services', 'create services', 'edit services', 'delete services',
                    'view categories', 'create categories', 'edit categories', 'delete categories'
                    ])
                    @php
                    $isServiceOrCategoryRoute = request()->routeIs('service.*') || request()->routeIs('category.*');
                    @endphp

                    <li class="nav-item pcoded-hasmenu {{ $isServiceOrCategoryRoute ? 'pcoded-trigger' : '' }}">
                        <a href="#!" class="nav-link">
                            <span class="pcoded-micon"><i class="fas fa-tools"></i></span>
                            <span class="pcoded-mtext">Manage Services</span>
                        </a>
                        <ul class="pcoded-submenu" @if($isServiceOrCategoryRoute) style="display:block;" @endif>
                            @can('create services')
                            <li class="{{ request()->routeIs('service.add') ? 'active' : '' }}">
                                <a href="{{ route('service.add') }}">Add Service</a>
                            </li>
                            @endcan
                            @can('view services')
                            <li class="{{ request()->routeIs('service.list') ? 'active' : '' }}">
                                <a href="{{ route('service.list') }}">All Services</a>
                            </li>
                            @endcan
                            @can('view categories')
                            <li class="{{ request()->routeIs('category.list') ? 'active' : '' }}">
                                <a href="{{ route('category.list') }}">Categories</a>
                            </li>
                            @endcan
                        </ul>
                    </li>
                    @endcanany

                    {{-- Vendors --}}
                    @canany(['view vendors', 'create vendors', 'edit vendors', 'delete vendors'])
                    <li class="nav-item pcoded-hasmenu {{ request()->routeIs('vendors.*') ? 'pcoded-trigger' : '' }}">
                        <a href="#!" class="nav-link">
                            <span class="pcoded-micon"><i class="fas fa-store"></i></span>
                            <span class="pcoded-mtext">Manage Vendors</span>
                        </a>
                        <ul class="pcoded-submenu" @if(request()->routeIs('vendors.*')) style="display:block;" @endif>
                            @can('create vendors')
                            <li class="{{ request()->routeIs('vendors.add') ? 'active' : '' }}">
                                <a href="{{ route('vendors.add') }}">Add Vendor</a>
                            </li>
                            @endcan
                            @can('view vendors')
                            <li class="{{ request()->routeIs('vendors.list') ? 'active' : '' }}">
                                <a href="{{ route('vendors.list') }}">All Vendors</a>
                            </li>
                            @endcan
                        </ul>
                    </li>
                    @endcanany
                    {{-- Staffs --}}
                    @canany(['view staffs', 'create staffs', 'edit staffs', 'delete staffs'])
                    <li class="nav-item pcoded-hasmenu {{ request()->routeIs('staff.*') ? 'pcoded-trigger' : '' }}">
                        <a href="#!" class="nav-link">
                            <span class="pcoded-micon"><i class="fas fa-user-tie"></i></span>
                            <span class="pcoded-mtext">Manage Staffs</span>
                        </a>
                        <ul class="pcoded-submenu" @if(request()->routeIs('staff.*')) style="display:block;" @endif>
                            @can('create staffs')
                            <li class="{{ request()->routeIs('staff.create') ? 'active' : '' }}">
                                <a href="{{ route('staff.create') }}">Add Staff</a>
                            </li>
                            @endcan
                            @can('view staffs')
                            <li class="{{ request()->routeIs('staff.list') ? 'active' : '' }}">
                                <a href="{{ route('staff.list') }}">All Staffs</a>
                            </li>
                            @endcan
                        </ul>
                    </li>
                    @endcanany

                    {{-- Bookings --}}
                    @canany(['view bookings', 'create bookings', 'edit bookings', 'delete bookings'])
                    <li class="nav-item pcoded-hasmenu {{ request()->routeIs('booking.*') ? 'pcoded-trigger' : '' }}">
                        <a href="#!" class="nav-link">
                            <span class="pcoded-micon"><i class="feather icon-book"></i></span>
                            <span class="pcoded-mtext">Bookings</span>
                        </a>
                        <ul class="pcoded-submenu" @if(request()->routeIs('booking.*')) style="display:block;" @endif>
                            @can('create bookings')
                            <li class="{{ request()->routeIs('booking.add') ? 'active' : '' }}">
                                <a href="{{ route('booking.add') }}">Add Booking</a>
                            </li>
                            @endcan
                            @can('view bookings')
                            <li class="{{ request()->routeIs('booking.list') ? 'active' : '' }}">
                                <a href="{{ route('booking.list') }}">All Bookings</a>
                            </li>
                            @endcan
                        </ul>
                    </li>
                    @endcanany

                    {{-- Settings --}}
                    @can('access settings')
                    <li class="nav-item">
                        <a href="{{ route('settings') }}" class="nav-link {{ request()->routeIs('settings') ? 'active' : '' }}">
                            <span class="pcoded-micon"><i class="feather icon-sliders"></i></span>
                            <span class="pcoded-mtext">Settings</span>
                        </a>
                    </li>
                    @endcan
                </ul>
            </div>

            {{-- Switch Back Button --}}
            @php
            $isImpersonating = session()->has('impersonate_original_user') || Cookie::get('impersonate_original_user');
            @endphp

            @if ($isImpersonating && Auth::user() && isset($loginUser))
            <div class="mt-auto">
                <form method="POST" action="{{ route('user.switch.back') }}">
                    @csrf
                    <button type="submit" class="btn btn-danger btn-block d-flex align-items-center navbar-switch-button">
                        <i class="feather icon-log-out"></i>
                        <span class="ml-2">Switch Back</span>
                    </button>
                </form>
            </div>
            @endif
        </div>
    </div>
</nav>