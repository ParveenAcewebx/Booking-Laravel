<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\BookingTemplate;
use App\Models\User;

class BookingListingController extends Controller
{
    public function listing()
    {
        $sessionData = session()->all();
        foreach ($sessionData as $key => $value) {
            if (strpos($key, 'user_data_') === 0) {
                session()->forget($key);
            }
        }
        return view('frontend.bookingListing', [
            'bookings' => BookingTemplate::all(),
            'username' => null
        ]);
    }
}
