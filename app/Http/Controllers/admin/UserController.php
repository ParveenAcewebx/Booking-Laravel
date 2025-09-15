<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\form;
use App\Models\Staff;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\PasswordResetToken;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    protected $allUsers;
    public function __construct()
    {
        $this->allUsers = User::all();
    }

    public function index(Request $request)
    {
        $loginId = getOriginalUserId();
        $loginUser = $loginId ? User::find($loginId) : null;
    
        if ($request->ajax()) {
            $currentUser = Auth::user();
            $isImpersonating = session()->has('impersonate_original_user') || Cookie::get('impersonate_original_user');
            $statusLabels = array_flip(config('constants.status'));
    
            $query = User::with(['roles'])
                ->whereDoesntHave('roles', function ($q) {
                    $q->where('name', 'staff'); // exclude staff if needed
                })
                ->select('users.*');
    
            return DataTables::of($query)
                ->addIndexColumn()
    
                ->addColumn('checkbox', function ($row) use ($currentUser) {
                    if (Auth::id() != $row->id) {
                        return '<input type="checkbox" class="selectRow" value="' . $row->id . '">';
                    }
                    return '<input type="checkbox" class="selectRow" value="' . $row->id . '" disabled>';
                })
    
                ->addColumn('name', function ($row) {
                    return '<h6 class="m-b-0">' . e($row->name) . '</h6><p class="m-b-0">' . e($row->email) . '</p>';
                })
    
                ->editColumn('created_at', function ($row) {
                    return $row->created_at
                        ? $row->created_at->format(
                            get_setting('date_format', 'Y-m-d') . ' ' . get_setting('time_format', 'H:i')
                        )
                        : '';
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
    
                    // Edit
                    if (Auth::id() == $row->id) {
                        $btn .= '<a href="' . route('profile') . '" class="btn btn-icon btn-success" data-toggle="tooltip" title="Edit User">
                                    <i class="fas fa-pencil-alt"></i>
                                 </a> ';
                    } elseif ($currentUser->can('edit users')) {
                        $btn .= '<a href="' . route('user.edit', [$row->id]) . '" class="btn btn-icon btn-success" data-toggle="tooltip" title="Edit User">
                                    <i class="fas fa-pencil-alt"></i>
                                 </a> ';
                    }
    
                    // Delete
                    if ($currentUser->can('delete users') && Auth::id() != $row->id) {
                        $btn .= '<form action="' . route('user.delete', [$row->id]) . '" method="POST" style="display:inline;" id="deleteUser-' . $row->id . '">';
                        $btn .= csrf_field();
                        $btn .= method_field('DELETE');
                        $btn .= '<button type="button" onclick="return deleteUser(' . $row->id . ')" class="btn btn-icon btn-danger" data-toggle="tooltip" title="Delete User">
                                    <i class="feather icon-trash-2"></i>
                                 </button>';
                        $btn .= '</form>';
                    }
    
                    // Impersonation
                    if ($isImpersonating && Auth::id() === $row->id) {
                        $btn .= '<form method="POST" action="' . route('user.switch.back') . '" style="display:inline;">'
                            . csrf_field() . '
                            <button type="submit" class="btn btn-icon btn-dark" data-toggle="tooltip" title="Switch Back">
                                <i class="feather icon-log-out"></i>
                            </button>
                        </form>';
                    } elseif (!$isImpersonating && $currentUser->hasRole('Administrator') && $currentUser->id !== $row->id) {
                        if ($row->status == config('constants.status.active')) {
                            $btn .= '<form method="POST" action="' . route('user.switch', $row->id) . '" style="display:inline;">'
                                . csrf_field() . '
                                <button type="submit" class="btn btn-icon btn-dark" data-toggle="tooltip" title="Switch User">
                                    <i class="fas fa-random"></i>
                                </button>
                            </form>';
                        }
                    }
    
                    return $btn;
                })
    
                ->rawColumns(['checkbox', 'name', 'roles', 'status', 'action'])
                ->make(true);
        }
    
        return view('admin.user.index', compact('loginUser'));
    }
    


    public function userAdd()
    {
        $allRoles = Role::where('status', 1)->get();
        $allusers = $this->allUsers;
        $loginId = getOriginalUserId();
        $phoneCountries = config('phone_countries');
        $loginUser = null;

        if ($loginId) {
            $loginUser = User::find($loginId);
        }

        return view('admin.user.add', compact('allRoles', 'allusers', 'loginUser', 'phoneCountries'));
    }

    public function userSave(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'role' => 'required',
            'status' => 'required|in:' . config('constants.status.active') . ',' . config('constants.status.inactive'),
            'code' => 'required',
            'phone_number' => 'required'
        ]);

        $fullPhoneNumber = $request->phone_number;

        $avatarPath = null;
        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
        }
        $user = User::create([
            'name' => $request->username,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'avatar' => $avatarPath,
            'phone_number' => $fullPhoneNumber,
            'phone_code' => $request->code,
            'status' => $request->status,
        ]);

        $userRole = Role::find($request->role);
        $user->assignRole($userRole);

        if ($user) {
            // return redirect('/admin/users')->with('success', 'User Added Successfully.');
            return redirect()->route('user.list')->with('success', 'User Added Successfully.');
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
        $phoneCountries = config('phone_countries');
        $roles = $user->roles;
        if ($roles[0]->name == 'Staff') {
            return redirect('admin/staff/' . $id . '/edit');
        }
        $currentRole = null;

        $allusers =  $this->allUsers;
        $loginId = getOriginalUserId();
        $loginUser = null;

        if ($loginId) {
            $loginUser = User::find($loginId);
        }

        if ($roles->count() > 0) {
            $currentRole = $roles[0]->id;
        }

        $primaryStaff = Staff::where('user_id', $id)
            ->where('primary_staff', 1)
            ->first();
        return view('admin.user.edit', [
            'user' => $user,
            'allRoles' => Role::where('status', 1)->get(),
            'allusers' => $allusers,
            'currentRole' => $currentRole,
            'phoneCountries' => $phoneCountries,
            'loginUser' => $loginUser,
            'primaryStaff' => $primaryStaff
        ]);
    }

    public function userUpdate(Request $request, $id = null)
    {
        if ($id == null) {
            $id = Auth::id();
        }
        $user = User::findOrFail($id);

        $avatarPath = $user->avatar;
        // dd($avatarPath);
        $request->validate([
            'username' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable|min:6|confirmed',
            'status' => 'required|in:' . config('constants.status.active') . ',' . config('constants.status.inactive'),
            'phone_number' => 'required'

        ]);

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

        $status = $request->status;
        $fullPhoneNumber = $request->phone_number;

        $user->update([
            'name' => $request->username,
            'email' => $request->email,
            'phone_number' => $fullPhoneNumber,
            'avatar' => $avatarPath,
            'status' => $status,
            'phone_code' => $request->code,
        ]);

        if ($request->password) {
            $user->update([
                'password' => bcrypt($request->password)
            ]);
        }
        // dd($request->role);
        $userRole = Role::find($request->role);
        $user->roles()->sync([$userRole->id]);

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
            if ($user->status != 1 && $user->hasRole('Administrator')) {
                Auth::logout();
                return redirect('/login')->with('error', 'Your account is inactive. Please contact support.');
            } elseif ($user->hasRole('Administrator') && $user->status == 1) {
                return redirect()->intended('/admin/dashboard');
            } elseif ($user->hasRole('Customer') && $user->status == 0) {
                Auth::logout();
                return redirect('/login')->with('error', 'Your account is inactive. Please contact support.');
            } else {
                return redirect('/');
            }

            session(['impersonate_original_user' => $user->id]);
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

        $macros = [
            '{NAME}' =>$user->name,
            '{EMAIL}' => $user->email,
            '{SITE_TITLE}' => get_setting('site_title') ,
        ];
     
        newcustomerregister('new_account_email_notification', $user->email, $macros);
        sendAdminTemplateEmail('admin_new_user_notification',get_setting('owner_email'), $macros);
        return redirect('/login')->with('success', 'Registration successful! Please log in.');
    }

    public function permissionCheck()
    {
        echo "permission has given";
    }

    public function logout()
    {
        Auth::logout();
        session()->forget(['impersonate_original_user', 'impersonate_original_user']);
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
        $token = Str::random(24);
        PasswordResetToken::updateOrCreate(['email' => $request->email],['token' => $token,'created_at' => now(),]);
        $resetLink = route('password.reset', ['token' => $token]);
        $user = User::where('email', $request->email)->pluck('name');
        $macros = [
            '{NAME}' => $user[0],
            '{RESET_LINK}' => $resetLink,
            '{SITE_TITLE}' => get_setting('site_title'),
        ];
    SendPasswordResetEmail('password_reset_email', $request->email, $macros);
    return redirect()->route('login')->with('status', 'We have emailed your password reset link!');
    }

    public function showResetForm(Request $request, $token = null)
    {
        if(!empty($request->token)){
            if ($this->tokenverification($request->token)=== true) {
                return view('auth.reset')->with([
                    'token' => $token,
                    'email' => $request->email,
                ]);
            }elseif($this->tokenverification($request->token) === false){
               return redirect()->route('password.request')
                 ->with('error', 'Your reset token has expired or is invalid. Please request a new password reset.');
            }
        }
    }
    public function tokenverification($token)
    {
        $tokenRecord = PasswordResetToken::where('token', $token)->first();
        if ($tokenRecord) {
            $createdAt = Carbon::parse($tokenRecord->created_at);
            if ($createdAt->gt(now()->subMinutes(10))) {
                return true; 
            }else{
                return false; 
            }
        }
        return '';
        
    }

    public function reset(Request $request)
    {
        $request->validate([
            'token'=>'required',
            'password' => 'required|string|min:6|confirmed',
        ]);
        if(!empty($request->token)){
            $email = PasswordResetToken::where('token', $request->token)->pluck('email');
            $user = User::where('email',  $email)->update(['password' => Hash::make($request->password)]);
            PasswordResetToken::where(['email' => $email])->delete();
            return redirect('/login')->with('message', 'Your password has been changed!');
        }
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

        $userToSwitch = User::with('staff')->findOrFail($id);

        if ($userToSwitch->staff && $userToSwitch->staff->primary_staff == 1) {
            return redirect('/dashboard/profile'); 
        }

        return redirect('/admin');
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

                return redirect('/admin');
            }
        }
        return redirect('/admin');
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids');
    
        if (!$ids || !is_array($ids)) {
            return response()->json(['success' => false, 'message' => 'No Records Selected.'], 400);
        }
    
        User::whereIn('id', $ids)->delete();
    
        return response()->json(['success' => true, 'message' => 'Selected Users Deleted Successfully.']);
    }
    
}