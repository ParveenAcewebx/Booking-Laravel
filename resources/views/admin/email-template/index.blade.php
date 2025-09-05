@extends('admin.layouts.app')

@section('content')
<div class="pcoded-main-container">
    <div class="pcoded-content">
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-10">
                        <div class="page-header-title">
                            <h5>All Email Templates</h5>
                        </div>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="feather icon-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="#">Email Templates</a></li>
                            <li class="breadcrumb-item"><a href="#">All Email Templates</a></li>
                        </ul>
                    </div>
                    <div class="col-md-2">
                        <div class="page-header-titles float-right">
                            <a href="#" class="btn btn-primary float-right p-2">Add New Email Template</a>
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
                                        <th style="display:none;">ID</th> {{-- Hidden but sortable --}}
                                        <th>Title</th>
                                        <th>Slug</th>
                                        <th>Subject</th>
                                        <th>Email Content</th>
                                        <th>Dummy Template</th>
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
        $('#emailTemplatesTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('emails.list') }}",
            columns: [
                {
                    data: 'id',
                    name: 'id'
                },{
                    data: 'title',
                    name: 'title'
                },
                {
                    data: 'slug',
                    name: 'slug'
                },
                {
                    data: 'subject',
                    name: 'subject'
                },
                {
                    data: 'email_content',
                    name: 'email_content'
                },
                {
                    data: 'dummy_template',
                    name: 'dummy_template'
                },
                {
                    data: 'status_label',
                    name: 'status',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                }
            ]
        });

    });
</script>
@endsection