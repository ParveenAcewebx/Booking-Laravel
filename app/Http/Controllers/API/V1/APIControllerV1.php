<?php

namespace App\Http\Controllers\API\V1;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Booking;
use App\Models\BookingTemplate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Models\VendorStaffAssociation;
use App\Http\Controllers\Controller;

class APIControllerV1 extends Controller
{
    // Login API
    public function loginUserAPI(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email|exists:users,email',
                'password' => 'required|min:6',
            ]);

            $user = User::where('email', $request->email)->first();

            if (!$user || !Hash::check($request->password, $user->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Wrong credentials',
                ], 401);
            }

            $user->tokens()->delete();
            $token = $user->createToken('API Token')->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'User Login Successfully',
                'token' => $token,
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation Failed',
                'errors' => $e->errors(),
            ], 422);
        }
    }

    // Fetch all active booking templates
    public function bookingTemplates()
    {
        $bookings = BookingTemplate::where('status', '1')->get();

        if ($bookings->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No Active Booking Templates Found',
                'data' => [],
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Booking Templates Fetched Successfully',
            'data' => $bookings,
        ], 200);
    }

    // Fetch all bookings
    public function index()
    {
        $bookings = Booking::all();

        if ($bookings->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No Bookings Found',
                'data' => [],
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Bookings Fetched Successfully',
            'data' => $bookings,
        ], 200);
    }

    // Fetch a specific booking by ID
    public function show($id)
    {
        $booking = Booking::find($id);

        if (!$booking) {
            return response()->json([
                'success' => false,
                'message' => 'Booking Not Found!',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Bookings Data Fetched Successfully',
            'data' => $booking,
        ], 200);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'User Logged Out Successfully',
        ], 200);
    }

    public function searchBookingByVendorId($vendorId)
    {
        $bookings = Booking::where('vendor_id', $vendorId)->get();

        if ($bookings->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No Bookings Found For This Vendor',
                'data' => [],
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Bookings Data Fetched Successfully',
            'data' => $bookings,
        ], 200);
    }

    public function searchBookingByServiceId($serviceId)
    {
        $bookings = Booking::where('service_id', $serviceId)->get();

        if ($bookings->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No Bookings Found For This Service',
                'data' => [],
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Bookings Data Fetched Successfully',
            'data' => $bookings,
        ], 200);
    }

    public function searchBookingByStaffId($staffId)
    {
        $vendorIds = VendorStaffAssociation::where('user_id', $staffId)->pluck('vendor_id');

        $bookings = Booking::whereIn('vendor_id', $vendorIds)->get();

        if ($bookings->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No Bookings Found For This Staff',
                'data' => [],
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Bookings Data Fetched Successfully',
            'data' => $bookings->toArray(),
        ], 200);
    }
}
