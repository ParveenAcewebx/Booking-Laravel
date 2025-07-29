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
        if (!Auth::check()) {
            return view('frontend.bookingListing', [
                'bookings' => BookingTemplate::all(),
                'username' => null
            ]);
        }

        $userId = Auth::id();
        $username = Auth::user()->name;

        $bookings = BookingTemplate::where('created_by', $username)->get();

        return view('frontend.bookingListing', compact('bookings', 'userId'));
    }
}
