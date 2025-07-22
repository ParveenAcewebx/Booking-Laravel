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
                    <div class="col-md-2">
                        <div class="page-header-titles float-right">
                            @can('create users')
                            <a href="{{ route('vendors.add') }}" class="btn btn-primary float-right p-2">Add Vendor</a>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Vendor Table -->
        <div class="row">
            <div class="col-lg-12">
                <div class="card user-profile-list">
                    <div class="card-body">
                        <div class="dt-responsive">
                            <table class="table table-striped nowrap" id="vendors-table" width="100%">
                                <thead>
                                    <tr>
                                        <th style="display: none;">ID</th> <!-- Hidden column -->
                                        <th>Name</th>
                                        <th>Description</th>
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

<script>
    $(function() {
        $('#vendors-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('vendors.list') }}",
            columns: [{
                    data: 'id',
                    name: 'id',
                    visible: false
                }, // ✅ Fixed line
                {
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'description',
                    name: 'description',

                },
                {
                    data: 'status',
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
            ],
            order: [
                [1, 'desc']
            ]
        });

        toastr.options = {
            "closeButton": true,
            "progressBar": true,
            "timeOut": "4000",
            "positionClass": "toast-top-right"
        };

        @if(session('success')) toastr.success("{{ session('success') }}");
        @endif
        @if(session('error')) toastr.error("{{ session('error') }}");
        @endif
        @if(session('info')) toastr.info("{{ session('info') }}");
        @endif
        @if(session('warning')) toastr.warning("{{ session('warning') }}");
        @endif
    });
</script>
@endsection