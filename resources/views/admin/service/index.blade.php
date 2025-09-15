@extends('admin.layouts.app')

@section('content')
<div class="pcoded-main-container">
    <div class="pcoded-content">
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-10">
                        <div class="page-header-title">
                            <h5 class="m-b-10">All Services</h5>
                        </div>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('dashboard') }}">
                                    <i class="feather icon-home"></i>
                                </a>
                            </li>
                            <li class="breadcrumb-item active">Services</li>
                            <li class="breadcrumb-item"><a href="{{ route('service.list') }}">All Services</a></li>
                        </ul>
                    </div>
                    <div class="col-md-2">
                        <div class="page-header-titles float-right">
                            @can('create services')
                            <a href="{{ route('service.add') }}" class="btn btn-primary btn-sm mr-2 p-2">Add Service</a>
                            @endcan
                            @can('delete services')
                            <button id="bulkServiceDeleteBtn" class="btn btn-danger btn-sm p-2 bulkDeleteBtn" disabled>Delete</button>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Services Table -->
        <div class="row">
            <div class="col-lg-12">
                <div class="card user-profile-list">
                    <div class="card-body">
                        <div class="dt-responsive table-responsive">
                            <table id="service-list-table" class="table table-striped nowrap" width="100%">
                                <thead>
                                    <tr>
                                        <th><input type="checkbox" id="selectAll"></th>
                                        <th>ID</th> {{-- Hidden for sorting --}}
                                        <th>Name</th>
                                        <th>Created At</th>
                                        <th>Status</th>
                                        <th>Action</th>
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

<script>
    $(function() {
        var table = $('#service-list-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('service.list') }}",
            columns: [{
                    data: 'id',
                    name: 'id',
                    render: function(data) {
                        return `<input type="checkbox" class="selectRow" value="${data}">`;
                    },
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
                    data: 'created_at',
                    name: 'created_at'
                },
                {
                    data: 'status',
                    name: 'status'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                }
            ],
            order: [
                [1, 'desc']
            ]
        });

        toastr.options = {
            closeButton: true,
            progressBar: true,
            timeOut: 4000,
            positionClass: "toast-top-right"
        };

        @if(session('success')) toastr.success("{{ session('success') }}");
        @endif
        @if(session('error')) toastr.error("{{ session('error') }}");
        @endif

        bulkDelete("{{ route('service.bulk-delete') }}");

    });
</script>
@endsection