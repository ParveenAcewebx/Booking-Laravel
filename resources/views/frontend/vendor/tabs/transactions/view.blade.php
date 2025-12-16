@extends('frontend.layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8 text-center">
        <h1 class="text-3xl font-bold text-gray-800">Vendor Dashboard</h1>
        <p class="text-gray-600 mt-2">Transaction Details</p>
    </div>
    <div class="flex gap-6">
        <x-vendor-sidebar />
        <div class="w-3/4 bg-white shadow rounded-2xl p-6">
            <div class="flex justify-between mb-6">
                <h2 class="text-xl font-semibold">
                    Transaction #{{ $transaction->id }}
                </h2>

                <a href="{{ route('vendor.transactions') }}"
                   class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">
                    ← Back
                </a>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-3">
                    <p><strong>Template:</strong> {{ $transaction->bookingTemplate->template_name ?? '-' }}</p>
                    <p><strong>Customer:</strong> {{ $transaction->customer->name ?? '-' }}</p>
                    <p><strong>Email:</strong> {{ $transaction->customer->email ?? '-' }}</p>
                    <p><strong>Payment ID:</strong> {{ $transaction->payment_id ?? '-' }}</p>
                </div>
                <div class="space-y-3">
                    <p><strong>Amount:</strong> {{ $transaction->amount }}</p>
                    <p><strong>Status:</strong> {{ ucfirst($transaction->status) }}</p>
                    <p><strong>Date:</strong> {{ $transaction->created_at->format('d M Y h:i A') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
