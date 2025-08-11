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
        } else {
            $userId = Auth::id();
            if ($userId) {
                $user = Auth::user();
                $userId = $user->id;
                $bookingform = BookingTemplate::where('created_by', $userId)->get();
                return view('frontend.bookingListing', [
                    'bookings' => $bookingform,
                    'username' => null
                ]);
            }
        }
    }
}
