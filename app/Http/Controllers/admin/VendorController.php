<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Staff;
use App\Models\Vendor;
use App\Models\Service;
use App\Models\StaffAssociation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cookie;

class VendorController extends Controller
{
    protected $allUsers;
    protected $originalUserId;
    public function __construct()
    {
        $this->allUsers = User::all();
        $this->originalUserId = session()->has('impersonate_original_user')
            ? session('impersonate_original_user')
            : Cookie::get('impersonate_original_user');
    }

    public function index(Request $request)
    {
        $loginId = session('impersonate_original_user');
        $loginUser = $loginId ? User::find($loginId) : null;

        if ($request->ajax()) {
            $vendors = Vendor::select(['id', 'name', 'description', 'status', 'created_at']);

            return DataTables::of($vendors)
                ->addIndexColumn()

                ->editColumn('name', function ($row) {
                    return '<td>' . e($row->name) . '</td>';
                })

                ->editColumn('description', function ($row) {
                    return $row->description;
                })

                ->editColumn('created_at', function ($row) {
                    return $row->created_at ? $row->created_at->format('Y-m-d H:i:s') : '';
                })

                ->editColumn('status', function ($row) {
                    return $row->status
                        ? '<span class="badge badge-success">Active</span>'
                        : '<span class="badge badge-danger">Inactive</span>';
                })

                ->addColumn('action', function ($row) {
                    $btn = '';

                    if (auth()->user()->can('edit vendors')) {
                        $btn .= '<a href="' . route('vendors.edit', $row->id) . '" class="btn btn-icon btn-success" data-toggle="tooltip" title="Edit Vendor">
            <i class="fas fa-pencil-alt"></i>
        </a> ';
                    }

                    if (auth()->user()->can('delete vendors')) {
                        $btn .= '<form id="delete-vendor-' . $row->id . '" action="' . route('vendors.delete', $row->id) . '" method="POST" style="display:inline;">';
                        $btn .= csrf_field();
                        $btn .= method_field('DELETE');
                        $btn .= '<button type="button" class="btn btn-icon btn-danger" onclick="deleteVendor(' . $row->id . ', event)" title="Delete">';
                        $btn .= '<i class="feather icon-trash-2"></i>';
                        $btn .= '</button>';
                        $btn .= '</form>';
                    }

                    return $btn;
                })
                ->rawColumns(['name', 'description', 'status', 'action'])
                ->make(true);
        }
        return view('admin.vendor.index',compact('loginUser'));
    }

    public function add()
    {
        $allusers = $this->allUsers;
        $originalUserId = $this->originalUserId;
        $loginId = session('impersonate_original_user');
        $loginUser = null;

        if ($loginId) {
            $loginUser = User::find($loginId);
        }

        $roles = Role::select('id', 'name')->get();
        return view('admin.vendor.add', compact('roles', 'allusers', 'originalUserId', 'loginUser'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'username'    => 'required|string|max:255',
            'email'       => 'required|email|unique:users,email',
            'role_id'     => 'required|exists:roles,id',
            'status'      => 'required|boolean',
            'description' => 'nullable|string',
            'thumbnail'   => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            $thumbnailPath = null;
            if ($request->hasFile('thumbnail')) {
                $thumbnailPath = $request->file('thumbnail')->store('vendors', 'public');
            }

            $role = Role::findOrFail($request->role_id);
            $isPrimaryStaff = strtolower($role->name) === 'staff' ? 1 : 0;

            $user = User::create([
                'name'          => $request->username,
                'email'         => $request->email,
                'password'      => Hash::make('password'),
                'primary_staff' => $isPrimaryStaff,
                'status'        => config('constants.status.active'),
            ]);

            $user->assignRole($role->name);
            Vendor::create([
                'user_id'     => $user->id,
                'name'        => $request->username,
                'email'       => $request->email,
                'description' => $request->description,
                'status'      => $request->status ? config('constants.status.active') : config('constants.status.inactive'),
                'thumbnail'   => $thumbnailPath,
            ]);

            return redirect()->route('vendors.list')->with('success', 'Vendor created successfully.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $vendor = Vendor::with('user.roles')->findOrFail($id);
        $roles = Role::all();
        $loginId = session('impersonate_original_user');
        $loginUser = $loginId ? User::find($loginId) : null;

        $assignedRole = $vendor->user?->roles->first();
        if (!$assignedRole) {
            $assignedRole = $roles->firstWhere('name', 'staff');
        }

        $selectedRoleId = $assignedRole?->id;
        return view('admin.vendor.edit', compact('vendor', 'roles', 'loginUser', 'selectedRoleId'));
    }


    public function update(Request $request, Vendor $vendor)
    {
        $request->validate([
            'username'       => 'required|string|max:255',
            'description'    => 'nullable|string',
            'role_id'        => 'required|exists:roles,id',
            'status'         => 'required|boolean',
            'thumbnail'      => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'remove_avatar'  => 'nullable|in:0,1',
        ]);

        $role = Role::findOrFail($request->input('role_id'));
        $status = $request->input('status') ? config('constants.status.active') : config('constants.status.inactive');
        $isPrimaryStaff = strtolower($role->name) === 'staff' ? 1 : 0;

        $vendor->name        = $request->input('username');
        $vendor->email       = $request->input('email');
        $vendor->description = $request->input('description');
        $vendor->status      = $status;

        if ($request->hasFile('thumbnail')) {
            if ($vendor->thumbnail && Storage::exists($vendor->thumbnail)) {
                Storage::delete($vendor->thumbnail);
            }
            $vendor->thumbnail = $request->file('thumbnail')->store('vendors', 'public');
        } elseif ($request->input('remove_avatar') == '1') {
            if ($vendor->thumbnail && Storage::exists($vendor->thumbnail)) {
                Storage::delete($vendor->thumbnail);
            }
            $vendor->thumbnail = null;
        }
        $vendor->save();
        $user = User::find($vendor->user_id);
        if ($user) {
            $user->status        = config('constants.status.active');
            $user->primary_staff = $isPrimaryStaff;
            $user->save();

            $user->syncRoles([$role->name]);
        }
        return redirect()->route('vendors.list')->with('success', 'Vendor Updated Successfully.');
    }

    public function destroy($id)
    {
        $vendor = Vendor::find($id);
        if (!$vendor) {
            return response()->json(['success' => false, 'message' => 'Vendor not found.']);
        }

        $userId = $vendor->user_id;
        $user = User::find($userId);
        if ($user) {
            $user->delete();
        }
        $vendor->delete();
        return response()->json(['success' => true, 'message' => 'Vendor deleted successfully.']);
    }
}
