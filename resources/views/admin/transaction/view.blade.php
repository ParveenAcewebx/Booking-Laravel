@extends('admin.layouts.app')
@section('content')
<div class="pcoded-main-container">
    <div class="pcoded-content">
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <div class="page-header-title">
                            <h5 class="m-b-10">Booking Transaction #{{$transaction->id}}</h5>
                        </div>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('dashboard') }}">
                                    <i class="feather icon-home"></i>
                                </a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="#!">Booking</a>
                            </li>
                            <li class="breadcrumb-item"><a href="#!">Booking Transaction#{{$transaction->id}}</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <h5>Transaction Details</h5>
                <hr>
                <p><strong>Transaction ID:</strong> {{ $transaction->id }}</p>
                <p><strong>Customer:</strong> {{ $transaction->customer->name ?? 'N/A' }}</p>
                <p><strong>Amount:</strong> {{ $transaction->amount }}</p>
                <p><strong>Payment ID:</strong> {{ $transaction->payment_id }}</p>
                <p><strong>Status:</strong> {{ ucfirst($transaction->status) }}</p>
                <p><strong>Created At:</strong> {{ $transaction->created_at->format('d M Y h:i A') }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
