@extends('admin.layouts.app')

@section('content')
<div class="pcoded-main-container">
    <div class="pcoded-content">

        <!-- Page Header -->
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-10">
                        <div class="page-header-title">
                            <h5>All Staff</h5>
                        </div>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="feather icon-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="{{ route('staff.list') }}">Staffs</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('staff.list') }}">All Staff</a></li>
                        </ul>
                    </div>
                    <div class="col-md-2">
                        <div class="page-header-titles float-right">
                        @can('create staffs')
                            <a href="{{ route('staff.create') }}" class="btn btn-primary btn-sm p-2">Add Staff</a>
                        @endcan
                        @can('delete staffs')
                            <button id="bulkStaffsDeleteBtn" class="btn btn-danger btn-sm p-2" disabled>Delete</button>
                        @endcan
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Staff Table -->
        <div class="row">
            <div class="col-lg-12">
                <div class="card user-profile-list">
                    <div class="card-body">
                        <div class="dt-responsive">
                            <table class="table table-striped nowrap" id="staff-table" width="100%">
                                <thead>
                                    <tr>
                                        <th><input type="checkbox" id="selectAll"></th>
                                        <th style="display:none;">ID</th>
                                        <th>Name</th>
                                        <th>Services</th>
                                        <th>Created Date</th>
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
    var table = $('#staff-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('staff.list') }}",
        columns: [
            { data: 'checkbox', name: 'checkbox', orderable: false, searchable: false },
            { data: 'id', name: 'users.id', visible: false },
            { data: 'name', name: 'users.name' },
            { data: 'services', name: 'services', orderable: false, searchable: false },
            { data: 'created_at', name: 'users.created_at' },
            { data: 'status', name: 'users.status', orderable: false, searchable: false },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ],
        order: [[1, 'desc']]
    });

    // Toastr notifications
    toastr.options = {
        "closeButton": true,
        "progressBar": true,
        "timeOut": "4000",
        "positionClass": "toast-top-right"
    };

    @if(session('success')) toastr.success("{{ session('success') }}"); @endif
    @if(session('error')) toastr.error("{{ session('error') }}"); @endif

    bulkDelete("{{ route('staff.bulk-delete') }}");
});
</script>
@endsection
