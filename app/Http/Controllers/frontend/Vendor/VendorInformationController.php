<?php

namespace App\Http\Controllers\Frontend\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\VendorStaffAssociation;

class VendorInformationController extends Controller
{
    public function view()
    {
        if (Auth::check()) {
            $currentUserId = Auth::id();
            $vendorId = VendorStaffAssociation::where('user_id', $currentUserId)->value('vendor_id');
            if ($vendorId) {
                return view('frontend.vendor.view');
            }
            return redirect()->route('login')->with('error', 'No vendor associated with this user.');
        }
        return redirect()->route('login')->with('error', 'Unauthorized access.');
    }
}
