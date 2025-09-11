<?php

namespace App\Http\Controllers\admin;

use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use App\Helpers\FormHelper;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\EmailTemplate;
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
        if ($request->ajax()) {
            $bookings = Booking::with(['template', 'customer'])
                ->select('bookings.*');
    
            if ($request->has('template_id') && $request->template_id != '') {
                $bookings->where('booking_template_id', $request->template_id);
            }
    
            if ($request->has('customer_id') && $request->customer_id != '') {
                $bookings->where('customer_id', $request->customer_id);
            }
    
            if ($request->has('start_date') && $request->start_date != '') {
                $bookings->whereDate('created_at', '=', $request->start_date);
            }
    
            return DataTables::of($bookings)
                ->addColumn('template_name', function ($booking) {
                    return $booking->template ? $booking->template->template_name : '';
                })
                ->addColumn('booked_by', function ($booking) {
                    return $booking->customer ? $booking->customer->name : '';
                })
                ->editColumn('created_at', function ($booking) {
                    return $booking->created_at
                        ? $booking->created_at->format(get_setting('date_format', 'Y-m-d') . ' ' . get_setting('time_format', 'H:i'))
                        : '';
                })
                ->addColumn('action', function ($booking) {
                    $btn = '';
                    $btn .= '<a href="' . route('booking.view', $booking->id) . '" class="btn btn-icon btn-success" title="View Booking">
                              <i class="feather icon-eye"></i>
                            </a> ';
                    $btn .= '<form id="deleteBooking-' . $booking->id . '" 
                                    action="' . route('booking.delete', $booking->id) . '" 
                                    method="POST" style="display:inline-block;">
                                <input type="hidden" name="_method" value="DELETE">
                                ' . csrf_field() . '
                                <button type="button" onclick="return deleteBooking(' . $booking->id . ', event)" 
                                        class="btn btn-icon btn-danger" title="Delete Booking">
                                    <i class="feather icon-trash-2"></i>
                                </button>
                            </form>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    
        $templates = BookingTemplate::all();
        $customers = User::whereHas('roles')->get();
    
        return view('admin.booking.index', compact('templates', 'customers'));
    }
    

    public function bookingAdd()
    {
        $alltemplates = BookingTemplate::where('status', 1)->where('data', '!=', '')->get();
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
            'booking_template_id'       => $request->booking_template_id,
            'customer_id'               => $request->customer_id,
            'booking_datetime'          => $request->booking_datetime ?? date('Y-m-d H:i:s'),
            'booking_data'              => json_encode($bookingData),
            'selected_staff'            => '',
            'bookslots'                 => $request->input('bookslots'),
            'service_id'                => $bookingData['service'] ?? NULL,
            'vendor_id' => !empty($bookingData['vendor']) ? $bookingData['vendor'] : null,
        ]);

        return $booking
            ? redirect('/admin/bookings')->with('success', 'Booking Added Successfully.')
            : redirect()->back()->with('error', 'It failed. Please try again.');
    }

    public function bookingview($id)
    {
        $booking = Booking::with('template')->findOrFail($id);
        $dynamicValues = json_decode($booking->booking_data, true) ?? [];

        $servicedata = isset($dynamicValues['service'])
            ? Service::where('id', $dynamicValues['service'])->first()
            : null;

        $vendorname = isset($dynamicValues['vendor'])
            ? Vendor::where('id', $dynamicValues['vendor'])->pluck('name')->first()
            : null;

        $serviceverndor = [
            'serivename'      => $servicedata?->name,
            'serviceprice'    => $servicedata?->price,
            'servicurrency'   => $servicedata?->currency,
            'serviceduration' => $servicedata?->duration,
            'vendorname'      => $vendorname,
        ];

        $slotedetail = json_decode($booking->bookslots);
        $formStructureJson = $booking->template->data ?? '[]';
        $formStructureArray = json_decode($formStructureJson, true);
        $formStructureArray = array_filter($formStructureArray, fn($item) => $item['type'] !== 'shortcodeblock');

        $AdditionalInformation = [];

        if (!empty($dynamicValues)) {
            $excludedKeys = ['first_name', 'last_name', 'email', 'phone', 'service', 'vendor'];

            $filteredDynamicValues = array_filter(
                $dynamicValues,
                fn($key) => !in_array($key, $excludedKeys),
                ARRAY_FILTER_USE_KEY
            );

            $filteredKeys = array_keys($filteredDynamicValues);

            $matchedValues = array_map(function ($field) use ($dynamicValues) {
                $name = $field['name'] ?? null;
                if (!$name) return null;
                $value = $dynamicValues[$name] ?? null;

                if ($field['type'] === 'checkbox-group') {
                    $values = (array) ($value ?? []);
                    if (in_array('other', $values)) {
                        $values = array_diff($values, ['other']);
                        if (!empty($dynamicValues[$name . '_other'])) {
                            $otherValues = (array) $dynamicValues[$name . '_other'];
                            $values = array_merge($values, $otherValues);
                        }
                    }
                    return array_values($values);
                }

                if ($field['type'] === 'radio-group') {
                    if ($value === 'other' && !empty($dynamicValues[$name . '_other'])) {
                        return $dynamicValues[$name . '_other'];
                    }
                    return $value;
                }

                return $value;
            }, array_filter($formStructureArray, function ($field) use ($filteredKeys) {
                return !empty($field['name']) && in_array($field['name'], $filteredKeys);
            }));

            $matchedLabels = array_map(
                function ($field) {
                    return isset($field['label']) ? $field['label'] : '';
                },
                array_filter($formStructureArray, function ($field) use ($filteredKeys) {
                    return !empty($field['name']) && in_array($field['name'], $filteredKeys);
                })
            );


            $AdditionalInformation = [
                'AddInfoLabel'       => $matchedLabels,
                'AddInfoValue'       => $matchedValues,
                'formStructureArray' => $formStructureArray,
            ];
        }

        $bookingid = $id ?: '';

        if (!empty($booking->booking_datetime)) {
            $booking->booking_datetime = date('Y-m-d\TH:i', strtotime($booking->booking_datetime));
        }

        $selectedStaffUser = User::where('name', $booking->selected_staff)->first();
        $booking->selected_staff = $selectedStaffUser?->id;

        $loginId = getOriginalUserId();
        $loginUser = $loginId ? User::find($loginId) : null;

        return view('admin.booking.view', [
            'bookingid'            => $id,
            'booking'              => $booking,
            'AdditionalInformation' => $AdditionalInformation,
            'userinfo'             => $dynamicValues,
            'serviceverndor'       => $serviceverndor,
            'slotedetail'          => $slotedetail,
            'staffList'            => $this->allUsers,
            'loginUser'            => $loginUser,
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

    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids');

        if (!$ids || !is_array($ids)) {
            return response()->json(['success' => false, 'message' => 'No Records Selected.'], 400);
        }

        Booking::whereIn('id', $ids)->delete();
        return response()->json(['success' => true, 'message' => 'Selected Bookings Deleted Successfully.']);
    }
}
