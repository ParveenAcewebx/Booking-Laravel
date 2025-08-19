<?php

namespace App\Http\Controllers\admin;

use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
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
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cookie;
use App\Models\Service;
use App\Models\Vendor;

class BookingController extends Controller
{
    protected $allUsers;
    public function __construct()
    {
        $this->allUsers = User::role('Staff')->get();
    }

    public function index(Request $request)
    {
        $loginId = getOriginalUserId();
        $loginUser = $loginId ? User::find($loginId) : null;

        if ($request->ajax()) {
            $bookings = Booking::with(['template', 'customer'])
                ->select('bookings.*');

            return DataTables::of($bookings)
                ->addColumn('template_name', function ($booking) {
                    return $booking->template ? $booking->template->template_name : '';
                })
                ->filterColumn('template_name', function ($query, $keyword) {
                    $query->whereHas('template', function ($q) use ($keyword) {
                        $q->where('template_name', 'like', "%{$keyword}%");
                    });
                })
                ->orderColumn('template_name', function ($query, $order) {
                    $query->join('booking_templates', 'booking_templates.id', '=', 'bookings.booking_template_id')
                        ->orderBy('booking_templates.template_name', $order);
                })
                ->addColumn('booked_by', function ($booking) {
                    return $booking->customer ? $booking->customer->name : '';
                })
                ->filterColumn('booked_by', function ($query, $keyword) {
                    $query->whereHas('customer', function ($q) use ($keyword) {
                        $q->where('name', 'like', "%{$keyword}%");
                    });
                })
                ->orderColumn('booked_by', function ($query, $order) {
                    $query->join('users as customers', 'customers.id', '=', 'bookings.customer_id')
                        ->orderBy('customers.name', $order);
                })
                ->editColumn('created_at', function ($booking) {
                    return $booking->created_at
                        ? $booking->created_at->format(
                            get_setting('date_format', 'Y-m-d') . ' ' . get_setting('time_format', 'H:i')
                        )
                        : '';
                })
                ->editColumn('status', function ($booking) {
                    return '<span class="badge badge-light-success">Active</span>';
                })
                ->addColumn('action', function ($booking) {
                    $btn = '';

                    if (auth()->user()->can('edit bookings')) {
                        $btn .= '<a href="' . route('booking.edit', $booking->id) . '" class="btn btn-icon btn-success" data-toggle="tooltip" title="Edit Booking">
                                <i class="fas fa-pencil-alt"></i>
                            </a> ';
                    }

                    if (auth()->user()->can('delete bookings')) {
                        $btn .= '<form action="' . route('booking.delete', $booking->id) . '" method="POST" id="deleteBooking-' . $booking->id . '" style="display:inline-block;">
                                <input type="hidden" name="_method" value="DELETE">
                                ' . csrf_field() . '
                                <button type="button" onclick="return deleteBooking(' . $booking->id . ')" class="btn btn-icon btn-danger" data-toggle="tooltip" title="Delete Booking">
                                    <i class="feather icon-trash-2"></i>
                                </button>
                            </form>';
                    }

                    return $btn;
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }

        return view('admin.booking.index', compact('loginUser'));
    }


    public function bookingAdd()
    {
        $alltemplates = BookingTemplate::all();
        $allusers = $this->allUsers;
        $loginId = getOriginalUserId();
        $loginUser = $loginId ? User::find($loginId) : null;

        return view('admin.booking.add', [
            'alltemplates' => $alltemplates,
            'allusers' => $allusers,
            'alluser' => $allusers,
            'loginUser' => $loginUser,
        ]);
    }

    public function bookingSave(Request $request)
    {
        
        $request->validate([
            'booking_datetime' => 'required'
        ]);
        
        $bookingData = json_decode($request->booking_data, true) ?? [];
        $inputData = $request->input('dynamic', []);
        $files = $request->file('dynamic', []);

        foreach ($inputData as $key => $val) {
            $bookingData[$key] = $val;
        }

        foreach ($files as $key => $fileInput) {
            if (is_array($fileInput)) {
                $paths = [];
                foreach ($fileInput as $file) {
                    if ($file && $file->isValid()) {
                        $paths[] = $file->store('bookings', 'public');
                    }
                }
                $bookingData[$key] = $paths;
            } elseif ($fileInput && $fileInput->isValid()) {
                $bookingData[$key] = $fileInput->store('bookings', 'public');
            }
        }

        $booking = Booking::create([
            'booking_template_id' => $request->booking_template_id,
            'customer_id' => $request->customer_id,
            'booking_datetime' => $request->booking_datetime,
            'booking_data' => json_encode($bookingData),
            'selected_staff' => '',
            'bookslots'                 => $request->input('bookslots'),
            'service_id'                => $bookingData['service'] ?? NULL,
            'vendor_id'                 => $bookingData['vendor'] ?? NULL,

        ]);

        return $booking
            ? redirect('/admin/bookings')->with('success', 'Booking Added Successfully.')
            : redirect()->back()->with('error', 'It failed. Please try again.');
    }

    public function bookingEdit($id)
        {
            $booking = Booking::with('template')->findOrFail($id);
            $dynamicValues = json_decode($booking->booking_data, true) ?? [];
            if (isset($dynamicValues['service'])) {
                $servicedata = Service::where('id', $dynamicValues['service'])->first();
            } else {
                $servicedata = null;
            }
            if (isset($dynamicValues['vendor'])) {
                $vendorname = Vendor::where('id', $dynamicValues['vendor'])->pluck('name')->first();
            } else {
                $vendorname = null;
            }
            $serviceverndor = [
                'serivename' => $servicedata ? $servicedata->name : null,
                'serviceprice' => $servicedata ? $servicedata->price : null,
                'servicurrency' => $servicedata ? $servicedata->currency : null,
                'serviceduration' => $servicedata ? $servicedata->duration : null,
                'vendorname' => $vendorname,
            ];
            $slotedetail = json_decode($booking->bookslots);
            $formStructureJson = $booking->template->data ?? '[]';
            $formStructureArray = json_decode($formStructureJson, true);
            $bookingid = $id ? $id : '';
            $booking->booking_datetime = date('Y-m-d\TH:i', strtotime($booking->booking_datetime));
            $selectedStaffUser = User::where('name', $booking->selected_staff)->first();
            $booking->selected_staff = $selectedStaffUser ? $selectedStaffUser->id : null;
            $loginId = getOriginalUserId();
            $loginUser = $loginId ? User::find($loginId) : null;
            return view('admin.booking.edit', [
                'booking' => $booking,
                'userinfo' => $dynamicValues,
                'serviceverndor' => $serviceverndor,
                'slotedetail' => $slotedetail,
                'staffList' => $this->allUsers,
                'loginUser' => $loginUser,
            ]);
        }



    public function bookingUpdate(Request $request, $id)
    {
        $request->validate([
            'booking_datetime' => 'required',
        ]);

        $booking = Booking::findOrFail($id);

        $existingData = json_decode($booking->booking_data, true) ?? [];
        $newInputData = $request->input('dynamic', []);
        $files = $request->file('dynamic', []);

        foreach ($newInputData as $key => $val) {
            $existingData[$key] = $val;
        }

        foreach ($files as $key => $fileInput) {
            if (is_array($fileInput)) {
                $paths = [];
                foreach ($fileInput as $file) {
                    if ($file && $file->isValid()) {
                        $paths[] = $file->store('bookings', 'public');
                    }
                }
                $existingData[$key] = $paths;
            } elseif ($fileInput && $fileInput->isValid()) {
                $existingData[$key] = $fileInput->store('bookings', 'public');
            }
        }

        $booking->booking_data = json_encode($existingData);
        if (!empty($booking->first_name)) {
            $booking->first_name = $existingData['first_name'];
            $booking->last_name = $existingData['last_name'];
            $booking->phone_number = $existingData['phone'];
            $booking->email = $existingData['email'];
        }

        $booking->selected_staff = '';

        $booking->save();
        return redirect()->route('booking.list')->with('success', 'Booking Updated Successfully.');
    }

    public function bookingDelete($id)
    {
        $booking = Booking::find($id);

        if (!$booking) {
            return response()->json(['success' => false, 'message' => 'Booking not found.']);
        }

        $bookingData = json_decode($booking->booking_data, true) ?? [];

        foreach ($bookingData as $value) {
            if (is_string($value) && Storage::disk('public')->exists($value)) {
                Storage::disk('public')->delete($value);
            } elseif (is_array($value)) {
                foreach ($value as $filePath) {
                    if (is_string($filePath) && Storage::disk('public')->exists($filePath)) {
                        Storage::disk('public')->delete($filePath);
                    }
                }
            }
        }
        $booking->delete();
        return response()->json(['success' => true, 'message' => 'Booking Deleted Successfully.']);
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
            // explicitly use bootstrap for backend
            $html = FormHelper::renderDynamicFieldHTML($template->data, [], 'bootstrap');
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
