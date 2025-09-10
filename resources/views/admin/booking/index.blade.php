@extends('admin.layouts.app')

@section('content')
<div class="pcoded-main-container">
    <div class="pcoded-content">
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-10">
                        <div class="page-header-title">
                            <h5>All Bookings</h5>
                        </div>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="feather icon-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="{{ route('booking.list') }}">Bookings</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('booking.list') }}">All Bookings</a></li>
                        </ul>
                    </div>
                    <div class="col-md-2">
                        <div class="page-header-titles float-right">
                        @can('create bookings')
                            <a href="{{ route('booking.add') }}" class="btn btn-primary btn-sm mr-2 p-2">Add Booking</a>
                            @endcan
                            @can('delete bookings')
                            <button id="bulkBookingsDeleteBtn" class="btn btn-danger btn-sm p-2" disabled>Delete</button>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Filters --}}
        <div class="row">
            <div class="col-lg-12">
                <div class="card user-profile-list">
                    <div class="card-body">
                        <div class="row mb-3 justify-content-end">
                            <div class="col-md-2 col-sm-6">
                                <select id="filter-template" class="form-control select-template-name">
                                    <option value="">-- Select Template --</option>
                                    @foreach($templates as $template)
                                    <option value="{{ $template->id }}">{{ $template->template_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2 col-sm-6">
                                <select id="filter-customer" class="form-control select-users">
                                    <option value="">-- Select Booked By --</option>
                                    @foreach($customers as $customer)
                                    <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-1 col-sm-4">
                                <button id="reset-filters" class="btn btn-primary float-right w-100">Reset</button>
                            </div>
                        </div>

                        <div class="dt-responsive table-responsive">
                            <table id="booking-list-table" class="table table-striped nowrap" width="100%">
                                <thead>
                                    <tr>
                                        <th style="display:none;">ID</th>
                                        <th><input type="checkbox" id="selectAll"></th>
                                        <th>Template Name</th>
                                        <th>Booked By</th>
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

<script>
    $(function() {
        // Initialize DataTable
        let table = $('#booking-list-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('booking.list') }}",
                data: function(d) {
                    d.template_id = $('#filter-template').val();
                    d.customer_id = $('#filter-customer').val();
                }
            },
            columns: [{
                    data: 'id',
                    name: 'id',
                    visible: false
                },
                {
                    data: 'id',
                    render: function(data) {
                        return `<input type="checkbox" class="selectRow" value="${data}">`;
                    },
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'template_name',
                    name: 'template_name'
                },
                {
                    data: 'booked_by',
                    name: 'booked_by'
                },
                {
                    data: 'created_at',
                    name: 'created_at'
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
                [0, 'desc']
            ], // sort by 'created_at' (adjust column index)
            lengthMenu: [
                [10, 25, 50, 100],
                [10, 25, 50, 100]
            ],
        });

        // Filters
        $('#filter-template, #filter-customer').change(function() {
            table.ajax.reload();
        });
        $('#reset-filters').click(function() {
            $('#filter-template').val('').trigger('change');
            $('#filter-customer').val('').trigger('change');
            table.ajax.reload();
        });

        // Toastr notifications
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
        bulkDelete("{{ route('booking.bulk-delete') }}");

    });
</script>
@endsection