@extends('layouts.app')

@section('content')
<div class="pcoded-main-container">
    <div class="pcoded-content">
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-10">
                        <div class="page-header-title">
                            <h5 class="m-b-10">All Services</h5>
                        </div>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="feather icon-home"></i></a></li>
                            <li class="breadcrumb-item active">Services</li>
                        </ul>
                    </div>
                    <div class="col-md-2 text-right">
                        <a href="{{ route('service.add') }}" class="btn btn-primary">Add Service</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Services Table -->
        <div class="row">
            <div class="col-lg-12">
                <div class="card user-profile-list">
                    <div class="card-body">
                        <div class="dt-responsive table-responsive">
                            <table id="service-list-table" class="table table-striped nowrap" width="100%">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Description</th>
                                        <th>Staff Member</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        @if(session('success'))
        swal({
            title: "Success!",
            text: "{{ session('success') }}",
            icon: "success",
            button: "OK"
        });
        @endif
    });

    $(function () {
        $('#service-list-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('service.list') }}", 
            columns: [
                { data: 'name', name: 'name' },
                { data: 'description', name: 'description' },
                { data: 'staff_member', name: 'staff_member' },
                { data: 'status', name: 'status' },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ],
            order: [[0, 'desc']]
        });
    });
</script>
@endsection
