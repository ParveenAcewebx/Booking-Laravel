<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;
use DataTables;

class TransactionController extends Controller
{
        public function index(Request $request)
    {
        if ($request->ajax()) {

            $data = Transaction::with([
                'vendor:id,name',
                'customer:id,name'
            ])->latest();
            return DataTables::of($data)
                ->addColumn('checkbox', function ($row) {
                    return '<input type="checkbox" class="row-checkbox" value="' . $row->id . '">';
                })
                ->addColumn('customer_display', function ($row) {
                    return $row->customer
                        ? '#' . $row->customer_id . ' - ' . $row->customer->name
                        : 'N/A';
                })
                ->addColumn('vendor_display', function ($row) {
                    return $row->vendor
                        ? '#' . $row->vendor_id . ' - ' . $row->vendor->name
                        : 'N/A';
                })
                ->addColumn('created_date', function ($row) {
                    return $row->created_at->format('d M Y h:i A');
                })
                ->rawColumns(['checkbox'])
                ->make(true);
        }
        return view('admin.transaction.index');
    }
}
