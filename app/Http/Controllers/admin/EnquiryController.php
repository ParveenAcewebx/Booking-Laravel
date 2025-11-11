<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Mail;
use Illuminate\Mail\Message;// <-- Correct place for Mail

class EnquiryController extends Controller
{
    // Display all enquiries
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return DataTables::of(Contact::query())
                ->addColumn('checkbox', function ($contact) {
                    return '<input type="checkbox" class="selectRow" value="' . $contact->id . '">';
                })
                ->addColumn('name', fn($contact) => $contact->name)
                ->addColumn('email', fn($contact) => $contact->email)
                ->addColumn('phone', fn($contact) => $contact->phone)
                ->editColumn('created_at', function ($contact) {
                    return $contact->created_at
                        ? $contact->created_at->format(get_setting('date_format', 'Y-m-d') . ' ' . get_setting('time_format', 'H:i'))
                        : '';
                })
                ->addColumn('action', function ($contact) {
                    $btn = '';
                    if (auth()->user()->can('reply enquires')) {
                        $btn .= '<a href="javascript:void(0)" class="btn btn-icon btn-success replyview" data-id="' . $contact->id . '" title="Reply">
                                    <i class="feather icon-mail"></i>
                                </a> ';
                    }
                    if (auth()->user()->can('view enquires')) {
                        $btn .= '<a href="javascript:void(0)" class="btn btn-icon btn-info showenquiry" data-id="' . $contact->id . '" title="View">
                                    <i class="feather icon-eye"></i>
                                </a> ';
                    }
                    if (auth()->user()->can('delete enquires')) {
                        $btn .= '<form id="deleteEnquiry-' . $contact->id . '" 
                                action="' . route('enquiry.destroy', $contact->id) . '" 
                                method="POST" style="display:inline-block;">
                                <input type="hidden" name="_method" value="DELETE">
                                ' . csrf_field() . '
                                <button type="button" onclick="return EnquiryDelete(' . $contact->id . ', event)" 
                                        class="btn btn-icon btn-danger" title="Delete">
                                    <i class="feather icon-trash-2"></i>
                                </button>
                            </form>';
                    }
                    return $btn;
                })
                ->rawColumns(['checkbox', 'action'])
                ->make(true);
        }

        return view('admin.enquiry.index');
    }

    // Show a single enquiry
    public function show($id)
    {
        $contact = Contact::findOrFail($id);

        if ($contact->read == 0) {
            $contact->update(['read' => 1]);
        }

        return response()->json($contact);
    }

    // Delete single enquiry
    public function destroy($id)
    {
        $contact = Contact::findOrFail($id);
        $contact->delete();

        return response()->json([
            'success' => true,
            'message' => 'Contact Enquiry Deleted Successfully!'
        ]);
    }

    // Bulk delete enquiries
    public function bulkDelete(Request $request)
    {
        $ids = $request->ids;
        Contact::whereIn('id', $ids)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Contact Enquiries Deleted Successfully!'
        ]);
    }

    public function reply(Request $request)
    {
        try {
            $request->validate([
                'id' => 'required|exists:contacts,id',
                'reply_message' => 'required|string',
            ]);
    
            $contact = Contact::findOrFail($request->id);
            $macros = [
                '{USER_NAME}' => $contact->name,
                '{MESSAGE}' => $contact->message,
                '{REPLY}' => $request->reply_message,
                '{SITE_TITLE}' => get_setting('site_title'),
            ];

            sendCustomerReplyTemplateEmail('customer_reply_notification', $contact->email, $macros);
    
            return response()->json([
                'success' => true,
                'message' => 'Reply Sent Successfully!'
            ]);
    
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }    
}
