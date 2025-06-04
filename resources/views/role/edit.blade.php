@extends('layouts.app')

@section('content')
<div class="pcoded-main-container">
    <div class="pcoded-content">
        <div class="row">
            <div class="col-md-12 mx-auto">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('roles.update', $role->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="form-group">
                                <label for="name" class="font-weight-bold">Role Name <span class="text-danger">*</span></label>
                                <input type="text"
                                    name="name"
                                    id="name"
                                    class="form-control @error('name') is-invalid @enderror"
                                    value="{{ old('name', $role->name) }}"
                                    required>
                                @error('name')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group mt-4">
                                <div class="form-check">
                                    <input type="checkbox" 
                                        name="status" 
                                        id="status" 
                                        value="1" 
                                        class="form-check-input"
                                        {{ old('status', $role->status) ? 'checked' : '' }}>
                                    <label for="status" class="form-check-label font-weight-bold">Active</label>
                                </div>
                                @error('status')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group mt-4">
                                <label class="font-weight-bold">Select Role Groups</label>
                                <div class="row">
                                    @foreach($roleGroups as $groupKey => $group)
                                        @php
                                            $hasPermission = count(array_intersect($group['roles'], $rolePermissions)) > 0;
                                        @endphp
                                        <div class="col-md-3">
                                            <div class="form-check">
                                                <input type="checkbox"
                                                    class="form-check-input role-group-toggle"
                                                    id="group_{{ $groupKey }}"
                                                    data-group="{{ $groupKey }}"
                                                    {{ $hasPermission ? 'checked' : '' }}>
                                                <label class="form-check-label" for="group_{{ $groupKey }}">{{ $group['name'] }}</label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="form-group mt-4">
                                <label class="font-weight-bold">Permissions</label>
                                <div class="table-responsive">
                                    <table class="table table-bordered permission-table">
                                        <thead class="thead-light">
                                            <tr>
                                                <th><input type="checkbox" id="select-all-permissions"></th>
                                                <th>Permission</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($roleGroups as $groupKey => $group)
                                                @foreach($group['roles'] as $index => $permissionName)
                                                    @php
                                                        $checkboxId = "perm_{$groupKey}_{$index}";
                                                        $isChecked = in_array($permissionName, old('permissions', $rolePermissions));
                                                    @endphp
                                                    <tr class="perm-row-{{ $groupKey }}" style="{{ $isChecked ? '' : 'display:none;' }}">
                                                        <td>
                                                            <input type="checkbox"
                                                                name="permissions[]"
                                                                value="{{ $permissionName }}"
                                                                id="{{ $checkboxId }}"
                                                                class="permission-checkbox"
                                                                {{ $isChecked ? 'checked' : '' }}>
                                                        </td>
                                                        <td>
                                                            <label for="{{ $checkboxId }}">{{ ucfirst($permissionName) }}</label>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                @error('permissions')
                                    <div class="text-danger mt-2">{{ $message }}</div>
                                @enderror
                            </div>
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

<style>
    .permission-table {
        width: 100%;
        border-collapse: collapse;
    }
    .permission-table thead th {
        background-color: #f7f7f7;
        border-bottom: 1px solid #dee2e6;
        padding: 10px;
    }
    .permission-table tbody tr:nth-child(even) {
        background-color: #f9f9f9;
    }
    .permission-table tbody tr:hover {
        background-color: #f1f1f1;
    }
    .permission-table td {
        padding: 10px;
        vertical-align: middle;
    }
    .form-check-input {
        transform: scale(1.2);
        margin-top: 5px;
    }
    label {
        font-weight: 500;
    }
    .form-control {
        border-radius: 0.25rem;
    }
    .btn {
        min-width: 120px;
    }
</style>
@endsection
