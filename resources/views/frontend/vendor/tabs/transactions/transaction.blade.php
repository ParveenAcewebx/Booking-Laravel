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
                            <th>Refund Amount</th>
                            <th>Payment ID</th>
                            <th>Status</th>
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
            { data: 'refunded_amount' },
            { data: 'payment_id' },
            {
                data: 'status',
                render: function(data) {
                    let s = (data || '').toLowerCase();
                    if (s === 'completed') return '<span class="text-green-600 font-semibold">● Completed</span>';
                    if (s === 'pending') return '<span class="text-yellow-600 font-semibold">● Pending</span>';
                    if (s === 'refunded') return '<span class="text-yellow-600 font-semibold">● Refunded</span>';
                    return '<span class="text-red-600 font-semibold">● Failed</span>';
                }
            },
            { data: 'action', orderable:false, searchable:false }
        ],
        order: [[0,'desc']]
    });

    $('#selectAll').on('click', function () {
        $('.selectRow').prop('checked', this.checked);
    });

});
function refundtransaction(id) {

    swal({
        title: "Are you sure?",
        text: "Once refunded, you will not be able to recover this transaction!",
        icon: "warning",
        buttons: {
            partially: { text: "Partially", value: "partially" },
            confirm: { text: "Yes, Refund Fully Payment!", value: "full" },
            cancel: true
        },
        dangerMode: true,
    }).then((action) => {
        if (!action) return;

        const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute("content") || "";

        const doRefund = (formData) => {
            const form = document.getElementById("refundtransaction-" + id);

            fetch(form.action, {
                method: "POST",
                body: formData,
                headers: {
                    "X-Requested-With": "XMLHttpRequest",
                    "X-CSRF-TOKEN": csrf,
                },
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    swal("Refund successful!", { icon: "success" })
                        .then(() => location.reload());
                } else {
                    swal(data.message || "Refund failed", { icon: "error" });
                }
            })
            .catch(() => swal("Something went wrong!", { icon: "error" }));
        };

        const form = document.getElementById("refundtransaction-" + id);
        const formData = new FormData(form);

        if (action === "full") {
            formData.append("refund_type", "full");
            doRefund(formData);
        }

        if (action === "partially") {
            swal("Enter refund amount:", {
                content: "input",
            }).then((amount) => {
                if (!amount || amount <= 0) {
                    swal("Invalid amount", { icon: "error" });
                    return;
                }

                formData.append("refund_type", "partial");
                formData.append("refund_amount", amount);
                doRefund(formData);
            });
        }
    });
}
</script>
@endpush
