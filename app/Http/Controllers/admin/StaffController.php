<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;

class StaffController extends Controller
{
    protected $allUsers;

    public function __construct()
    {
        $this->allUsers = User::all();
    }

    public function index(Request $request)
    {
        $loginId = session('impersonate_original_user');
        $loginUser = $loginId ? User::find($loginId) : null;
        if ($request->ajax()) {
            $currentUser = Auth::user();
            $statusLabels = array_flip(config('constants.status'));

            $query = User::with('roles')
                ->whereHas('roles', function ($q) {
                    $q->where('name', 'Staff');
                });

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('name', function ($row) {
                    return '<h6 class="m-b-0">' . e($row->name) . '</h6><p class="m-b-0">' . e($row->email) . '</p>';
                })
                ->editColumn('created_at', function ($row) {
                    return $row->created_at ? $row->created_at->format('Y-m-d H:i:s') : '';
                })
                ->addColumn('status', function ($row) use ($statusLabels) {
                    return $row->status == config('constants.status.active')
                        ? '<span class="badge badge-success">Active</span>'
                        : '<span class="badge badge-danger">Inactive</span>';
                })
                ->addColumn('action', function ($row) use ($currentUser) {
                    $btn = '';

                    if ($currentUser->can('edit staffs')) {
                        $btn .= '<a href="' . route('staff.edit', [$row->id]) . '" class="btn btn-icon btn-success" data-toggle="tooltip" title="Edit User">
                            <i class="fas fa-pencil-alt"></i>
                         </a> ';
                    }

                    if ($currentUser->can('delete staffs') && Auth::id() != $row->id) {
                        $btn .= '<form action="' . route('user.delete', [$row->id]) . '" method="POST" style="display:inline;" id="deleteUser-' . $row->id . '">
                            ' . csrf_field() . '
                            <input type="hidden" name="_method" value="DELETE">
                            <button type="button" onclick="return deleteUser(' . $row->id . ')" class="btn btn-icon btn-danger" data-toggle="tooltip" title="Delete User">
                                <i class="feather icon-trash-2"></i>
                            </button>
                        </form>';
                    }

                    return $btn;
                })
                ->rawColumns(['name', 'status', 'action'])
                ->make(true);
        }

        return view('admin.staff.index', compact('loginUser'));
    }

    public function create()
    {
        $roles = Role::all();
        $weekDays = config('constants.week_days');
        $phoneCountries = collect(config('phone_countries'))->unique('code')->values();
        $services = Service::all();
        $loginId = session('impersonate_original_user');
        $loginUser = $loginId ? User::find($loginId) : null;

        return view('admin.staff.add', compact('roles', 'services', 'phoneCountries', 'weekDays', 'loginUser'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'role' => 'required',
            'phone_number' => 'required'
        ]);

        $fullPhoneNumber = $request->code . $request->phone_number;

        $avatarPath = null;
        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'avatar' => $avatarPath,
            'phone_number' => $fullPhoneNumber,
            'status' => $request->has('status') ? config('constants.status.active') : config('constants.status.inactive'),
        ]);

        $role = Role::findById($request->role, 'web');
        $user->assignRole($role->name);

        return redirect()->route('staff.list')->with('success', 'Staff Created Successfully!');
    }

    public function edit(User $staff)
    {
        $roles = Role::all();
        $phoneCountries = collect(config('phone_countries'))->unique('code')->values();
        $services = Service::with('category')->get();
        $loginId = session('impersonate_original_user');
        $loginUser = $loginId ? User::find($loginId) : null;

        return view('admin.staff.edit', compact('staff', 'roles', 'services', 'phoneCountries', 'loginUser'));
    }

    public function update(Request $request, User $staff)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $staff->id,
            'password' => 'nullable|min:6|confirmed',
            'role' => 'required',
            'phone_number' => 'required',
        ]);

        $fullPhoneNumber = $request->code . $request->phone_number;

        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $staff->avatar = $avatarPath;
        }

        $staff->name = $request->name;
        $staff->email = $request->email;
        $staff->phone_number = $fullPhoneNumber;
        $staff->status = $request->has('status') ? config('constants.status.active') : config('constants.status.inactive');

        if ($request->filled('password')) {
            $staff->password = Hash::make($request->password);
        }

        $staff->save();

        $role = Role::findById($request->role, 'web');
        $staff->syncRoles([$role->name]);

        return redirect()->route('staff.list')->with('success', 'Staff Updated Successfully!');
    }

    public function destroy($id)
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
}
