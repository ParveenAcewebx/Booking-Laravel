<?php

namespace App\Http\Controllers\admin;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\form;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;


class UserController extends Controller
{
    protected $allUsers;
    protected $originalUserId;
    public function __construct()
    {
        $this->allUsers = User::all();
        $this->originalUserId = session('impersonate_original_user') ?? Cookie::get('impersonate_original_user');
    }
    public function index(Request $request)
    {
        $loginId = session('previous_login_id');
        $loginUser = $loginId ? User::find($loginId) : null;

        if ($request->ajax()) {
            $currentUser = Auth::user();
            $isImpersonating = session()->has('impersonate_original_user') || Cookie::get('impersonate_original_user');
            $statusLabels = array_flip(config('constants.status'));

            $query = User::with('roles')->select('users.*'); // select explicitly for joins

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('name', function ($row) {
                    return '<h6 class="m-b-0">' . e($row->name) . '</h6><p class="m-b-0">' . e($row->email) . '</p>';
                })
                ->editColumn('created_at', function ($row) {
                    return $row->created_at ? $row->created_at->format('Y-m-d H:i:s') : '';
                })
                ->addColumn('roles', function ($row) {
                    return $row->roles->pluck('name')->map(function ($role) {
                        return '<span class="badge badge-primary">' . e($role) . '</span>';
                    })->implode(' ');
                })
                ->addColumn('status', function ($row) use ($statusLabels) {
                    return $row->status == config('constants.status.active')
                        ? '<span class="badge badge-success">Active</span>'
                        : '<span class="badge badge-danger">Inactive</span>';
                })
                ->addColumn('action', function ($row) use ($currentUser, $isImpersonating) {
                    $btn = '';

                    if (Auth::id() == $row->id) {
                        $btn .= '<a href="' . route('profile') . '" class="btn btn-icon btn-success" data-toggle="tooltip" title="Edit User">
                                <i class="fas fa-pencil-alt"></i>
                             </a> ';
                    } elseif ($currentUser->can('edit users')) {
                        $btn .= '<a href="' . route('user.edit', [$row->id]) . '" class="btn btn-icon btn-success" data-toggle="tooltip" title="Edit User">
                                <i class="fas fa-pencil-alt"></i>
                             </a> ';
                    }

                    if ($currentUser->can('delete users') && Auth::id() != $row->id) {
                        $btn .= '<form action="' . route('user.delete', [$row->id]) . '" method="POST" style="display:inline;" id="deleteUser-' . $row->id . '">
                                ' . csrf_field() . '
                                <input type="hidden" name="_method" value="DELETE">
                                <button type="button" onclick="return deleteUser(' . $row->id . ')" class="btn btn-icon btn-danger" data-toggle="tooltip" title="Delete User">
                                    <i class="feather icon-trash-2"></i>
                                </button>
                            </form>';
                    }

                    if ($isImpersonating && Auth::id() === $row->id) {
                        $btn .= '<form method="POST" action="' . route('user.switch.back') . '" style="display:inline;">
                                ' . csrf_field() . '
                                <button type="submit" class="btn btn-icon btn-dark" data-toggle="tooltip" title="Switch Back">
                                    <i class="feather icon-log-out"></i>
                                </button>
                            </form>';
                    } elseif (!$isImpersonating && $currentUser->hasRole('Administrator') && $currentUser->id !== $row->id) {
                        $btn .= '<form method="POST" action="' . route('user.switch', $row->id) . '" style="display:inline;">
                                ' . csrf_field() . '
                                <button type="submit" class="btn btn-icon btn-dark" data-toggle="tooltip" title="Switch User">
                                    <i class="fas fa-random"></i>
                                </button>
                            </form>';
                    }

                    return $btn;
                })
                ->rawColumns(['name', 'roles', 'status', 'action'])
                ->make(true);
        }

        return view('user.index', compact('loginUser'));
    }


    public function userAdd()
    {
        $allRoles = Role::where('status', 1)->get();
        $allusers = $this->allUsers;
        $originalUserId = $this->originalUserId;
        $loginId = session('previous_login_id');
        $loginUser = null;

        if ($loginId) {
            $loginUser = User::find($loginId);
        }

        return view('user.add', compact('allRoles', 'allusers', 'originalUserId', 'loginUser'));
    }

    public function userSave(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'role' => 'required',
            'status' => 'nullable|boolean',
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
            'phone_number' => $request->phone_number,
            'status' => $request->has('status') ? config('constants.status.active') : config('constants.status.inactive'),
        ]);

        $userRole = Role::find($request->role);
        $user->assignRole($userRole);

        if ($user) {
            return redirect('/user')->with('success', 'User Added Successfully.');
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

        $allusers =  $this->allUsers;
        $originalUserId = $this->originalUserId;
        $loginId = session('previous_login_id');
        $loginUser = null;

        if ($loginId) {
            $loginUser = User::find($loginId);
        }

        if ($roles->count() > 0) {
            $currentRole = $roles[0]->id;
        }

        return view('user.edit', [
            'user' => $user,
            'allRoles' => Role::where('status', 1)->get(),
            'allusers' => $allusers,
            'currentRole' => $currentRole,
            'originalUserId' => $originalUserId,
            'loginUser' => $loginUser
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

        // Handle avatar upload
        $avatarPath = $user->avatar;
        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
        }


        $status = $request->has('status') ? config('constants.status.active') : config('constants.status.inactive');
        // Update user basic fields
        $user->update([
            'name' => $request->username,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'avatar' => $avatarPath,
            'status' => $status, 
        ]);

        // Update password if provided
        if ($request->password) {
            $user->update([
                'password' => bcrypt($request->password)
            ]);
        }

        // Update role
        $userRole = Role::find($request->role);
        $user->roles()->detach();
        $user->assignRole($userRole);

        return back()->with('success', 'User Updated Successfully.');
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
        return response()->json(['message' => 'User Created Successfully.']);
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
            $user = Auth::user();

            if ($user->status != 1 && $user->hasRole('Admin')) {
                Auth::logout();
                return redirect('/login')->with('error', 'Your account is inactive. Please contact support.');
            }

            if ($user->hasRole('Customer') ) { 
                Auth::logout();
                return redirect('/login')->with('error', 'You do not have permissions to access this area.');
            }

            session(['previous_login_id' => $user->id]);
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

        return redirect('/admin');
    }

    public function switchBack()
    {
        $originalUserId = session('impersonate_original_user') ?? Cookie::get('impersonate_original_user');
        // dd($originalUserId);
        if ($originalUserId) {
            $originalUser = User::find($originalUserId);

            if ($originalUser) {
                Auth::login($originalUser);

                session()->forget('impersonate_original_user');
                Cookie::queue(Cookie::forget('impersonate_original_user'));

                return redirect('/admin');
            }
        }
        return redirect('/admin');
    }
}
