@extends('frontend.layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8 text-center">
        <h1 class="text-3xl font-bold text-gray-800">Vendor Dashboard</h1>
        <p class="text-gray-600 mt-2">Manage your bookings, payments & transactions</p>
    </div>

    <div class="flex gap-6">
        <x-vendor-sidebar />

        <div class="w-3/4 bg-white shadow rounded-2xl p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Transactions</h2>

            <div class="overflow-x-auto">
                <table id="transaction-table" class="min-w-full border rounded-lg">
                    <thead class="bg-gray-100">
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
@endsection

@push('scripts')
<script>
$(function () {

    let table = $('#transaction-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('vendor.transactions') }}",
        columns: [
            { data: 'id', visible: false },
            { data: 'checkbox', orderable:false, searchable:false },
            { data: 'template_name' },
            { data: 'customer_display' },
            { data: 'amount' },
            { data: 'payment_id' },
            {
                data: 'status',
                render: function(data) {
                    let s = (data || '').toLowerCase();
                    if (s === 'success') return '<span class="text-green-600 font-semibold">● Success</span>';
                    if (s === 'pending') return '<span class="text-yellow-600 font-semibold">● Pending</span>';
                    return '<span class="text-red-600 font-semibold">● Failed</span>';
                }
            },
            { data: 'created_date' },
            { data: 'action', orderable:false, searchable:false }
        ],
        order: [[0,'desc']]
    });

    $('#selectAll').on('click', function () {
        $('.selectRow').prop('checked', this.checked);
    });

});
</script>
@endpush
