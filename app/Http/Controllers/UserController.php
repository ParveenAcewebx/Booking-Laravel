<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\form;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;


class UserController extends Controller
{
    public function index(){
        $alluser=User::all();
        return view('user.userlist', ['alluser' => $alluser]);

    }

    public function userAdd(){
        return view('user.usercreate');
    }

    public function userSave(Request $request)
    {
        // Validate input data
        $request->validate([
            'username' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);
    
        // Initialize avatar path
        $avatarPath = null;
    
        // Check if an avatar file is uploaded
        if ($request->hasFile('avatar')) {
            // Store the uploaded image in the 'avatars' folder
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
        }
    
        // Save user data to the database
        $user = User::create([
            'name' => $request->username,
            'email' => $request->email,
            'password' => bcrypt($request->password), 
            'avatar' => $avatarPath, // Save the uploaded avatar path or null
        ]);
    
        // Check if the user was created successfully
        if ($user) {
            return redirect('/user')->with('success', 'User Added successfully!');
        } else {
            return redirect()->back()->with('error', 'It failed. Please try again.');
        }
    }

    public function userEdit($id)
    {
        $user = User::findOrFail($id);
        return view('user.useredit', ['user' => $user]);
    }
    
    public function userUpdate(Request $request, $id)
    {
        // Validate input data
        $request->validate([
            'username' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable|min:6|confirmed',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);
    
        $user = User::findOrFail($id);
    
        // Handle avatar update
        $avatarPath = $user->avatar; // Keep existing avatar
        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
        }
    
        // Update user details
        $user->update([
            'name' => $request->username,
            'email' => $request->email,
            'avatar' => $avatarPath,
        ]);
    
        // Update password if provided
        if ($request->password) {
            $user->update(['password' => bcrypt($request->password)]);
        }

        $userRole=Role::find($request->role);
        $user->roles()->detach();
        $user->assignRole($userRole);
        return back()->with('success', 'User updated successfully!');
        return redirect('/user')->with('success', 'User updated successfully!');
    }
    
    
    public function userDelete($id) {

        $user = User::find($id);
        $authuser_id =Auth::user()->id;
        $username=$user->name;
        if($authuser_id!=$id){
           $user->delete();
           return response()->json(['success' => true]);
        }else{
            return response()->json(['success' => 'login', 'message' => 'Item not found']);

        }
    }

    public function createUser()
    {
        $user=User::find(1);
        $user->assignRole('writer');
        return response()->json(['message' => 'User created successfully']);
    } 

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        $remember = $request->has('rememberme');
        if (Auth::attempt($credentials,$remember)) {
            return redirect()->intended('/');
        }
        return redirect('/login')->with('error', 'Invalid credentials. Please try again.');
    }

    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        return redirect('/login')->with('success', 'Registration successful! Please log in.');
    }

    public function permissionCheck(){
        echo "permission has given";
    }

    public function home()
    {
        return view('layouts.home');
    }

    public function logout(){
        Auth::logout();
        return redirect('/login');
    }

    public function todo(){
        return redirect('/home');
    }
    
    public function forgotPassword(){
        return view('auth.forgot-password');
    }

    public function sendResetLinkEmail(Request $request)
    {
        
        $request->validate(['email' => 'required|email']);
        $response = Password::sendResetLink($request->only('email'));
        return $response == Password::RESET_LINK_SENT
            ? back()->with('status', 'We have emailed your password reset link!')
            : back()->withErrors(['email' => 'We couldn\'t find an account with that email address.']);
    }

    public function showResetForm(Request $request, $token = null)
    {
        return view('auth.reset')->with(['token' => $token, 'email' => $request->email]);
    }

    public function reset(Request $request)
    {
        $tokenData = DB::table('password_resets')
        ->where('token', $token) 
        ->first();
        if ($tokenData) {
            $email = $tokenData->email;
            $request->merge(['email' => $email]);
        }
        $request->validate([
            'email'    => 'required',
            'password' => 'required|confirmed|min:8',
            'token' => 'required',
        ]);
        $response = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->save();

                event(new PasswordReset($user));
            }
        );
        return $response == Password::PASSWORD_RESET
            ? redirect()->route('login')->with('status', 'Your password has been reset!')
            : back()->withErrors(['email' => 'There was an error resetting your password.']);
    }
   public function userrole(){
        $adminRole = Role::firstOrCreate(['name' => 'Administrator']);
        $staffRole = Role::firstOrCreate(['name' => 'Staff']);
        $bookingRole = Role::firstOrCreate(['name' => 'Booking Manager']);
        $customerRole = Role::firstOrCreate(['name' => 'Customer']);

        // Create permissions
        $editPermission = Permission::firstOrCreate(['name' => 'edit']);
        $managePermission = Permission::firstOrCreate(['name' => 'manage']);
        $viewPermission = Permission::firstOrCreate(['name' => 'view']);

        // Assign all permissions to the admin role
        $adminRole->givePermissionTo($editPermission, $managePermission, $viewPermission);

        // Assign 'view' permission to staff
        $staffRole->givePermissionTo($viewPermission);

        // Assign 'edit' and 'view' permissions to the booking manager
        $bookingRole->givePermissionTo($editPermission, $viewPermission);
        $user = User::where('id', User::min('id'))->first();
        echo $user->id;
        $user->assignRole($adminRole);
        $users = User::where('id', '!=', $user->id)->get();
        foreach ($users as $userToUpdate) {
            $userToUpdate->assignRole($bookingRole);
        }
 }
}
