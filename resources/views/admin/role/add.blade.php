@extends('admin.layouts.app')

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
                            <li class="breadcrumb-item">
                                <a href="{{ route('dashboard') }}">
                                    <i class="feather icon-home"></i>
                                </a>
                            </li>
                            <li class="breadcrumb-item"><a href="{{ route('roles.list') }}">Roles</a></li>
                            <li class="breadcrumb-item">Add Role</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <form action="{{ route('roles.store') }}" method="POST">
            @csrf
            <div class="row">
                <!-- Left Column -->
                <div class="col-md-8 order-md-1">
                    <div class="card">
                        <div class="card-header">
                            <h5>Role Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <!-- Name -->
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-label">Role</label>
                                        <input type="text" name="name" id="name"
                                            class="form-control @error('name') is-invalid @enderror"
                                            value="{{ old('name') }}" required>
                                        @error('name')
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group mt-2 mb-1">
                                        <label class="font-weight-bold">Permissions</label>
                                        <div class="table-responsive">
                                            <table class="table table-bordered">
                                                <thead class="thead-light">
                                                    <tr>
                                                        <th width="60">
                                                            <div class="custom-control custom-checkbox">
                                                                <input type="checkbox" class="custom-control-input" id="select-all-permissions">
                                                                <label class="custom-control-label" for="select-all-permissions"></label>
                                                            </div>
                                                        </th>
                                                        <th>Name</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($roleGroups as $groupKey => $group)
                                                    <tr class="bg-light align-middle">
                                                        <td class="text-center">
                                                            <div class="custom-control custom-checkbox">
                                                                <input type="checkbox" class="custom-control-input group-checkbox" id="group_{{ $groupKey }}" data-group="{{ $group['slug'] }}">
                                                                <label class="custom-control-label" for="group_{{ $groupKey }}"></label>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="d-flex justify-content-between align-items-center w-100">
                                                                <span class="font-weight-bold">{{ $group['name'] }}</span>
                                                                <i class="feather icon-chevron-right toggle-icon" data-group="{{ $group['slug'] }}" style="cursor: pointer;"></i>
                                                            </div>
                                                        </td>
                                                    </tr>

                                                    @foreach($group['roles'] as $permission)
                                                    @php $permissionId = \Illuminate\Support\Str::slug($permission); @endphp
                                                    <tr class="permission-row group-perms-{{ $group['slug'] }}" style="display: none;">
                                                        <td class="text-center pl-4">
                                                            <div class="custom-control custom-checkbox">
                                                                <input type="checkbox"
                                                                    name="permissions[]"
                                                                    value="{{ $permission }}"
                                                                    class="custom-control-input permission-checkbox group-{{ $group['slug'] }}"
                                                                    id="perm_{{ $permissionId }}">
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

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column - Original Avatar Upload -->
                <div class="col-md-4 order-md-2">
                    <div class="card">
                        <div class="card-header">
                            <h5>Settings</h5>
                        </div>
                        <div class="card-body">
                            <div class="form-group col-md-12 p-0">
                                <!-- Status -->
                                <div class="form-group mt-3">
                                    <label for="status" class="form-label d-block font-weight-bold">Status</label>
                                    <select name="status" id="status" class="form-control select-user">
                                        <option value="{{ config('constants.status.active') }}"
                                            {{ old('status', 1) == config('constants.status.active') ? 'selected' : '' }}>
                                            Active
                                        </option>
                                        <option value="{{ config('constants.status.inactive') }}"
                                            {{ old('status', 1) == config('constants.status.inactive') ? 'selected' : '' }}>
                                            Inactive
                                        </option>
                                    </select>
                                    @error('status')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mt-4">
                                <div class="col-md-12 text-right">
                                    <button type="submit" class="btn btn-primary">Save</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
</div>
@endsection