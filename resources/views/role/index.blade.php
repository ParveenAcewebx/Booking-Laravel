@extends('layouts.app')

@section('content')
<div class="pcoded-main-container">
    <div class="pcoded-content">
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-10">
                        <div class="page-header-title">
                            <h5>All Roles</h5>
                        </div>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="feather icon-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="{{ route('roles.list') }}">Roles</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('roles.list') }}">All Roles</a></li>
                        </ul>
                    </div>

                    <div class="col-md-2">
                        <div class="page-header-titles float-right">
                            @can('create roles')
                            <a href="{{ route('roles.add') }}" class="btn btn-primary float-right p-2">Add Role</a>
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
                        <div class="dt-responsive table-responsive">
                            <table id="roles-list-table" class="table nowrap">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Permissions</th>
                                        <th>Status</th>
                                        @canany(['delete roles', 'edit roles'])
                                        <th>Actions</th>
                                        @endcanany
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

<!-- DataTable Script -->
<script>
    $(function () {
        $('#roles-list-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('roles.list') }}",
            columns: [
                { data: 'name', name: 'name' },
                { data: 'permissions', name: 'permissions', orderable: false, searchable: false },
                { data: 'status', name: 'status', orderable: false, searchable: false },
                @canany(['delete roles', 'edit roles'])
                { data: 'action', name: 'action', orderable: false, searchable: false },
                @endcanany
            ],
            order: [[0, 'asc']],
            lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
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
