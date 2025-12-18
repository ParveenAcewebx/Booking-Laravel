@extends('frontend.layouts.app')
@section('content')
<div class="mt-8 text-center">
    <h1 class="text-3xl font-bold text-gray-800">Vendor Dashboard</h1>
    <p class="text-gray-600 mt-2">Transaction Details</p>
</div>
<div class="container mx-auto px-4 py-8" x-data="{ tab: 'transactions' }">
    <div class="flex gap-6">
        <x-vendor-sidebar />
        <div class="w-3/4 bg-white shadow rounded-2xl p-6">
            <div class="space-y-4 booking-section">
                <section class="">
                    <div class="">
                        <div class="border-b border-gray-200 pb-4 mb-6">
                            <div class="flex flex-col gap-2">
                                <div class="flex justify-between gap-3 items-center">
                                    <h5 class="text-xl font-semibold text-gray-800">Transaction #{{ $transaction->id }}</h5>
                                      <a href="{{ route('vendor.transactions') }}" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Cancel</a>
                                </div>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="bg-white border rounded-lg shadow">
                                <div class="border-b px-6 py-4">
                                    <h5 class="text-lg font-medium text-gray-800">Transactions Information</h5>
                                </div>
                                <div class="p-6">
                                    <div class="space-y-6">
                                        <div>
                                            <h6 class="text-blue-600 font-semibold mb-2">Transactions Information</h6>
                                            <div class="space-y-2">
                                                <div>
                                                    <strong>Template:</strong> <span>{{ $transaction->bookingTemplate->template_name}}</span>
                                                </div>
                                                <div>
                                                    <strong>Customer:</strong> <span>{{ $transaction->customer->name}}</span>
                                                </div>
                                                <div>
                                                    <strong>Email:</strong>
                                                    <a href="mailto:{{ $transaction->customer->email}}" class="text-blue-500 underline">{{ $transaction->customer->email}}</a>
                                                </div>
                                                <div>
                                                    <strong>Payment ID:</strong> <span> {{ $transaction->payment_id}}</span>
                                                </div>
                                                <div>
                                                    <strong>Amount</strong> <span>  {{ $transaction->amount }}</span>
                                                </div>
                                                <div>
                                                    <p><strong>Status:</strong> {{ ucfirst($transaction->status) }}</p>
                                                </div>
                                                <div>
                                                   <p><strong>Date:</strong> {{ $transaction->created_at->format('d M Y h:i A') }}</p>
                                                </div>
                                            </div>              
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
</div>
@endsection
