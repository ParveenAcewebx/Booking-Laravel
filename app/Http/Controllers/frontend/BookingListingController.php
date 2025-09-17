<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\BookingTemplate;
use App\Models\User;

class BookingListingController extends Controller
{
    public function listing($id = null)
    {
        // Clear old session data
        foreach (session()->all() as $key => $value) {
            if (strpos($key, 'user_data_') === 0) {
                session()->forget($key);
            }
        }

        // If ID is given → filter by that ID
        if ($id) {
            $bookings = BookingTemplate::where('status', 1)
                ->where('vendor_id', $id)
                ->where('data', '!=', '')
                ->get();
        } else {
            // Otherwise fetch all
            $bookings = BookingTemplate::where('status', 1)
                ->where('data', '!=', '')
                ->get();
        }

        return view('frontend.bookingListing', [
            'bookings' => $bookings,
            'username' => null
        ]);
    }
}
