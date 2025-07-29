<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserProfileController extends Controller
{
    public function userEdit()
    {
        return view('frontend.user.profile');
    }
    public function UserUpdate(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . auth()->id(),
            'phone_number' => 'nullable|string|max:20',
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png,bmp,gif,svg|max:2048',  // Validation for the avatar
        ]);
        $user = auth()->user();
        $avatarPath = $user->avatar;
        if ($request->hasFile('avatar')) {
            if ($avatarPath && Storage::disk('public')->exists($avatarPath)) {
                Storage::disk('public')->delete($avatarPath);
            }
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
        } elseif ($request->filled('remove_avatar') && $request->remove_avatar == '1') {
            if ($avatarPath && Storage::disk('public')->exists($avatarPath)) {
                Storage::disk('public')->delete($avatarPath);
            }
            $avatarPath = null;
        }
        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone_number' => $validated['phone_number'],
            'avatar' => $avatarPath,
        ]);
        return redirect()->route('Userprofile')->with('success', 'Profile updated successfully.');
    }
}
