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
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold text-gray-800">Transactions</h2>
            </div>

            <div class="overflow-x-auto">
                <table id="transaction-table" class="min-w-full border rounded-lg">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-2">ID</th>
                            <th class="px-4 py-2">#ID - Customer Name</th>
                            <th class="px-4 py-2">Amount</th>
                            <th class="px-4 py-2">Payment ID</th>
                            <th class="px-4 py-2">Status</th>
                            <th class="px-4 py-2">Created Date</th>
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
    $(document).ready(function() {

        $('#transaction-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('vendor.transactions') }}",
                type: "GET",
                error: function(xhr) {
                    console.log("ERROR:", xhr.responseText);
                }
            },
            columns: [{
                    data: 'id',
                    name: 'id'
                },
                {
                    data: 'customer_name',
                    name: 'customer_name'
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
                    name: 'status',
                    render: function(data, type, row) {
                        let status = data ? data.toLowerCase() : "";

                        if (status === "success") {
                            return `<span style="color: green; font-weight:600;">
                                        <i class="fa fa-circle" style="font-size:10px; color:green;"></i> success
                                    </span>`;
                        } else if (status === "pending") {
                            return `<span style="color: orange; font-weight:600;">
                                        <i class="fa fa-circle" style="font-size:10px; color:orange;"></i> Pending
                                    </span>`;
                        }
                        return data;
                    }
                },
                {
                    data: 'created_date',
                    name: 'created_date'
                }
            ],
            order: [
                [5, 'desc']
            ]
        });

    });
</script>
@endpush