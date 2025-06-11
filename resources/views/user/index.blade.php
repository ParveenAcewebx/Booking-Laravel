@extends('layouts.app')

@section('content')

<div class="pcoded-main-container">
    <div class="pcoded-content">
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-10">
                        <div class="page-header-title">
                            <h5>All Users</h5>
                        </div>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('dashboard') }}">
                                    <i class="feather icon-home"></i>
                                </a>
                            </li>
                            <li class="breadcrumb-item"><a href="{{route('booking.list') }}">User</a></li>
                            <li class="breadcrumb-item"><a href="{{route('booking.list') }}">All Users</a></li>
                        </ul>

                    </div>
                    <div class="col-md-2">
                        <div class="page-header-titles float-right">
                            @can('create users')
                            <a href="{{ route('user.add') }}" class="btn btn-primary float-right p-2">Add User</a>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="card user-profile-list">
                    <div class="card-body">
                        <div class="dt-responsive">
                            <table class="table table-striped nowrap" id="users-table" width="100%">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Created Date</th>
                                        <th>Role</th>
                                        <th>Status</th>
                                        <th>Actions</th>
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

<script type="text/javascript">
    $(function() {
        $('#users-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('user.list') }}",
            columns: [
                { data: 'name', name: 'users.name', orderable: true , searchable: true },
                { data: 'created_at', name: 'users.created_at'},
                { data: 'roles', name: 'roles.name', orderable: false, searchable: true },
                { data: 'status', name: 'users.status', orderable: false, searchable: false },
                { data: 'action', name: 'action', orderable: false, searchable: false },
            ],
            lengthMenu: [ [10, 25, 50, 100], [10, 25, 50, 100] ],
            order: [[1, 'desc']], 
        });

    });

    document.addEventListener("DOMContentLoaded", function() {
		@if(session('success'))
		swal({
			title: "Success!",
			text: "{{ session('success') }}",
			icon: "success",
			timer: 2000,
			buttons: false
		});
		@endif

		@if(session('error'))
		swal({
			title: "Error!",
			text: "{{ session('error') }}",
			icon: "error", // changed from 'danger' to 'error'
			timer: 2000,
			buttons: false
		});
		@endif
	});
</script>

@endsection
