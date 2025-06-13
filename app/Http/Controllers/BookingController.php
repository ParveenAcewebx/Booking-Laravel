<?php

namespace App\Http\Controllers;

use App\Helpers\FormHelper;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\BookingTemplate;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use DB;
use Carbon\Carbon;
use Mail;
use DataTables;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cookie;

class BookingController extends Controller
{
    protected $allUsers;
    public function __construct()
    {
        $this->allUsers = User::all();
    }

    public function index(Request $request)
    {
        $allusers  = $this->allUsers;
        $loginId = session('previous_login_id');
        $loginUser = null;

        if ($loginId) {
            $loginUser = User::find($loginId);
        }

        if ($request->ajax()) {
            $bookings = Booking::query();

            return DataTables::of($bookings)
                ->editColumn('created_at', function ($booking) {
                    return $booking->created_at->format('Y-m-d H:i:s');
                })
                ->editColumn('status', function ($booking) {
                    return '<span class="badge badge-light-success"> Active </span>';
                })
                ->addColumn('action', function ($booking) {
                    $btn = '';

                    if (auth()->user()->can('edit bookings')) {
                        $btn .= '<a href="' . route('booking.edit', [$booking->id]) . '" class="btn btn-icon btn-success" data-toggle="tooltip" data-placement="top" title="Edit Booking">
                                <i class="fas fa-pencil-alt"></i>
                            </a> ';
                    }

                    if (auth()->user()->can('delete bookings')) {
                        $btn .= '<form action="' . route('booking.delete', [$booking->id]) . '" method="POST" id="deleteBooking-' . $booking->id . '" style="display:inline-block;">
                <input type="hidden" name="_method" value="DELETE">
                ' . csrf_field() . '
                <button type="button" onclick="return deleteBooking(' . $booking->id . ')" class="btn btn-icon btn-danger" data-toggle="tooltip" data-placement="top" title="Delete Booking">
                    <i class="feather icon-trash-2"></i>
                </button>
            </form>';
                    }

                    return $btn ?: '-';
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }

        return view('booking.index', compact('loginUser'));
    }
    public function bookingAdd()
    {

        $alltemplates = BookingTemplate::all();
        $allusers  = $this->allUsers;
        $loginId = session('previous_login_id');
        $loginUser = null;

        if ($loginId) {
            $loginUser = User::find($loginId);
        }
        return view('booking.add', ['alltemplates' => $alltemplates, 'allusers' => $allusers, 'alluser' => $allusers, 'loginUser' => $loginUser]);
    }

    public function bookingSave(Request $request)
    {
        $request->validate([
            'selected_staff' => 'required',
            'booking_datetime' => 'required'
        ]);
        $bookingData = json_decode($request->booking_data, true);
        $booking = Booking::create([
            'booking_template_id' => $request->booking_template_id,
            'customer_id' => $request->customer_id,
            'booking_datetime' => $request->booking_datetime,
            'booking_data' => json_encode($bookingData),
            'selected_staff' => $request->selected_staff,
        ]);
        if ($booking) {
            return redirect('/bookings')->with('success', 'Booking Added Successfully.');
        } else {
            return redirect()->back()->with('error', 'It failed. Please try again.');
        }
    }

    public function bookingEdit($id)
    {
        $booking = Booking::with('form')->findOrFail($id);

        // Decode stored dynamic booking values (if any)
        $dynamicValues = json_decode($booking->booking_data, true) ?? [];

        // Parse JSON form structure from associated form
        $formStructureJson = $booking->form->data ?? '[]';
        $formStructureArray = json_decode($formStructureJson, true);

        // Generate the dynamic form fields HTML from helper
        $dynamicFieldHtml = \App\Helpers\FormHelper::renderDynamicFieldHTML($formStructureArray, $dynamicValues);

        // Format datetime for HTML5 input
        $booking->booking_datetime = date('Y-m-d\TH:i', strtotime($booking->booking_datetime));

        // Convert staff name to ID for selected dropdown
        $selectedStaffUser = User::where('name', $booking->selected_staff)->first();
        $booking->selected_staff = $selectedStaffUser?->id;

        // Fetch impersonator user info if available
        $loginId = session('previous_login_id');
        $loginUser = $loginId ? User::find($loginId) : null;

        return view('booking.edit', [
            'booking' => $booking,
            'dynamicFieldHtml' => $dynamicFieldHtml,
            'staffList' => $this->allUsers,
            'loginUser' => $loginUser,
        ]);
    }


    public function bookingUpdate(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);

        $booking->booking_data = json_encode($request->input('dynamic', []));
        $booking->booking_datetime = $request->input('booking_datetime');

        $selectedStaff = User::find($request->input('staff'));
        $booking->selected_staff = $selectedStaff?->name ?? '';

        $booking->save();

        return redirect()->route('booking.list')->with('success', 'Booking Updated Successfully.');
    }

    public function bookingDelete($id)
    {
        $booking = Booking::find($id);
        $booking->delete();
        return response()->json(['success' => true]);
    }

    public function loadTemplateHTML($id)
    {
        $template = BookingTemplate::find($id);

        if (!$template) {
            return response()->json([
                'success' => false,
                'message' => 'Template not found.'
            ], 404);
        }

        try {
            $html = FormHelper::renderDynamicFieldHTML($template->data);
            return response()->json([
                'success' => true,
                'html' => $html
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Render error: ' . $e->getMessage()
            ], 500);
        }
    }
}
