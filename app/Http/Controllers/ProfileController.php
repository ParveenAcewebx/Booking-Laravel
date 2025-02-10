<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'password' => 'nullable|string|min:8|confirmed',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'

        ]);

        $user = Auth::user();
        $user->name = $request->input('name');
        
        if ($request->filled('password')) {
            $user->password = Hash::make($request->input('password'));
        }

        if ($request->hasFile('avatar')) {
            // Store the uploaded image in the 'avatars' folder
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            
            // Delete the old avatar if it exists
            if ($user->avatar) {
                \Storage::disk('public')->delete($user->avatar);
            }
    
            // Save the path of the new avatar
            $user->avatar = $avatarPath;
        }

        $user->save();

        return redirect()->route('profile.edit')->with('success', 'Profile updated successfully.');
    }
}
