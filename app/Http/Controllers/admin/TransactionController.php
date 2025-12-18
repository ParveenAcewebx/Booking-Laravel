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
            $query = Transaction::query()->with(['customer:id,name', 'vendor:id,name', 'bookingTemplate:id,template_name']);
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
                    return $row->customer->name;
                })
                ->addColumn('template_name', function ($row) {
                    return  $row->bookingTemplate->template_name;
                })
                ->addColumn('created_date', function ($row) {
                    return $row->created_at->format('d M Y h:i A');
                })
                ->addColumn('action', function ($row) {
                    $btn = '';
                    if (auth()->user()->can('view transactions')) {
                        $btn .= '<a href="' . route('transaction.view', $row->id) . '" 
                                    class="btn btn-icon btn-success" 
                                    title="View Transaction">
                                    <i class="feather icon-eye"></i>
                                </a> ';
                    }
                    if (auth()->user()->can('delete transactions')) {
                        $btn .= '<form id="deleterow-' . $row->id . '" 
                        action="' . route('transaction.delete', $row->id) . '" 
                        method="POST" 
                        style="display:inline-block;">
                        <input type="hidden" name="_method" value="DELETE">
                        ' . csrf_field() . '
                        <button type="button" 
                                onclick="deleterow(' . $row->id . ', event)" 
                                class="btn btn-icon btn-danger" 
                                title="Delete row">
                            <i class="feather icon-trash-2"></i>
                        </button>
                    </form>';
                    }if (auth()->user()->can('refund')) {
                        $btn .= '<form action="' . route('stripe.refund', $row->id) . '" 
                            method="POST" 
                            style="display:inline-block;">
                            ' . csrf_field() . '
                            <button type="submit" 
                                    class="btn btn-icon btn-warning btn-sm"
                                    title="Refund">
                                <i class="feather icon-rotate-ccw"></i>
                            </button>
                        </form> ';
                    }
                    return $btn;
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
    public function delete($id)
    {
        $transaction = Transaction::findOrFail($id);
        $transaction->delete();
        return response()->json(['success' => true]);
    }
}
