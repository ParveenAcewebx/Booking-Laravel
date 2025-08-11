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
        return view('frontend.bookingListing', [
            'bookings' => BookingTemplate::all(),
            'username' => null
        ]);
    }
}
