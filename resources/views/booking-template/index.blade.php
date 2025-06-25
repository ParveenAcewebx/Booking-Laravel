@extends('layouts.app')

@section('content')

<div class="pcoded-main-container">
    <div class="pcoded-content">
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-10">
                        <div class="page-header-title">
                            <h5>All Booking Templates</h5>
                        </div>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="feather icon-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="{{ route('template.list') }}">Booking Template</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('template.list') }}">All Booking Templates</a></li>
                        </ul>
                    </div>
                    <div class="col-md-2">
                        <div class="page-header-titles float-right">
                            @can('create forms')
                                <a href="{{ route('template.add') }}" class="btn btn-primary float-right p-2">Add Form</a>
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
                            <table class="table table-striped nowrap" id="booking-templates-table" width="100%">
                                <thead>
                                    <tr>
                                        <th style="display:none;">ID</th> {{-- Hidden but sortable --}}
                                        <th>Name</th>
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
        $('#booking-templates-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('template.list') }}",
            columns: [
                { data: 'id', name: 'id', visible: false }, // 👈 required for sorting DESC
                { data: 'template_name', name: 'template_name', orderable: true, searchable: true }, 
                { data: 'created_by', name: 'created_by' },
                { data: 'created_at', name: 'created_at' },
                { data: 'status', name: 'status', orderable: false, searchable: false },
                { data: 'action', name: 'action', orderable: false, searchable: false },
            ],
            order: [[0, 'desc']], // 👈 sort by id (hidden)
            lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
        });
    });

    document.addEventListener("DOMContentLoaded", function() {
        @if(session('success'))
        swal({
            title: "Success!",
            text: "{{ session('success') }}",
            icon: "success",
            buttons: "OK"
        });
        @endif

        @if(session('error'))
        swal({
            title: "Error!",
            text: "{{ session('error') }}",
            icon: "error",
            button: "OK"
        });
        @endif
    });
</script>

@endsection
