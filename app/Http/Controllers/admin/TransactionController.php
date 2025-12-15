<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use DataTables;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Transaction::query()
                ->with(['customer:id,name', 'vendor:id,name', 'bookingTemplate:id,template_name']);
            if ($request->customer_id) {
                $query->where('customer_id', $request->customer_id);
            }
            if ($request->status) {
                $query->where('status', $request->status);
            }
            if ($request->start_date) {
                $query->whereDate('created_at', $request->start_date);
            }
            return DataTables::eloquent($query)
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
                ->addColumn('template_name', function ($row) {
                return $row->bookingTemplate
                    ? $row->bookingTemplate->template_name
                    : 'N/A';
            })
                ->addColumn('created_date', function ($row) {
                    return $row->created_at->format('d M Y h:i A');
                })
                ->addColumn('action', function ($row) {
                    return '<a href="' . route('transaction.view', $row->id) . '" 
                class="btn btn-icon btn-success" title="View Transaction">
                <i class="feather icon-eye"></i>
            </a>';
                })
                ->rawColumns(['checkbox', 'action'])
                ->toJson();
        }
        $customers = User::select('id', 'name')->orderBy('name')->get();
        return view('admin.transaction.index', compact('customers'));
    }
    public function view($id)
    {
        $transaction = Transaction::with(['customer', 'vendor'])
            ->findOrFail($id);

        return view('admin.transaction.view', compact('transaction'));
    }
}
