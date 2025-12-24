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
                </div>
            </div>
        </div>
        <div class="row mb-3 justify-content-end">
            <div class="col-md-2">
                <select id="filter-customer" class="form-control">
                    <option value="">Select Customer</option>
                    @foreach($customers as $customer)
                    <option value="{{ $customer->id }}">
                        {{ $customer->name }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select id="filter-status" class="form-control">
                    <option value="">Select Status</option>
                    <option value="completed">Completed</option>
                    <option value="pending">Pending</option>
                    <option value="refunded">Refunded</option>
                </select>
            </div>
            <div class="col-md-2">
                <input type="date" id="filter-date" class="form-control">
            </div>
            <div class="col-md-1">
                <button id="reset-filters" class="btn btn-primary w-100">Reset</button>
            </div>
            <div class="export_booking d-flex justify-content-end">
                <a href="{{route('export.booking-transaction.excel')}}" class="btn btn-primary btn-sm mr-3 p-2 d-flex align-items-center">Export To Excel</a>
            </div>
        </div>
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
                                        <th>Template</th>
                                        <th>Customer Name</th>
                                        <th>Amount</th>
                                        <th>Payment ID</th>
                                        <th>Status</th>
                                        <th>Created Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
$(document).ready(function () {
    let table = $('#transaction-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('transaction') }}",
            type: "GET",
            data: function (d) {
                d.customer_id = $('#filter-customer').val();
                d.status      = $('#filter-status').val();
                d.start_date  = $('#filter-date').val();
            }
        },
        columns: [
            { data: 'id', visible: false },
            { data: 'checkbox', orderable: false, searchable: false },
            { data: 'template_name' },
            { data: 'customer_display' },
            { data: 'total_balance',
                render: function (data, type, row) {
                    return '$' + (data);
                }
            },
            { data: 'payment_id' },
            { data: 'status',
                name: 'status',
                render: function (data, type, row) {
                    let status = data ? data.toLowerCase() : "";
                    if (status === "completed") {
                        return `
                            <span style="color: green; font-weight:600;">
                                <i class="fa fa-circle" style="font-size:10px; color:green;"></i>
                                Completed
                            </span>`;
                    }
                    if (status === "pending") {
                        return `
                            <span style="color: orange; font-weight:600;">
                                <i class="fa fa-circle" style="font-size:10px; color:orange;"></i>
                                Pending
                            </span>`;
                    }
                   if (status === "refunded" || status === "partial_refund") {
                            let refunded  = parseFloat(row.refunded_amount || 0);
                            let remaining = parseFloat(row.amount || 0);
                            if (remaining <= 0) {
                                return `
                                    <span style="color:red;font-weight:600;">
                                        <i class="fa fa-circle" style="font-size:10px;color:red;"></i>
                                        Refunded
                                    </span>`;
                            }

                            if (refunded > 0) {
                                return `
                                    <span style="color:#c0392b;font-weight:600;">
                                        <i class="fa fa-circle" style="font-size:10px;color:#c0392b;"></i>
                                        Refund (Partial - $${refunded})
                                    </span>`;
                            }
                        }
                    return data;
                }
            },
            { data: 'created_date' },
            { data: 'action', orderable: false, searchable: false }
        ],
        order: [[0, 'desc']]
    });

    $('#filter-customer, #filter-status, #filter-date').on('change', function () {
        table.ajax.reload();
    });

    $('#reset-filters').on('click', function () {
        $('#filter-customer').val('');
        $('#filter-status').val('');
        $('#filter-date').val('');
        table.ajax.reload();
    });
});
</script>
@endsection
