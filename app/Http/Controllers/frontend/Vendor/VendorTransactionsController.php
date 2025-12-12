<?php

namespace App\Http\Controllers\frontend\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\Vendor;
use Illuminate\Http\Request;

class VendorTransactionsController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $vendor = Vendor::where('email', $user->email)->first();
        $vendorId = $vendor->id;
        $query = Transaction::with([
            'booking.customer:id,name,email',
        ])->where('vendor_id', $vendorId);
        if ($request->ajax()) {
            return datatables()->of($query)
                ->addColumn('customer_name', function ($row) {
                    return $row->booking->customer->name ?? 'N/A';
                })
                ->addColumn('created_date', function ($row) {
                    return $row->created_at->format('d M Y h:i A');
                })
                ->make(true);
        }
        return view('frontend.vendor.tabs.transactions.transaction');
    }
}
