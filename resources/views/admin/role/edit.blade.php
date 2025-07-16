@extends('admin.layouts.app')

@section('content')
<div class="pcoded-main-container">
    <div class="pcoded-content">
        <!-- Page Header -->
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <div class="page-header-title">
                            <h5 class="m-b-10">Edit Role</h5>
                        </div>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="feather icon-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="{{ route('roles.list') }}">Roles</a></li>
                            <li class="breadcrumb-item">Edit Role</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Card -->
        <div class="row">
            <div class="col-md-12 mx-auto">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('roles.update', $role->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <!-- Role Name -->
                            <div class="form-group">
                                <label for="name" class="font-weight-bold">Role Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $role->name ?? '') }}" required>
                                @error('name')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Status -->
                            <div class="form-group mt-3">
                                <label for="status" class="form-label d-block">Status</label>
                                <select name="status" id="status" class="form-control select-user">
                                    <option value="{{ config('constants.status.active') }}"
                                        {{ old('status', $role->status) == config('constants.status.active') ? 'selected' : '' }}>
                                        Active
                                    </option>
                                    <option value="{{ config('constants.status.inactive') }}"
                                        {{ old('status', $role->status) == config('constants.status.inactive') ? 'selected' : '' }}>
                                        Inactive
                                    </option>
                                </select>
                                @error('status')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>


                            <!-- Permissions Table -->
                            <div class="form-group mt-4">
                                <label class="font-weight-bold">Permissions</label>
                                <div class="table-responsive border">
                                    <table class="table table-bordered mb-0">
                                        <thead class="thead-light">
                                            <tr>
                                                <th style="width: 60px;">
                                                    <div class="custom-control custom-checkbox">
                                                        <input type="checkbox" id="select-all-permissions" class="custom-control-input">
                                                        <label class="custom-control-label" for="select-all-permissions"></label>
                                                    </div>
                                                </th>
                                                <th>Name</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($roleGroups as $groupKey => $group)
                                            @php
                                            $oldPerms = old('permissions', $rolePermissions ?? []);
                                            $groupHasCheckedPerm = false;
                                            foreach ($group['roles'] as $perm) {
                                            if (in_array($perm, $oldPerms)) {
                                            $groupHasCheckedPerm = true;
                                            break;
                                            }
                                            }
                                            $groupSlug = $group['slug'];
                                            @endphp
                                            <tr class="bg-light align-middle">
                                                <td class="text-center">
                                                    <div class="custom-control custom-checkbox">
                                                        <input type="checkbox" class="custom-control-input group-checkbox" data-group="{{ $groupSlug }}" id="group_{{ $groupKey }}" {{ $groupHasCheckedPerm ? 'checked' : '' }}>
                                                        <label class="custom-control-label" for="group_{{ $groupKey }}"></label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="d-flex justify-content-between align-items-center w-100">
                                                        <label for="group_{{ $groupKey }}" class="font-weight-bold mb-0">
                                                            {{ $group['name'] }}
                                                        </label>
                                                        <i class="feather icon-chevron-right toggle-icon" data-group="{{ $groupSlug }}" style="cursor: pointer;"></i>
                                                    </div>
                                                </td>
                                            </tr>

                                            @foreach($group['roles'] as $permission)
                                            @php $permissionId = Str::slug($permission); @endphp
                                            <tr class="permission-row group-perms-{{ $groupSlug }}" style="display: none;">
                                                <td class="text-center pl-4">
                                                    <div class="custom-control custom-checkbox">
                                                        <input type="checkbox" name="permissions[]" value="{{ $permission }}" class="custom-control-input permission-checkbox group-{{ $groupSlug }}" id="perm_{{ $permissionId }}" {{ in_array($permission, $oldPerms) ? 'checked' : '' }}>
                                                        <label class="custom-control-label" for="perm_{{ $permissionId }}"></label>
                                                    </div>
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
                                <button type="submit" class="btn btn-primary">Update</button>
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