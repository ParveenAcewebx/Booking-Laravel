@extends('admin.layouts.app')

@section('content')
<div class="pcoded-main-container">
    <div class="pcoded-content">
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <div class="page-header-title">
                            <h5>All Pages</h5>
                        </div>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="feather icon-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="{{ route('page.list') }}">Pages</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('page.list') }}">All Pages</a></li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <div class="page-header-titles float-right">
                            @can('create pages')
                                <a href="{{ route('page.add') }}" class="btn btn-primary btn-sm mr-2 p-2">Add Page</a>
                            @endcan
                            @can('delete pages')
                                <button id="bulkDeleteBtn" class="btn btn-danger btn-sm p-2 bulkDeleteBtn" disabled>Delete</button>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pages Table -->
        <div class="row">
            <div class="col-lg-12">
                <div class="card user-profile-list">
                    <div class="card-body">
                        <div class="dt-responsive">
                            <table class="table table-striped nowrap" id="pages-table" width="100%">
                                <thead>
                                    <tr>
                                        <th style="display: none;">ID</th>
                                        <th><input type="checkbox" id="selectAll"></th>
                                        <th>Title</th>
                                        <th>Created By</th>
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
    // Initialize DataTable
    var table = $('#pages-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('page.list') }}",  // URL that provides pages data
        columns: [
            { data: 'id', name: 'id', visible: false },
            { data: 'checkbox', name: 'checkbox', orderable: false, searchable: false },
            { data: 'title', name: 'title' },
            { data: 'created_by', name: 'created_by' },
            { data: 'created_at', name: 'created_at' },
            { data: 'status', name: 'status', orderable: false, searchable: false },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ],
        order: [[0, 'desc']],
        lengthMenu: [[10,25,50,100],[10,25,50,100]]
    });

    // Toastr setup
    toastr.options = { closeButton: true, progressBar: true, timeOut: 4000, positionClass: "toast-top-right" };
    @if(session('success')) toastr.success("{{ session('success') }}"); @endif
    @if(session('error')) toastr.error("{{ session('error') }}"); @endif

    // Bulk delete setup
    bulkDelete("{{ route('page.bulk-delete') }}");
});
</script>
@endsection
