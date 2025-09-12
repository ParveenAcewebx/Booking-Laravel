<?php

namespace App\Http\Controllers\Frontend\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;;
use App\Models\VendorStaffAssociation;
use App\Models\VendorServiceAssociation;
use App\Models\User;
use App\Models\Service;
use App\Models\Booking;
use App\Models\BookingTemplate;
use App\Models\Category;
use App\Models\Role;
use App\Models\Staff;
use App\Models\StaffServiceAssociation;

class VendorProfileController extends Controller
{
    /*========================== profile update ====================*/
    public function UserprofileUpdate(Request $request)
    {
        $user = auth()->user();

        // Validation
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone_number' => 'nullable|string|max:20',
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png,bmp,gif,svg|max:2048',
            'remove_avatar' => 'nullable|boolean',
        ]);

        $avatarPath = $user->avatar;

        // Handle new avatar upload
        if ($request->hasFile('avatar')) {
            // Delete old avatar if exists
            if ($avatarPath && Storage::disk('public')->exists($avatarPath)) {
                Storage::disk('public')->delete($avatarPath);
            }
            // Store new avatar
            $avatarPath = $request->file('avatar')->store('avatars', 'public');

        // Handle remove avatar
        } elseif ($request->filled('remove_avatar') && $request->remove_avatar) {
            if ($avatarPath && Storage::disk('public')->exists($avatarPath)) {
                Storage::disk('public')->delete($avatarPath);
            }
            $avatarPath = null;
        }

        // Update user profile
        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone_number' => $validated['phone_number'] ?? null,
            'avatar' => $avatarPath,
        ]);

        return redirect()->route('vendor.dashboard.view')->with('success', 'Profile Updated Successfully.');
    }
}
