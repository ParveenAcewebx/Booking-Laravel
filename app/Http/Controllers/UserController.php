<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Cookie;
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
        $currentUser = Auth::user();

        $originalUserId = session('impersonate_original_user') ?? Cookie::get('impersonate_original_user');
        $loginId = session('previous_login_id');
        $loginUser = null;

        if ($loginId) {
            $loginUser = User::find($loginId);  // Get user model for that ID
        }
        return view('user.index', compact('alluser', 'currentUser', 'originalUserId', 'loginUser'));
    }

    public function userAdd()
    {
        $allRole = Role::where('status', 1)->get();
        return view('user.add', ['allRoles' => $allRole]);
    }

    public function userSave(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'avatar' => 'required|nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);
        $avatarPath = null;
        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
        }
        $user = User::create([
            'name' => $request->username,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'avatar' => $avatarPath,
        ]);
        $userRole = Role::find($request->role);
        $user->assignRole($userRole);
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
        $currentRole = null;
        if ($roles->count() > 0) {
            $currentRole = $roles[0]->id;
        }
        return view('user.edit', [
            'user' => $user,
            'allRoles' => Role::where('status', 1)->get(),
            'currentRole' => $currentRole
        ]);
    }

    public function userUpdate(Request $request, $id = null)
    {
        if ($id == null) {
            $id = Auth::id();
        }
        $request->validate([
            'username' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable|min:6|confirmed',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);
        $user = User::findOrFail($id);
        $avatarPath = $user->avatar;
        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
        }
        $user->update([
            'name' => $request->username,
            'email' => $request->email,
            'avatar' => $avatarPath,
        ]);
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
                $currentId = Auth::user()->id;
                session(['previous_login_id' => $currentId]);

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
        session()->forget(['impersonate_original_user', 'previous_login_id']);
        Cookie::queue(Cookie::forget('impersonate_original_user'));
        session()->flush();

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
        $editPermission = Permission::firstOrCreate(['name' => 'edit']);
        $managePermission = Permission::firstOrCreate(['name' => 'manage']);
        $viewPermission = Permission::firstOrCreate(['name' => 'view']);
        $adminRole->givePermissionTo($editPermission, $managePermission, $viewPermission);
        $staffRole->givePermissionTo($viewPermission);
        $bookingRole->givePermissionTo($editPermission, $viewPermission);
        $user = User::where('id', User::min('id'))->first();
        echo $user->id;
        $user->assignRole($adminRole);
        $users = User::where('id', '!=', $user->id)->get();
        foreach ($users as $userToUpdate) {
            $userToUpdate->assignRole($bookingRole);
        }
    }
    public function switchUser($id)
    {
        $currentUser = Auth::user();

        if (!$currentUser->hasRole('Administrator')) {
            abort(403, 'Unauthorized action.');
        }

        if ($currentUser->id == $id) {
            return redirect()->back()->with('error', 'You are already logged in as this user.');
        }

        if (!session()->has('impersonate_original_user') && !Cookie::get('impersonate_original_user')) {
            session(['impersonate_original_user' => $currentUser->id]);
            Cookie::queue('impersonate_original_user', $currentUser->id, 60 * 24 * 7); // 7 days
        }

        $userToSwitch = User::findOrFail($id);
        Auth::login($userToSwitch);

        return redirect('/user')->with('success', 'Switch new user: ' . $userToSwitch->name);
    }

    public function switchBack()
    {
        $originalUserId = session('impersonate_original_user') ?? Cookie::get('impersonate_original_user');

        if ($originalUserId) {
            $originalUser = User::find($originalUserId);

            if ($originalUser) {
                Auth::login($originalUser);

                session()->forget('impersonate_original_user');
                Cookie::queue(Cookie::forget('impersonate_original_user'));

                return redirect('/user')->with('success', 'Switched back to original user: ' . $originalUser->name);
            }
        }
        return redirect('/user')->with('error', 'Unable to switch back to original user.');
    }
}
