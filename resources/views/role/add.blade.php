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

                            <div class="form-group">
                                <label for="name" class="font-weight-bold">Role Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" id="name"
                                    class="form-control @error('name') is-invalid @enderror"
                                    value="{{ old('name') }}" required>
                                @error('name')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group mt-4">
                                <div class="form-check">
                                    <input type="checkbox" name="status" id="status" value="1"
                                        class="form-check-input"
                                        {{ old('status', 1) ? 'checked' : '' }}>
                                    <label for="status" class="form-check-label font-weight-bold">Active</label>
                                </div>
                                @error('status')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group mt-4">
                                <label class="font-weight-bold">Select Role Groups</label>
                                <div class="row">
                                    @foreach(config('constants.role_groups') as $key => $group)
                                        <div class="col-md-3">
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input role-group-toggle" 
                                                    id="group_{{ $key }}" data-group="{{ $key }}">
                                                <label class="form-check-label" for="group_{{ $key }}">{{ $group['name'] }}</label>
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
                                        <tbody id="permissions-container">
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

<script>
    const roleGroups = @json(config('constants.role_groups'));
</script>
@endsection
