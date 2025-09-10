@extends('admin.layouts.app')

@section('content')
<div class="pcoded-main-container">
    <div class="pcoded-content">
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-10">
                        <div class="page-header-title">
                            <h5>All Vendors</h5>
                        </div>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="feather icon-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="{{ route('vendors.list') }}">Vendors</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('vendors.list') }}">All Vendors</a></li>
                        </ul>
                    </div>
                    <div class="col-md-2 text-right">
                        @can('create users')
                            <a href="{{ route('vendors.add') }}" class="btn btn-primary btn-sm mr-2">Add Vendor</a>
                        @endcan
                        @can('delete users')
                            <button id="bulkVendorsDeleteBtn" class="btn btn-danger btn-sm" disabled>Delete</button>
                        @endcan
                    </div>
                </div>
            </div>
        </div>

        <!-- Vendors Table -->
        <div class="row">
            <div class="col-lg-12">
                <div class="card user-profile-list">
                    <div class="card-body">
                        <div class="dt-responsive">
                            <table class="table table-striped nowrap" id="vendors-table" width="100%">
                                <thead>
                                    <tr>
                                        <th style="display: none;">ID</th>
                                        <th><input type="checkbox" id="selectAll"></th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Services</th>
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

    // Initialize DataTable
    var table = $('#vendors-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('vendors.list') }}",
        columns: [
            { data: 'id', name: 'id', visible: false },
            { data: null, name: 'select', orderable: false, searchable: false,
              render: function(data, type, row) {
                  return '<input type="checkbox" class="selectRow" value="' + row.id + '">';
              }
            },
            { data: 'name', name: 'vendors.name' },
            { data: 'email', name: 'vendors.email' },
            { data: 'services', name: 'services', orderable: false, searchable: false },
            { data: 'status', name: 'vendors.status', orderable: false, searchable: false },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ],
        order: [[0, 'desc']],
        lengthMenu: [[10,25,50,100],[10,25,50,100]]
    });

    // Toastr Notifications
    toastr.options = { closeButton: true, progressBar: true, timeOut: 4000, positionClass: "toast-top-right" };

    @if(session('success')) toastr.success("{{ session('success') }}"); @endif
    @if(session('error')) toastr.error("{{ session('error') }}"); @endif
    // Bulk delete handler
    bulkDelete("{{ route('vendors.bulk-delete') }}");
});
</script>
@endsection
