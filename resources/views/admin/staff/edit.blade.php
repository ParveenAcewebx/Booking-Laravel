@extends('admin.layouts.app')

@section('content')
<div class="pcoded-main-container">
    <div class="pcoded-content">
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <div class="page-header-title">
                            <h5 class="m-b-10">Edit Staff</h5>
                        </div>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="feather icon-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="{{ route('staff.list') }}">Staff</a></li>
                            <li class="breadcrumb-item">Edit Staff</li>
                        </ul>
                    </div>
                </div>
            </div>

            @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    {{-- ✅ One unified card-body --}}
                    <div class="card-body">
                        <ul class="nav nav-tabs mb-3" role="tablist">
                            <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#user-details" role="tab"><i class="feather icon-info"></i> User Details</a></li>
                            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#assigned-services" role="tab"><i class="feather icon-briefcase"></i> Assigned Services</a></li>
                            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#working-days" role="tab"><i class="feather icon-clock"></i> Work Hours</a></li>
                            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#days-off" role="tab"><i class="feather icon-calendar"></i> Days Off</a></li>
                        </ul>

                        <form method="POST" action="{{ route('staff.update', $staff->id) }}" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="tab-content">

                                {{-- TAB: USER DETAILS --}}
                                @include('admin.staff.partials.edit.user-details')

                                {{-- TAB: ASSIGNED SERVICES --}}
                                @include('admin.staff.partials.edit.assigned-services')

                                {{-- TAB: WORKING DAYS --}}
                                @include('admin.staff.partials.edit.working-days')

                                {{-- TAB: DAYS OFF --}}
                                @include('admin.staff.partials.edit.days-off')

                                <div class="mt-4">
                                    <button type="submit" class="btn btn-primary savebutton">Update</button>
                                    <a href="{{ route('staff.list') }}" class="btn btn-secondary">Back</a>
                                </div>

                        </form>
                    </div> {{-- end card-body --}}
                </div> {{-- end card --}}
            </div>
        </div>
    </div>
</div>
@endsection
