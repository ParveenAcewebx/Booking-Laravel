@extends('layouts.app')

@section('content')
<div class="pcoded-main-container">
    <div class="pcoded-content">
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <div class="page-header-title">
                            <h5 class="m-b-10">Add Role</h5>
                        </div>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="feather icon-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="{{ route('roles.list') }}">Roles</a></li>
                            <li class="breadcrumb-item">Add Role</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 mx-auto">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('roles.store') }}" method="POST">
                            @csrf

                            <!-- Role Name -->
                            <div class="form-group">
                                <label for="name" class="font-weight-bold">Role Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror"
                                       value="{{ old('name') }}" required>
                                @error('name')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Status -->
                            <div class="form-group mt-3">
                                <div class="form-check">
                                    <input type="checkbox" name="status" id="status" value="1"
                                           class="form-check-input" {{ old('status', 1) ? 'checked' : '' }}>
                                    <label for="status" class="form-check-label font-weight-bold">Active</label>
                                </div>
                                @error('status')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Permissions Table -->
                            <div class="form-group mt-4">
                                <label class="font-weight-bold">Permissions</label>
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead class="thead-light">
                                            <tr>
                                                <th width="50">
                                                    <input type="checkbox" id="select-all-permissions">
                                                </th>
                                                <th>Name</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($roleGroups as $groupKey => $group)
                                                <!-- Group Header -->
                                                <tr class="bg-light align-middle">
                                                    <td class="text-center">
                                                        <input type="checkbox" class="group-checkbox" id="group_{{ $groupKey }}" data-group="{{ $groupKey }}">
                                                    </td>
                                                    <td>
                                                        <label for="group_{{ $groupKey }}" class="font-weight-bold mb-0">
                                                            {{ $group['name'] }}
                                                        </label>
                                                    </td>
                                                </tr>

                                                <!-- Group Permissions (Initially Hidden) -->
                                                @foreach($group['roles'] as $permission)
                                                    @php $permissionId = Str::slug($permission); @endphp
                                                    <tr class="permission-row group-perms-{{ $groupKey }} bg-light align-middle" style="display: none;">
                                                        <td class="text-center pl-4">
                                                            <input type="checkbox" name="permissions[]" value="{{ $permission }}"
                                                                   class="permission-checkbox group-{{ $groupKey }}" id="perm_{{ $permissionId }}">
                                                        </td>
                                                        <td>
                                                            <label for="perm_{{ $permissionId }}" class="mb-0">
                                                                {{ ucfirst($permission) }}
                                                            </label>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                @error('permissions')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Submit Buttons -->
                            <div class="form-group mt-4">
                                <button type="submit" class="btn btn-primary">Save Role</button>
                                <a href="{{ route('roles.list') }}" class="btn btn-secondary">Back</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
