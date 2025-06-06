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
                        <div class="col-sm-6 card-body bg-white">
                            <h6 class="text-dark m-b-5">Total Users</h6>
                            <h4 class="text-dark mb-0">{{count($allusers);}}</h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-md-6">
                <div class="card flat-card">
                    <div class="row-table">
                        <div class="col-sm-6 card-body bg-white">
                            <h6 class="text-dark m-b-5">Total Bookings</h6>
                            <h4 class="text-dark mb-0">{{count($bookings);}}</h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-md-12">
                <div class="card flat-card">
                    <div class="row-table">
                        <div class="col-sm-6 card-body bg-white">
                            <h6 class="text-dark m-b-5">Total Booking Templates</h6>
                            <h4 class="text-dark mb-0">{{count($bookingForms);}}</h4>
                        </div>
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
                                            <th>user ID</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($allusers as $users)
                                        <tr>
                                            <td><img src="{{ asset('storage/' . $users->avatar) }}" alt="" style="width:35px;" class="img-20"></td>
                                            <td>{{$users->name}}</td>
                                            <td>
                                                <div><span class="badge badge-light-success">Active</span></div>
                                            </td>
                                            <td>{{$users->email}}</td>
                                            <td>{{$users->id}}</td>
                                            <td>
                                                <a href="{{ route('user.edit', [$users->id]) }}"><i class="icon feather icon-edit f-16  text-c-green"></i></a>
                                                <a href="{{route('user.delete', [$users->id])}}"><i class="feather icon-trash-2 ml-3 f-16 text-c-red"></i></a>
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