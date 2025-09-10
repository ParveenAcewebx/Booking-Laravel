@extends('admin.layouts.app')

@section('content')
<div class="pcoded-main-container">
    <div class="pcoded-content">

        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-10">
                        <div class="page-header-title">
                            <h5>All Categories</h5>
                        </div>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('dashboard') }}"><i class="feather icon-home"></i></a>
                            </li>
                            <li class="breadcrumb-item"><a href="{{ route('category.list') }}">Categories</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('category.list') }}">All Categories</a></li>
                        </ul>
                    </div>

                    <div class="col-md-2">
                        <div class="page-header-titles float-right">
                            @can('create categories')
                            <a href="{{ route('category.create') }}" class="btn btn-primary btn-sm mr-2 p-2">Add Category</a>
                            @endcan
                            @can('delete categories')
                            <button id="bulkCategoryDeleteBtn" class="btn btn-danger btn-sm p-2" disabled>Delete</button>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Categories Table -->
        <div class="row">
            <div class="col-lg-12">
                <div class="card user-profile-list">
                    <div class="card-body">
                        <div class="dt-responsive">
                            <table class="table table-striped nowrap" id="categories-table" width="100%">
                                <thead>
                                    <tr>
                                        <th style="display:none;">ID</th>
                                        <th><input type="checkbox" id="selectAll"></th>
                                        <th>Category Name</th>
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

<meta name="csrf-token" content="{{ csrf_token() }}">

<script type="text/javascript">
$(function() {

    // Initialize DataTable
    var table = $('#categories-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('category.list') }}",
        columns: [
            { data: 'id', name: 'id', visible: false },
            { data: null, name: 'select', orderable: false, searchable: false,
              render: function(data, type, row) {
                  return '<input type="checkbox" class="selectRow" value="' + row.id + '">';
              }
            },
            { data: 'category_name', name: 'category_name' },
            { data: 'created_at', name: 'created_at' },
            { data: 'status', name: 'status', orderable: false, searchable: false },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ],
        order: [[0,'desc']], // Sort by ID descending
        lengthMenu: [[10,25,50,100],[10,25,50,100]]
    });

    // Toastr setup
    toastr.options = { closeButton: true, progressBar: true, timeOut: 4000, positionClass: "toast-top-right" };

    // Session messages
    @if(session('success')) toastr.success("{{ session('success') }}"); @endif
    @if(session('error')) toastr.error("{{ session('error') }}"); @endif
    // Initialize bulk delete
    bulkDelete("{{ route('category.bulk-delete') }}");

});
</script>
@endsection
