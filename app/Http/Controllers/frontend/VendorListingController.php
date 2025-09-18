<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\BookingTemplate;
use App\Models\User;

class VendorListingController extends Controller
{
    public function listing($id = null)
    {
        if ($id) {
            $bookings = BookingTemplate::where('status', 1)
                ->where('vendor_id', $id)
                ->where('data', '!=', '')
                ->get();
        }

        return view('frontend.VendorListing', [
            'bookings' => $bookings,
            'username' => null
        ]);
    }
}
