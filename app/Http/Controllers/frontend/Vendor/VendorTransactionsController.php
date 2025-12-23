<?php

namespace App\Http\Controllers\frontend\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\Vendor;
use Illuminate\Http\Request;
use DataTables;

class VendorTransactionsController extends Controller
{
    public function index(Request $request)
    {
        $vendor = Vendor::where('email', auth()->user()->email)->firstOrFail();

        if ($request->expectsJson()) {

            $query = Transaction::select('transactions.*')
                ->with([
                    'customer:id,name',
                    'bookingTemplate:id,template_name'
                ])
                ->where('vendor_id', $vendor->id);

                return DataTables::eloquent($query)
                ->addColumn('checkbox', function ($row) {
                    return '<input type="checkbox" class="selectRow" value="'.$row->id.'">';
                })
                ->addColumn('template_name', function ($row) {
                    return $row->bookingTemplate
                        ? preg_replace('/-\d+$/', '', $row->bookingTemplate->template_name)
                        : '';
                })
                ->addColumn('customer_display', function ($row) {
                    return $row->customer ? $row->customer->name : '';
                })
                ->addColumn('created_date', function ($row) {
                    return $row->created_at->format('d M Y h:i A');
                })
                ->addColumn('action', function ($row) {
                $viewUrl   = route('vendor.transaction.view', $row->id);
                $deleteUrl = route('vendor.transaction.delete', $row->id);
                $refundUrl = route('stripe.refund', $row->id);
                return '
                    <div class="flex gap-2 items-center">
                        <a href="'.$viewUrl.'"
                        class="inline-flex items-center justify-center w-9 h-9 rounded-full bg-blue-600 text-white hover:bg-blue-700"
                        title="View Transaction">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5
                                        s8.268 2.943 9.542 7
                                        c-1.274 4.057-5.065 7-9.542 7
                                        s-8.268-2.943-9.542-7z" />
                            </svg>
                        </a>
                        <form action="'.$deleteUrl.'" method="POST"
                            onsubmit="return confirm(\'Are you sure?\');">
                            '.csrf_field().method_field('DELETE').'
                            <button type="submit"
                                class="px-3 py-1 text-sm bg-red-500 text-white rounded hover:bg-red-600">
                                Delete
                            </button>
                        </form>
                        <form id="refundtransaction-'.$row->id.'"
                            action="'.$refundUrl.'"
                            method="POST"
                            style="display:inline-block;">
                            '.csrf_field().'
                            <button type="button"
                                onclick="refundtransaction('.$row->id.')"
                                class="px-3 py-1 text-sm bg-yellow-500 text-white rounded hover:bg-yellow-600">
                                Refund
                            </button>
                        </form>
                    </div>
                ';
            })
                ->rawColumns(['checkbox','action'])
                ->make(true);
        }
        return view('frontend.vendor.tabs.transactions.transaction');
    }
    public function view($id)
    {
        $vendor = Vendor::where('email', auth()->user()->email)->firstOrFail();

        $transaction = Transaction::with([
                'customer:id,name,email',
                'bookingTemplate:id,template_name'
            ])
            ->where('vendor_id', $vendor->id)
            ->findOrFail($id);

        return view('frontend.vendor.tabs.transactions.view', compact('transaction'));
    }
    public function delete($id)
    {
        $vendor = Vendor::where('email', auth()->user()->email)->firstOrFail();

        Transaction::where('vendor_id', $vendor->id)
            ->where('id', $id)
            ->delete();

        return redirect()
            ->route('vendor.transactions')
            ->with('success', 'Transaction deleted successfully.');
    }
    public function bulkDelete(Request $request)
    {
        $vendor = Vendor::where('email', auth()->user()->email)->firstOrFail();

        Transaction::where('vendor_id', $vendor->id)
            ->whereIn('id', $request->ids)
            ->delete();

        return response()->json([
            'success' => true,
            'message' => 'Selected transactions deleted successfully.'
        ]);
    }
}
