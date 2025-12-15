<?php

namespace App\Http\Controllers\export;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Vendor;
use App\Exports\BookingsTransactionExport;
use Maatwebsite\Excel\Facades\Excel;

class ExportBookingTransactionController extends Controller
{
    public function exportTransaction()
    {
        $transactions = Transaction::all();

        $transactionData = [];

        foreach ($transactions as $transaction) {

            // Get customer name
            $customerName = User::where('id', $transaction->customer_id)
                ->value('name');

            // Get vendor name
            $vendorName = Vendor::where('id', $transaction->vendor_id)
                ->value('name');

            $transactionData[] = [
                $transaction->id,
                $transaction->booking_id,
                $customerName,
                $vendorName,
                $transaction->payment_id,
                $transaction->status,
                $transaction->amount,
                $transaction->currency,
                $transaction->created_at?->format('Y-m-d H:i:s'),
            ];
        }

        return Excel::download(
            new BookingsTransactionExport($transactionData),
            'transactions.xlsx'
        );
    }
}
