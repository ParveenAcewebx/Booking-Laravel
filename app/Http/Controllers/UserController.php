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
use DB;
use Carbon\Carbon;
use Mail;

use Illuminate\Support\Str;


class UserController extends Controller
{

    public function index()
    {
        $alluser = User::all();
        $currentUserId = Auth::id();
        return view('user.userlist', ['alluser' => $alluser]);
    }

    public function userAdd()
    {
        $allRole = Role::all();
        return view('user.usercreate', ['allRoles' => $allRole]);
    }

    public function userSave(Request $request)
    {
        // Validate input data
        $request->validate([
            'username' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'avatar' => 'required|nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
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
        $userRole = Role::find($request->role);
        $user->assignRole($userRole);
        // Check if the user was created successfully
        if ($user) {
            return redirect('/user')->with('success', 'User Added successfully!');
        } else {
            return redirect()->back()->with('error', 'It failed. Please try again.');
        }
    }

    public function userEdit($id = null)
    {
        if ($id == null) {
            $id = Auth::id();
        }
        $user = User::findOrFail($id);

        $user->unsetRelation('roles')->unsetRelation('permissions');
        $roles = $user->roles;
        return view('user.useredit', ['user' => $user, 'allRoles' => Role::all(), 'currentRole' => $roles[0]->id]);
    }

    public function userUpdate(Request $request, $id = null)
    {
        if ($id == null) {
            $id = Auth::id();
        }
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
        $userRole = Role::find($request->role);
        $user->roles()->detach();
        $user->assignRole($userRole);
        return back()->with('success', 'User updated successfully!');
    }


    public function userDelete($id)
    {

        $user = User::find($id);
        $authuser_id = Auth::user()->id;
        $username = $user->name;
        if ($authuser_id != $id) {
            $user->delete();
            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => 'login', 'message' => 'Item not found']);
        }
    }

    public function createUser()
    {
        $user = User::find(1);
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
        if (Auth::attempt($credentials, $remember)) {
            if (Auth::user()->hasRole('Customer')) {
                return redirect()->intended('/welcome');
            } else {
                return redirect()->intended('/');
            }
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
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        $role_id = 4;
        $userRole = Role::find($role_id);
        $user->assignRole($userRole);


        return redirect('/login')->with('success', 'Registration successful! Please log in.');
    }

    public function permissionCheck()
    {
        echo "permission has given";
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/login');
    }

    public function todo()
    {
        return redirect('/home');
    }

    public function forgotPassword()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users',
        ]);
        $token = Str::random(64);
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            [
                'token' => $token,
                'created_at' => Carbon::now()
            ]
        );

        $resetLink = route('password.reset', ['token' => $token]);

        Mail::send([], [], function ($message) use ($request, $resetLink) {
            $message->to($request->email)
                ->subject('Reset Password')
                ->setBody('Here is your reset password link: <a href="' . $resetLink . '">Click here to reset your password</a>', 'text/html');
        });

        return back()->with('message', 'We have e-mailed your password reset link!');
    }

    public function showResetForm(Request $request, $token = null)
    {
        return view('auth.reset')->with(['token' => $token, 'email' => $request->email]);
    }

    public function reset(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users',
            'password' => 'required|string|min:6|confirmed',
            'password_confirmation' => 'required'
        ]);
        $updatePassword = DB::table('password_reset_tokens')
            ->where([
                'email' => $request->email,
                'token' => $request->token
            ])
            ->first();
        if (!$updatePassword) {
            return back()->withInput()->with('error', 'Invalid token!');
        }
        $user = User::where('email', $request->email)->update(['password' => Hash::make($request->password)]);
        DB::table('password_reset_tokens')->where(['email' => $request->email])->delete();
        return redirect('/login')->with('message', 'Your password has been changed!');
    }
    public function welcome()
    {
        return view('auth.welcome');
    }
    // Create roles
    public function userrole()
    {
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
