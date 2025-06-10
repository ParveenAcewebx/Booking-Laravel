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

        @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        @endif

        @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        @endif

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
</script>
@endsection
