<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class SubscriptionController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return DataTables::of(Subscription::query())
                ->addColumn('checkbox', function ($subscription) {
                    
                    return '<input type="checkbox" class="selectRow" value="' . $subscription->id . '">';
                })
                ->addColumn('email', function ($subscription) {
                    return $subscription->email;
                })
                ->editColumn('created_at', function ($subscription) {
                    return $subscription->created_at
                        ? $subscription->created_at->format(get_setting('date_format', 'Y-m-d') . ' ' . get_setting('time_format', 'H:i'))
                        : '';
                })
                ->addColumn('action', function ($subscription) {
                    if (auth()->user()->can('delete subscriptions')) {
                    $btn = '<form id="deleteSubscription-' . $subscription->id . '" 
                                action="' . route('subscription.destroy', $subscription->id) . '" 
                                method="POST" style="display:inline-block;">
                            <input type="hidden" name="_method" value="DELETE">
                            ' . csrf_field() . '
                            <button type="button" onclick="return SubscriptionDelete(' . $subscription->id . ', event)" 
                                    class="btn btn-icon btn-danger" title="Delete">
                                <i class="feather icon-trash-2"></i>
                            </button>
                        </form>';
                    return $btn;
                    }
                })
                ->rawColumns(['checkbox', 'action'])
                ->make(true);
        }

        return view('admin.subscription.index');
    }

    public function destroy($id)
    {
        $subscription = Subscription::findOrFail($id);
        $subscription->delete();

        return response()->json([
            'success' => true,
            'message' => 'Subscription Deleted Successfully!'
        ]);
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->ids;
        Subscription::whereIn('id', $ids)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Subscriptions Deleted Successfully!'
        ]);
    }
}