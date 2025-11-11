@extends('admin.layouts.app')

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
                            <li class="breadcrumb-item active"><a href="{{ route('roles.list') }}">Roles</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('roles.list') }}">All Roles</a></li>
                        </ul>
                    </div>

                    <div class="col-md-2">
                        <div class="page-header-titles float-right">
                            @can('create roles')
                                <a href="{{ route('roles.add') }}" class="btn btn-primary mr-2 p-2">Add Role</a>
                            @endcan
                            @can('delete roles')
                                <button id="bulkRolesDeleteBtn" class="btn btn-danger p-2 bulkDeleteBtn" disabled>Delete</button>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Roles Table -->
        <div class="row">
            <div class="col-lg-12">
                <div class="card user-profile-list">
                    <div class="card-body">
                        <div class="dt-responsive table-responsive">
                            <table id="roles-list-table" class="table table-striped nowrap" width="100%">
                                <thead>
                                    <tr>
                                        <th><input type="checkbox" id="selectAll"></th>
                                        <th style="display:none;">ID</th>
                                        <th>Name</th>
                                        <th>Permissions</th>
                                        <th>Status</th>
                                        @canany(['edit roles', 'delete roles'])
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

<!-- DataTables & SweetAlert -->
<script>
    $(function() {
        var table = $('#roles-list-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('roles.list') }}",
            columns: [
                {
                    data: 'checkbox',
                    name: 'checkbox',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'id',
                    name: 'id',
                    visible: false
                },
                {
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'permissions',
                    name: 'permissions',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'status',
                    name: 'status',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                }
            ],
            order: [[1, 'desc']],
            lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]]
        });

        // Toastr options
        toastr.options = {
            closeButton: true,
            progressBar: true,
            timeOut: 4000,
            positionClass: "toast-top-right"
        };

        @if(session('success'))
            toastr.success("{{ session('success') }}");
        @endif
        @if(session('error'))
            toastr.error("{{ session('error') }}");
        @endif

        // Bulk delete setup
        bulkDelete("{{ route('roles.bulk-delete') }}");
    });
</script>
@endsection
