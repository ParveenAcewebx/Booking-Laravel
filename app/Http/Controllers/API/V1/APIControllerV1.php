<?php

namespace App\Http\Controllers\API\V1;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Booking;
use App\Models\Service;
use App\Models\BookingTemplate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Models\VendorStaffAssociation;
use App\Http\Controllers\Controller;
use App\Models\VendorServiceAssociation;

class APIControllerV1 extends Controller
{
    /**
     * Login API
     */
    public function loginUserAPI(Request $request)
    {
        try {
            $request->validate([
                'email'    => 'required|email|exists:users,email',
                'password' => 'required|min:6',
            ]);

            $user = User::where('email', $request->email)->first();

            if (!$user || !Hash::check($request->password, $user->password)) {
                return response()->json([
                    'success'       => false,
                    'message'       => 'Wrong credentials',
                    'total_records' => 0,
                ], 401);
            }

            $user->tokens()->delete();
            $token = $user->createToken('API Token')->plainTextToken;

            return response()->json([
                'success'       => true,
                'message'       => 'User Login Successfully',
                'token'         => $token,
                'total_records' => 1,
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'success'       => false,
                'message'       => 'Validation Failed',
                'errors'        => $e->errors(),
                'total_records' => 0,
            ], 422);
        }
    }

    /**
     * Fetch all active booking templates
     */
    public function bookingTemplates()
    {
        $bookings = BookingTemplate::where('status', '1')->get();

        if ($bookings->isEmpty()) {
            return response()->json([
                'success'       => false,
                'message'       => 'No Active Booking Templates Found',
                'data'          => [],
                'total_records' => 0,
            ], 404);
        }

        $bookings->transform(function ($booking) {
            $booking->data = !empty($booking->data) ? json_decode($booking->data, true) : [];
            return $booking;
        });

        return response()->json([
            'success'       => true,
            'message'       => 'Booking Templates Fetched Successfully',
            'data'          => $bookings,
            'total_records' => $bookings->count(),
        ], 200);
    }

    /**
     * Fetch all bookings
     */
    public function index()
    {
        $bookings = Booking::all();

        if ($bookings->isEmpty()) {
            return response()->json([
                'success'       => false,
                'message'       => 'No Bookings Found',
                'data'          => [],
                'total_records' => 0,
            ], 404);
        }

        $data = $bookings->map(function ($booking) {
            return [
                'id'                  => $booking->id,
                'booking_template_id' => $booking->booking_template_id,
                'customer_id'         => $booking->customer_id,
                'booking_datetime'    => $booking->booking_datetime,
                'booking_data'        => !empty($booking->booking_data) ? json_decode($booking->booking_data, true) : [],
                'selected_staff'      => $booking->selected_staff,
                'status'              => $booking->status,
                'created_at'          => $booking->created_at,
                'updated_at'          => $booking->updated_at,
                'first_name'          => $booking->first_name,
                'last_name'           => $booking->last_name,
                'phone_number'        => $booking->phone_number,
                'email'               => $booking->email,
                'service_id'          => $booking->service_id,
                'vendor_id'           => $booking->vendor_id,
                'bookslots'           => $booking->bookslots,
            ];
        });

        return response()->json([
            'success'       => true,
            'message'       => 'Bookings Fetched Successfully',
            'data'          => $data,
            'total_records' => $data->count(),
        ], 200);
    }

    /**
     * Fetch a specific booking by ID
     */
    public function show($id)
    {
        if (!$id || !is_numeric($id)) {
            return response()->json([
                'success'       => false,
                'message'       => 'Invalid Booking ID',
                'total_records' => 0,
            ], 422);
        }

        $booking = Booking::find($id);

        if (!$booking) {
            return response()->json([
                'success'       => false,
                'message'       => 'Booking Not Found!',
                'total_records' => 0,
            ], 404);
        }

        $booking->booking_data = !empty($booking->booking_data)
            ? json_decode($booking->booking_data, true)
            : [];

        return response()->json([
            'success'       => true,
            'message'       => 'Booking Data Fetched Successfully',
            'data'          => $booking,
            'total_records' => 1,
        ], 200);
    }

    /**
     * Logout API
     */
    public function logout(Request $request)
    {
        if (!$request->user()) {
            return response()->json([
                'success'       => false,
                'message'       => 'Unauthorized Access',
                'total_records' => 0,
            ], 401);
        }

        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success'       => true,
            'message'       => 'User Logged Out Successfully',
            'total_records' => 1,
        ], 200);
    }

    /**
     * Booking by Vendor
     */
    public function searchBookingByVendorId($vendorId)
    {
        if (!$vendorId || !is_numeric($vendorId)) {
            return response()->json([
                'success'       => false,
                'message'       => 'Invalid Vendor ID',
                'total_records' => 0,
            ], 422);
        }

        $bookings = Booking::where('vendor_id', $vendorId)->get();

        if ($bookings->isEmpty()) {
            return response()->json([
                'success'       => false,
                'message'       => 'No Bookings Found For This Vendor',
                'data'          => [],
                'total_records' => 0,
            ], 404);
        }

        $bookings->transform(function ($booking) {
            $booking->booking_data = !empty($booking->booking_data) ? json_decode($booking->booking_data, true) : [];
            return $booking;
        });

        return response()->json([
            'success'       => true,
            'message'       => 'Bookings Data Fetched Successfully',
            'data'          => $bookings,
            'total_records' => $bookings->count(),
        ], 200);
    }

    /**
     * Booking by Service
     */
    public function searchBookingByServiceId($serviceId)
    {
        if (!$serviceId || !is_numeric($serviceId)) {
            return response()->json([
                'success'       => false,
                'message'       => 'Invalid Service ID',
                'total_records' => 0,
            ], 422);
        }

        $getVendorIds = VendorServiceAssociation::where('service_id', $serviceId)->pluck('vendor_id')->toArray();
        $bookings     = Booking::whereIn('vendor_id', $getVendorIds)->get();

        if ($bookings->isEmpty()) {
            return response()->json([
                'success'       => false,
                'message'       => 'No Bookings Found For This Service',
                'data'          => [],
                'total_records' => 0,
            ], 404);
        }

        $bookings->transform(function ($booking) {
            $booking->booking_data = !empty($booking->booking_data) ? json_decode($booking->booking_data, true) : [];
            return $booking;
        });

        return response()->json([
            'success'       => true,
            'message'       => 'Bookings Data Fetched Successfully',
            'data'          => $bookings,
            'total_records' => $bookings->count(),
        ], 200);
    }

    /**
     * Booking by Staff
     */
    public function searchBookingByStaffId($staffId)
    {
        if (!$staffId || !is_numeric($staffId)) {
            return response()->json([
                'success'       => false,
                'message'       => 'Invalid Staff ID',
                'total_records' => 0,
            ], 422);
        }

        $vendorIds = VendorStaffAssociation::where('user_id', $staffId)->pluck('vendor_id');

        if ($vendorIds->isEmpty()) {
            return response()->json([
                'success'       => false,
                'message'       => 'No Vendors Found For This Staff',
                'data'          => [],
                'total_records' => 0,
            ], 404);
        }

        $bookings = Booking::whereIn('vendor_id', $vendorIds)->get();

        if ($bookings->isEmpty()) {
            return response()->json([
                'success'       => false,
                'message'       => 'No Bookings Found For This Staff',
                'data'          => [],
                'total_records' => 0,
            ], 404);
        }

        $bookings->transform(function ($booking) {
            $booking->booking_data = !empty($booking->booking_data) ? json_decode($booking->booking_data, true) : [];
            return $booking;
        });

        return response()->json([
            'success'       => true,
            'message'       => 'Bookings Data Fetched Successfully',
            'data'          => $bookings,
            'total_records' => $bookings->count(),
        ], 200);
    }

    /**
     * Staff by ID
     */
    public function searchStaffById($id)
    {
        if (!$id || !is_numeric($id)) {
            return response()->json([
                'success'       => false,
                'message'       => 'Invalid Staff ID',
                'total_records' => 0,
            ], 422);
        }

        $user = User::with('roles')->find($id);

        if (!$user) {
            return response()->json([
                'status'        => 'error',
                'message'       => 'User not found',
                'total_records' => 0,
            ], 404);
        }

        if (!$user->hasRole('Staff')) {
            return response()->json([
                'status'        => 'error',
                'message'       => 'This ID does not have Staff permission',
                'total_records' => 0,
            ], 403);
        }

        return response()->json([
            'status'        => 'success',
            'message'       => 'Staff Fetched Successfully',
            'data'          => [
                'id'                => $user->id,
                'name'              => $user->name,
                'email'             => $user->email,
                'phone_code'        => $user->phone_code,
                'phone_number'      => $user->phone_number,
                'email_verified_at' => $user->email_verified_at,
                'status'            => $user->status,
                'created_at'        => $user->created_at,
                'updated_at'        => $user->updated_at,
                'avatar'            => $user->avatar,
                'role'              => $user->roles->pluck('name')->first(),
            ],
            'total_records' => 1,
        ], 200);
    }

    public function searchServiceById($id)
    {
        if (!$id || !is_numeric($id)) {
            return response()->json([
                'success'       => false,
                'message'       => 'Invalid Service ID',
                'total_records' => 0,
            ], 422);
        }

        $service = Service::find($id);

        if (!$service) {
            return response()->json([
                'success'       => false,
                'message'       => 'Service Not Found!',
                'total_records' => 0,
            ], 404);
        }

        return response()->json([
            'success'       => true,
            'message'       => 'Service Data Fetched Successfully',
            'data'          => $service,
            'total_records' => 1,
        ], 200);
    }
}
