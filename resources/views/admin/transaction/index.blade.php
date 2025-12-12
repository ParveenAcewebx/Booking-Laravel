@extends('admin.layouts.app')
@section('content')
<div class="pcoded-main-container">
    <div class="pcoded-content">
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-10">
                        <div class="page-header-title">
                            <h5>All Transactions</h5>
                        </div>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('dashboard') }}"><i class="feather icon-home"></i></a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{ route('transaction') }}">Transactions</a>
                            </li>
                            <li class="breadcrumb-item">All Transactions</li>
                        </ul>
                    </div>

                    <div class="col-md-2">
                        <div class="page-header-titles float-right">
                            @can('delete transaction')
                            <button id="bulkDeleteBtn" class="btn btn-danger btn-sm p-2" disabled>
                                Delete
                            </button>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Transactions Table -->
        <div class="row">
            <div class="col-lg-12">
                <div class="card user-profile-list">
                    <div class="card-body">
                        <div class="dt-responsive">
                            <table class="table table-striped nowrap" id="transaction-table" width="100%">
                                <thead>
                                    <tr>
                                        <th style="display:none;">ID</th>
                                        <th><input type="checkbox" id="selectAll"></th>
                                        <th>Booking ID</th>
                                        <th>Customer Name</th>
                                        <th>Vendor Name</th>
                                        <th>Amount</th>
                                        <th>Payment ID</th>
                                        <th>Status</th>
                                        <th>Created Date</th>
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
        var table = $('#transaction-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('transaction') }}",
            columns: [{
                    data: 'id',
                    name: 'id',
                    visible: false
                },
                {
                    data: 'checkbox',
                    name: 'checkbox',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'booking_id',
                    name: 'booking_id'
                },
                {
                    data: 'customer_display',
                    name: 'customer_display'
                },
                {
                    data: 'vendor_display',
                    name: 'vendor_display'
                },
                {
                    data: 'amount',
                    name: 'amount'
                },
                {
                    data: 'payment_id',
                    name: 'payment_id'
                },
                {
                    data: 'status',
                    name: 'status'
                },
                {
                    data: 'created_date',
                    name: 'created_date'
                },
            ],
            order: [
                [0, 'desc']
            ],
            lengthMenu: [
                [10, 25, 50, 100],
                [10, 25, 50, 100]
            ]
        });
        bulkDelete("{{ route('transaction.bulk-delete') }}");
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
    });
</script>
@endsection