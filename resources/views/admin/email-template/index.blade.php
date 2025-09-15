@extends('admin.layouts.app')

@section('content')
<div class="pcoded-main-container">
    <div class="pcoded-content">
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-10">
                        <div class="page-header-title">
                            <h5>All Email</h5>
                        </div>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="feather icon-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="{{ route('emails.list') }}">Email</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('emails.list') }}">All Email</a></li>
                        </ul>
                    </div>
                    <div class="col-md-2">
                        <div class="page-header-titles float-right">
                        @can('create emails')
                        <a href="{{ route('emails.create') }}" class="btn btn-primary btn-sm mr-2 p-2">Add Email</a>
                        @endcan
                        @can('delete emails')
                        <button id="bulkEmailsDeleteBtn" class="btn btn-danger btn-sm p-2 bulkDeleteBtn" disabled>Delete</button>
                        @endcan
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="row">
            <div class="col-lg-12">
                <div class="card user-profile-list">
                    <div class="card-body">
                        <div class="dt-responsive">
                            <table class="table table-striped nowrap" id="emailTemplatesTable" width="100%">
                                <thead>
                                    <tr>
                                        <th><input type="checkbox" id="selectAll"></th>
                                        <th style="display:none;">ID</th>
                                        <th>Title</th>
                                        <th>Slug</th>
                                        <th>Subject</th>
                                        <th>Macro</th>
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
    $(document).ready(function() {
        let table = $('#emailTemplatesTable').DataTable({
            processing: true,
            serverSide: true,
            autoWidth: false,
            scrollX: true,
            ajax: "{{ route('emails.list') }}",
            order: [[1, 'desc']],
            columns: [
                { data: 'checkbox', orderable: false, searchable: false, width: "5%" },
                { data: 'id', visible: false },
                { data: 'title', name: 'title', width: "15%" },
                { data: 'slug', name: 'slug', width: "15%" },
                { 
                    data: 'subject',
                    name: 'subject',
                    width: "25%",
                    render: function(data) {
                        return `<div style="max-width:390px; white-space:normal; word-break:break-word;">${data ?? ''}</div>`;
                    }
                },
                { 
                    data: 'macro',
                    name: 'macro',
                    width: "15%",
                    render: function(data) {
                        return `<div style="max-width:190px; white-space:normal; word-break:break-word;">${data ?? ''}</div>`;
                    }
                },
                { 
                    data: 'status_label',
                    name: 'status',
                    orderable: false,
                    searchable: false,
                    width: "10%",
                    render: function(data) {
                        return `<div style="white-space:nowrap;">${data ?? ''}</div>`;
                    }
                },
                { 
                    data: 'action',
                    orderable: false,
                    searchable: false,
                    width: "10%",
                    render: function(data) {
                        return `<div style="white-space:nowrap;">${data ?? ''}</div>`;
                    }
                }
            ]
        });

        toastr.options = { closeButton: true, progressBar: true, timeOut: 4000, positionClass: "toast-top-right" };

        @if(session('success')) toastr.success("{{ session('success') }}"); @endif
        @if(session('error')) toastr.error("{{ session('error') }}"); @endif

        bulkDelete("{{ route('emails.bulk-delete') }}");
    });
</script>

@endsection
