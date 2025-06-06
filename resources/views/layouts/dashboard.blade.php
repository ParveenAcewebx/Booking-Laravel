@extends('layouts.app')
@section('content')
<!-- [ Main Content ] start -->
<div class="pcoded-main-container">
    <div class="pcoded-content">
        <!-- [ breadcrumb ] start -->
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <div class="page-header-title">
                            <h5 class="m-b-10">Dashboard</h5>
                        </div>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('dashboard') }}"><i class="feather icon-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="{{route('dashboard') }}">Dashboard</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- [ breadcrumb ] end -->
        <!-- [ Main Content ] start -->
        <div class="row">
            <!-- View Chart Start-->
            <div class="col-xl-4 col-md-6">
                <div class="card flat-card">
                    <div class="row-table">
                        <a href="{{ route('user.list') }}" class="dashboard-card-link">
                            <div class="bg-white dash-card dashboard-card-hover">
                                <div class="card-body">
                                    <h6 class="text-dark m-b-5">Total Users</h6>
                                    <h4 class="text-dark mb-0">{{ count($allusers) }}</h4>
                                </div>
                            </div>
                        </a>

                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-md-6">
                <div class="card flat-card">
                    <div class="row-table">
                        <a href="{{ route('booking.list') }}" class="dashboard-card-link">
                            <div class="bg-white dash-card dashboard-card-hover">
                                <div class="card-body">
                                    <h6 class="text-dark m-b-5">Total Bookings</h6>
                                    <h4 class="text-dark mb-0">{{count($bookings);}}</h4>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-md-12">
                <div class="card flat-card">
                    <div class="row-table">
                        <a href="{{ route('template.list') }}" class="dashboard-card-link">
                            <div class="bg-white dash-card dashboard-card-hover">
                                <div class="card-body">
                                    <h6 class="text-dark m-b-5">Total Booking Templates</h6>
                                    <h4 class="text-dark mb-0">{{count($bookingForms);}}</h4>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-xl-8">
                <div class="card table-card">
                    <div class="card-header">
                        <h5>Users</h5>
                        <div class="card-header-right">
                            <div class="btn-group card-option">
                                <button type="button" class="btn dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="feather icon-more-horizontal"></i>
                                </button>
                                <ul class="list-unstyled card-option dropdown-menu dropdown-menu-right">
                                    <li class="dropdown-item full-card"><a href="#!"><span><i class="feather icon-maximize"></i> maximize</span><span style="display:none"><i class="feather icon-minimize"></i> Restore</span></a></li>
                                    <li class="dropdown-item minimize-card"><a href="#!"><span><i class="feather icon-minus"></i> collapse</span><span style="display:none"><i class="feather icon-plus"></i> expand</span></a></li>
                                    <li class="dropdown-item reload-card"><a href="#!"><i class="feather icon-refresh-cw"></i> reload</a></li>
                                    <li class="dropdown-item close-card"><a href="#!"><i class="feather icon-trash"></i> remove</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="pro-scroll" style="height:350px;position:relative;">
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover m-b-0">
                                    <thead>
                                        <tr>
                                            <th>Avatar</th>
                                            <th>Name</th>
                                            <th>Status</th>
                                            <th>Email</th>
                                            <th>Role</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($allusers as $users)
                                        <tr>
                                            <td>
                                                <img src="{{ $users->avatar ? Storage::url($users->avatar) : asset('assets/images/no-image-available.png') }}"
                                                    alt="" style="width:35px;" class="img-20">
                                            </td>
                                            <td>{{ $users->name }}</td>
                                            <td>
                                                <span class="badge {{ $users->status == config('constants.status.active') ? 'badge-light-success' : 'badge-light-danger' }}">
                                                    {{ $users->status == config('constants.status.active') ? 'Active' : 'Inactive' }}
                                                </span>
                                            </td>
                                            <td>{{ $users->email }}</td>
                                            <td>
                                                @php
                                                $roleName = $users->getRoleNames()->first();
                                                @endphp
                                                <span class="badge badge-primary">
                                                    {{ $roleName ? $roleName : 'No Role' }}
                                                </span>
                                            </td>
                                            <td class="d-flex align-items-center">
                                                <!-- Edit button -->
                                                @if(Auth::id() == $users->id)
                                                <a href="{{ route('profile') }}" data-toggle="tooltip" data-placement="top" title="Edit User">
                                                    <i class="icon feather icon-edit f-16 text-c-green"></i>
                                                </a>
                                                @else
                                                @can('edit users')
                                                <a href="{{ route('user.edit', [$users->id]) }}" data-toggle="tooltip" data-placement="top" title="Edit User">
                                                    <i class="icon feather icon-edit f-16 text-c-green"></i>
                                                </a>
                                                @endcan
                                                @endif

                                                <!-- Delete button -->
                                                @can('delete users')
                                                @if(Auth::id() != $users->id)
                                                <form action="{{ route('user.delete', [$users->id]) }}" method="POST" id="deleteUser-{{ $users->id }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" onclick="return deleteUser({{ $users->id }})" data-toggle="tooltip" data-placement="top" title="Delete User" class="delete-User">
                                                        <i class="feather icon-trash-2 ml-2 f-16 text-c-red"></i>
                                                    </button>
                                                </form>
                                                @endif
                                                @endcan

                                                <!-- Impersonation buttons -->
                                                @php
                                                $isImpersonating = session()->has('impersonate_original_user') || Cookie::get('impersonate_original_user');
                                                $currentUser = Auth::user();
                                                @endphp

                                                @if($isImpersonating && Auth::id() === $users->id)
                                                <!-- Switch Back button -->
                                                <form method="POST" action="{{ route('user.switch.back') }}" style="display:inline;">
                                                    @csrf
                                                    <button type="submit" class="logout-User" data-toggle="tooltip" data-placement="top" title="Switch Back to {{ $loginUser->name }}">
                                                        <i class="feather icon-log-out ml-2 f-16 text-c-black"></i>
                                                    </button>
                                                </form>
                                                @elseif(!$isImpersonating && $currentUser->hasRole('Administrator') && $currentUser->id !== $users->id)
                                                <!-- Switch User button -->
                                                <form method="POST" action="{{ route('user.switch', $users->id) }}" style="display:inline;">
                                                    @csrf
                                                    <button type="submit" class="logout-User" data-toggle="tooltip" data-placement="top" title="Switch User">
                                                        <i class="fas fa-random ml-2 f-16 text-c-black"></i>
                                                    </button>
                                                </form>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4">
                <div class="user-card-body card">
                    <div class="card-body">
                        <div class="top-card text-center">
                            <img src="{{ Auth::user()->avatar ? Storage::url(Auth::user()->avatar) : asset('assets/images/no-image-available.png') }}" class="img-fluid img-radius" alt="">
                        </div>
                        <div class="card-contain text-center p-t-20">
                            <h5 class="text-capitalize p-b-10">{{ Auth::user()->name }}</h5>
                            <p class="text-muted">{{ Auth::user()->email }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- [ Main Content ] end -->
    </div>
</div>
@endsection