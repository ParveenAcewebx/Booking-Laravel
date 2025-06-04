@extends('layouts.app')

@section('content')
<div class="pcoded-main-container">
    <div class="pcoded-content">
        <!-- [ breadcrumb ] start -->
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
                            <a href="{{ route('roles.add') }}" class="btn btn-primary float-right">Add Role</a>
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
                            <table id="booking-list-table" class="table nowrap">
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
                                <tbody>
                                    @foreach($allroles as $role)
                                    <tr>
                                        <td>
                                            <div class="d-inline-block align-middle">
                                                <div class="d-inline-block">
                                                    <h6 class="m-b-0">{{ $role->name }}</h6>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="permissions-listing">
                                            @php
                                            // Group permissions by entity (last word in permission slug)
                                            $groupedPermissions = [];
                                            foreach ($role->permissions as $permission) {
                                            $parts = preg_split('/[\s_]+/', $permission->name);
                                            $entity = strtolower(end($parts));
                                            if (!isset($groupedPermissions[$entity])) {
                                            $groupedPermissions[$entity] = [];
                                            }
                                            $groupedPermissions[$entity][] = $permission->name;
                                            }
                                            @endphp

                                            @foreach ($groupedPermissions as $entity => $permissions)
                                            @php
                                            $permissionList = implode(', ', $permissions);
                                            @endphp
                                            <span
                                                class="badge badge-light-success"
                                                data-toggle="tooltip"
                                                data-placement="right"
                                                title="{{ $permissionList }}"
                                                style="cursor: pointer;">
                                                {{ ucfirst($entity) }} ({{ count($permissions) }})
                                            </span><br>
                                            @endforeach
                                        </td>
                                        <td>
                                            @if ($role->status == config('constants.status.active'))
                                            <span class="badge badge-success">Active</span>
                                            @else
                                            <span class="badge badge-danger">Inactive</span>
                                            @endif
                                        </td>
                                          @canany(['delete roles', 'edit roles']) 
                                        <td>
                                            <div class="overlay-edit">
                                                @can('edit roles')
                                                <a href="{{ route('roles.edit', [$role->id]) }}" class="btn btn-icon btn-success" data-toggle="tooltip" data-placement="top" title="Edit Role">
                                                    <i class="fas fa-pencil-alt"></i>
                                                </a>
                                                @endcan
                                                @can('delete roles')
                                                <form action="{{ route('roles.delete', [$role->id]) }}" method="POST" id="delete-role-{{ $role->id }}" style="display:inline-block;">
                                                    @method('DELETE')
                                                    @csrf
                                                    <button class="btn btn-icon btn-danger" data-toggle="tooltip" data-placement="top" title="Delete Role" onclick="return confirm('Are you sure to delete this role?');">
                                                        <i class="feather icon-trash-2"></i>
                                                    </button>
                                                </form>
                                                @endcan
                                            </div>
                                        </td>
                                        @endcanany
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>Name</th>
                                        <th>Permissions</th>
                                        <th>Status</th>
                                       @canany(['delete roles', 'edit roles']) 
                                        <th>Actions</th>
                                        @endcanany
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection