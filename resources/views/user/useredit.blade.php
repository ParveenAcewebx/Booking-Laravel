@extends('layouts.app')
@section('content')

<section class="pcoded-main-container">
    <div class="pcoded-content">
        <!-- [ breadcrumb ] start -->
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <div class="page-header-title">
                            <h5 class="m-b-10">User Edit</h5>
                        </div>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('home') }}"><i class="feather icon-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="/user">Users</a></li>
                            <li class="breadcrumb-item"><a href="#!">User Edit</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- [ breadcrumb ] end -->
        <!-- [ Main Content ] start -->
        <form action="{{ route('user.update', $user->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <!-- [ Form Validation ] start -->
                <div class="col-md-4 order-md-1">
                    <div class="card">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <h5 class="mb-0">Avatar</h5>
                        </div>
                        <div class="card-body">
                            <ul class="list-inline">
                                <li class="list-inline-item"><img src="{{ asset('storage/' . $user->avatar) }}" alt="user image" class="img-radius mb-2 wid-50" data-toggle="tooltip" title="{{ old('username', $user->name) }}"></li>
                            </ul>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Upload</span>
                                </div>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" name="avatar" id="inputGroupFile01">
                                    <label class="custom-file-label" for="inputGroupFile01">Choose file</label>
                                </div>
                                @error('avatar')
                                <div class="error">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>			
                </div>
                <div class="col-md-8 order-md-2">
                    <div class="card">
                        <div class="card-header">
                            <h5>User Information</h5>
                            @if(session('success'))
                                <div class="alert alert-success" role="alert">
                                    {{ session('success') }}   
                                </div>
                            @endif
                            @if(session('error'))
                                <div class="alert alert-danger" role="alert">
                                    {{ session('error') }}
                                </div>
                            @endif
                        </div>
                        <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Name</label>
                                            <input type="text" class="form-control" name="username" value="{{ old('username', $user->name) }}" placeholder="Name">
                                            @error('username')
                                            <div class="error">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Email</label>
                                            <input type="email" class="form-control" name="email" value="{{ old('email', $user->email) }}" placeholder="Email" readonly>
                                            @error('email')
                                            <div class="error">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Password</label>
                                            <input type="password" class="form-control" name="password" placeholder="Password">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Confirm Password</label>
                                            <input type="password" class="form-control" name="password_confirmation" placeholder="Confirm Password">
                                            @error('password')
                                            <div class="error">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </div>	
                </div>
                <!-- [ Form Validation ] end -->
            </div>
        </form>
        <!-- [ Main Content ] end -->
    </div>
</section>

@endsection