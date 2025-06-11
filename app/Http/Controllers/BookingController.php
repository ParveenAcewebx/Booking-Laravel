<?php

namespace App\Http\Controllers;

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
                    return '<span class="badge badge-light-success">Active</span>';
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
                                ' . csrf_field() . method_field('DELETE') . '
                                <button onclick="return confirm(\'Are you sure to delete this booking?\');" class="btn btn-icon btn-danger" data-toggle="tooltip" data-placement="top" title="Delete Booking">
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
            return redirect('/bookings')->with('success', 'Booking Added successfully!');
        } else {
            return redirect()->back()->with('error', 'It failed. Please try again.');
        }
    }

    public function bookingEdit($id)
    {
        $booking = Booking::with(['form', 'staff'])->findOrFail($id);
        $dynamicValues = json_decode($booking->booking_data, true);
        $formStructure = json_decode($booking->form->data, true);
        $fieldsWithValues = [];
        foreach ($formStructure as $field) {
            $name = $field['name'] ?? null;
            $field['value'] = $dynamicValues[$name] ?? null;
            $fieldsWithValues[] = $field;
        }
        $staffList = $this->allUsers;
        $allusers = $this->allUsers;
        $loginId = session('previous_login_id');
        $loginUser = null;

        if ($loginId) {
            $loginUser = User::find($loginId);
        }
        $booking->booking_datetime = date('Y-m-d\TH:i', strtotime($booking->booking_datetime));
        return view('booking.edit', compact('booking', 'fieldsWithValues', 'staffList', 'allusers', 'loginUser'));
    }

    public function bookingUpdate(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);
        $dynamicFields = $request->input('dynamic', []);
        $booking->booking_data = json_encode($dynamicFields);
        $booking->selected_staff = $request->input('staff');
        $booking->booking_datetime = $request->input('booking_datetime');
        $booking->save();
        return redirect()->route('booking.list')->with('success', 'Booking updated successfully.');
    }

    public function bookingDelete($id)
    {
        $booking = Booking::find($id);
        $booking->delete();
        return redirect('/bookings')->with('success', 'Booking Delete successfully!');
    }
}