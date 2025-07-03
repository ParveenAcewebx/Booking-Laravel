<?php

namespace App\Http\Controllers\frontend;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use App\Http\Controllers\Controller;
use App\Models\Booking;

class BookingListingController extends Controller
{
    public function listing()
    {
        $userId = Auth::id();
        $categories = Booking::where('status', config('constants.status.active'))
            ->withCount('services')
            ->get();
        return view('frontend.categoryListing', compact('categories','userId'));
    }

    public function show()
    {
        return "data";
    }
}
