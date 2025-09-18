@extends('admin.layouts.app')

@section('content')
<div class="pcoded-main-container">
    <div class="pcoded-content">
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-10">
                        <div class="page-header-title">
                            <h5>All Enquiries</h5>
                        </div>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="feather icon-home"></i></a></li>
                            <li class="breadcrumb-item">Enquiries</li>
                            <li class="breadcrumb-item">All Enquiries</li>
                        </ul>
                    </div>
                    <div class="col-md-2">
                        <div class="page-header-titles float-right">
                            @can('delete enquires')
                                <button id="bulkDeleteBtn" class="btn btn-danger btn-sm p-2 bulkDeleteBtn" disabled>Delete</button>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Enquiries Table -->
        <div class="row">
            <div class="col-lg-12">
                <div class="card user-profile-list">
                    <div class="card-body">
                        <div class="dt-responsive">
                            <table class="table table-striped nowrap" id="enquiries-table" width="100%">
                                <thead>
                                    <tr>
                                        <th style="display: none;">ID</th>
                                        <th><input type="checkbox" id="selectAll"></th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Created Date</th>
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
    var table = $('#enquiries-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('enquiry.list') }}",
        columns: [
            { data: 'id', name: 'id', visible: false },
            { data: 'checkbox', name: 'checkbox', orderable: false, searchable: false },
            { data: 'name', name: 'name' },
            { data: 'email', name: 'email' },
            { data: 'phone', name: 'phone' },
            { data: 'created_at', name: 'created_at' },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ],
        order: [[0, 'desc']],
        lengthMenu: [[10,25,50,100],[10,25,50,100]],
        createdRow: function(row, data, dataIndex) {
            if (data.read == 0) {
                $(row).css('background-color', '#d4edda'); // light green
            }
        }
    });

    bulkDelete("{{ route('enquiry.bulk-delete') }}");

    toastr.options = { closeButton: true, progressBar: true, timeOut: 4000, positionClass: "toast-top-right" };
    @if(session('success')) toastr.success("{{ session('success') }}"); @endif
    @if(session('error')) toastr.error("{{ session('error') }}"); @endif
});
</script>
@endsection
