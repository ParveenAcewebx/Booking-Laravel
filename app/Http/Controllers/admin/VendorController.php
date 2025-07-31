<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Staff;
use App\Models\Vendor;
use App\Models\VendorStaffAssociation;
use App\Models\Service;
use App\Models\VendorServiceAssociation;
use App\Models\StaffServiceAssociation;
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
use Illuminate\Support\Facades\DB;


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
            $vendors = Vendor::select(['id', 'name', 'email', 'description', 'status', 'created_at']);

            return DataTables::of($vendors)
                ->addIndexColumn()

                ->editColumn('name', function ($row) {
                    return '<td>' . e($row->name) . '</td>';
                })

                ->editColumn('email', function ($row) {
                    return $row->email;
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
                ->rawColumns(['name', 'email', 'status', 'action'])
                ->make(true);
        }
        return view('admin.vendor.index', compact('loginUser'));
    }

    public function add()
    {
        $allusers = $this->allUsers;
        $originalUserId = $this->originalUserId;
        $loginId = session('impersonate_original_user');
        $loginUser = $loginId ? User::find($loginId) : null;

        // Get Staff role
        $staffRole = Role::where('name', 'staff')->first();

        // All users with staff role
        $staffUsers = User::whereHas('roles', function ($query) use ($staffRole) {
            $query->where('id', $staffRole->id);
        })->get();

        // IDs present in Staff table
        $staffTableUserIds = VendorStaffAssociation::pluck('user_id')->toArray();
        // Staff users NOT in Staff table (fresh staff)
        $freshStaffUserIds = $staffUsers->whereNotIn('id', $staffTableUserIds)
            ->pluck('id')
            ->toArray();

        // Staff already assigned in VendorStaffAssociation (exclude these)
        $assignedStaffUserIds = VendorStaffAssociation::pluck('user_id')->toArray();

        // Staff whose vendor_id is NULL (free/unassigned staff from Staff table)
        $unassignedStaffUserIds = Staff::whereIn('user_id', $staffUsers->pluck('id'))
            ->whereNotIn('user_id', $assignedStaffUserIds) // exclude already linked in association
            ->pluck('user_id')
            ->toArray();

        /**
         * Merge fresh staff + unassigned staff
         * Final available staff list
         */
        $availableStaffIds = array_unique(array_merge(
            $freshStaffUserIds,
            $unassignedStaffUserIds
        ));

        $availableStaff = User::whereIn('id', $availableStaffIds)->get();

        // No preassigned staff for Add page
        $preAssignedStaffIds = [];

        $allService = Service::where('status', config('constants.status.active'))->get();
        $roles = Role::select('id', 'name')->get();

        return view('admin.vendor.add', compact(
            'roles',
            'allusers',
            'originalUserId',
            'loginUser',
            'allService',
            'availableStaff',
            'preAssignedStaffIds'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'username'    => 'required|string|max:255',
            'email'       => 'required|email|unique:users,email',
            'description' => 'nullable|string',
            'assigned_service'  => 'nullable|exists:services,id',
            'thumbnail'   => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            $thumbnailPath = null;
            if ($request->hasFile('thumbnail')) {
                $thumbnailPath = $request->file('thumbnail')->store('vendors', 'public');
            }

            $user = User::create([
                'name'          => $request->username,
                'email'         => $request->email,
                'password'      => Hash::make('password'),
                'status'        => config('constants.status.active'),
            ]);

            $user->assignRole('Staff');

            $vendor = Vendor::create([
                'name'        => $request->username,
                'email'       => $request->email,
                'description' => $request->description,
                'status'      => $request->status ? config('constants.status.active') : config('constants.status.inactive'),
                'thumbnail'   => $thumbnailPath,
                'stripe_mode' => $request->stripe_mode,
                'stripe_test_site_key' => $request->stripe_test_site_key,
                'stripe_test_secret_key' => $request->stripe_test_secret_key,
                'stripe_live_site_key' => $request->stripe_live_site_key,
                'stripe_live_secret_key' => $request->stripe_live_secret_key,
            ]);

            $lastInsertId = $vendor->id;

            VendorStaffAssociation::create([
                'vendor_id'   => $lastInsertId,
                'user_id'     => $user->id,
            ]);

            Staff::create([
                'user_id' => $user->id,
                'primary_staff' => 1,
            ]);

            if ($request->has('assigned_service') && is_array($request->assigned_service)) {
                foreach ($request->assigned_service as $serviceId) {
                    VendorServiceAssociation::create([
                        'vendor_id'  => $lastInsertId,
                        'service_id' => $serviceId,
                    ]);
                }
            }

            $selectedStaffIds = $request->input('select_staff', []);
            // Convert to integer values
            $selectedStaffIds = array_map('intval', $selectedStaffIds);

            foreach ($selectedStaffIds as $userId) {
                // Skip if blank (in case empty option submitted)
                if (!$userId) continue;

                $staff = VendorStaffAssociation::where('user_id', $userId)->first();

                if ($staff) {
                    // Update existing record with vendor_id
                    $staff->vendor_id = $vendor->id;
                    $staff->save();
                } else {
                    // Create new staff record if doesn't exist
                    VendorStaffAssociation::create([
                        'user_id'   => $userId,
                        'vendor_id' => $vendor->id,
                    ]);
                }
            }
            return redirect()->route('vendors.list')->with('success', 'Vendor Created Successfully.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        // Get vendor
        $vendor = Vendor::findOrFail($id);

        // Vendor staff associations + user + primary_staff (via relationship)
        $staffAssociation = VendorStaffAssociation::where('vendor_id', $id)
            ->with(['user:id,name,email', 'staff:id,user_id,primary_staff'])
            ->get();

        // Vendor's service IDs
        $gsd = VendorServiceAssociation::where('vendor_id', $id)->pluck('service_id')->toArray();

        // All active services
        $allService = Service::where('status', config('constants.status.active'))->get();

        // Staff role
        $staffRole = Role::where('name', 'staff')->first();

        // All users with staff role
        $staffUsers = User::whereHas('roles', function ($query) use ($staffRole) {
            $query->where('id', $staffRole->id);
        })->get();

        // Staff table user IDs
        $staffTableUserIds = Staff::pluck('user_id')->toArray();

        // Pre-assigned staff to this vendor
        $assignedUserIds = $staffAssociation->pluck('user_id')->toArray();

        // Staff users not in staff table (fresh staff)
        $roleStaffNotInStaffTable = $staffUsers->whereNotIn('id', $staffTableUserIds);

        // Staff whose vendor_id is NULL (free staff from vendor_staff_associations)
        $vendorFreeStaffIds = VendorStaffAssociation::whereNull('vendor_id')
            ->whereIn('user_id', $staffUsers->pluck('id'))
            ->pluck('user_id')
            ->toArray();

        // Staff who are not in vendor_staff_associations at all (fresh + role=staff)
        $vendorAssociationUserIds = VendorStaffAssociation::pluck('user_id')->toArray();
        $freshRoleStaffIds = $staffUsers->pluck('id')
            ->diff($vendorAssociationUserIds)
            ->toArray();

        // Merge: preassigned + free + fresh
        $mergedAvailableIds = array_unique(array_merge(
            $roleStaffNotInStaffTable->pluck('id')->toArray(),
            $vendorFreeStaffIds,
            $freshRoleStaffIds,
            $assignedUserIds
        ));

        // Fetch available staff list
        $availableStaff = User::whereIn('id', $mergedAvailableIds)
            ->with('staff:id,user_id,primary_staff') // include primary_staff flag
            ->get();

        // Roles list
        $roles = Role::all();

        // Current impersonation info
        $loginId = session('impersonate_original_user');
        $loginUser = $loginId ? User::find($loginId) : null;

        // Vendor-related service associations (if used)
        $serviceIds = StaffServiceAssociation::where('staff_member', $id)->pluck('service_id');
        $allServiceData = Service::whereIn('id', $serviceIds)->get();

        // Assigned role
        $assignedRole = $vendor->user?->roles->first() ?? $roles->firstWhere('name', 'staff');
        $selectedRoleId = $assignedRole?->id;

        // Pre-assigned staff IDs for edit form
        $preAssignedStaffIds = $staffAssociation->pluck('user_id')->toArray();

        return view('admin.vendor.edit', compact(
            'vendor',
            'roles',
            'loginUser',
            'selectedRoleId',
            'allServiceData',
            'staffAssociation',
            'availableStaff',
            'preAssignedStaffIds',
            'gsd',
            'allService'
        ));
    }

    public function getStaffServices($staffId)
    {
        $services = DB::table('staff_service_associations')
            ->join('services', 'services.id', '=', 'staff_service_associations.service_id')
            ->where('staff_service_associations.staff_member', $staffId)
            ->pluck('services.name');
        return response()->json($services);
    }

    public function update(Request $request, Vendor $vendor)
    {
        $request->validate([
            'username'       => 'required|string|max:255',
            'email'          => 'required|email|max:255|unique:vendors,email,' . $vendor->id,
            'description'    => 'nullable|string',
            'status'         => 'required|boolean',
            'thumbnail'      => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'remove_avatar'  => 'nullable|in:0,1',
        ]);

        $status                             = $request->input('status') ? config('constants.status.active') : config('constants.status.inactive');
        $vendor->name                       = $request->input('username');
        $vendor->email                      = $request->input('email');
        $vendor->description                = $request->input('description');
        $vendor->status                     = $status;
        $vendor->stripe_mode                = $request->stripe_mode;
        $vendor->stripe_test_site_key       = $request->stripe_test_site_key;
        $vendor->stripe_test_secret_key     = $request->stripe_test_secret_key;
        $vendor->stripe_live_site_key       = $request->stripe_live_site_key;
        $vendor->stripe_live_secret_key     = $request->stripe_live_secret_key;

        $selectedStaffIds = $request->input('select_staff', []);

        // Convert to integers
        $selectedStaffIds = array_map('intval', $selectedStaffIds);

        // Step 1: Remove vendor_id from staff NOT in request
        VendorStaffAssociation::where('vendor_id', $vendor->id)
            ->whereNotIn('user_id', $selectedStaffIds)
            ->delete();
        // Step 2: Assign vendor_id to selected staff
        foreach ($selectedStaffIds as $userId) {
            $staff = VendorStaffAssociation::where('user_id', $userId)->first();

            if ($staff) {
                // Update existing record
                $staff->vendor_id = $vendor->id;
                $staff->save();
            } else {
                // Create new record
                VendorStaffAssociation::create([
                    'user_id'   => $userId,
                    'vendor_id' => $vendor->id,
                ]);
            }
        }
        $assignedServices = $request->input('assigned_service', []);
        $assignedServices = array_map('intval', $assignedServices);

        // Get Services  Fetch From VendorAssociation
        $existingServices = VendorServiceAssociation::where('vendor_id', $vendor->id)
            ->pluck('service_id')
            ->toArray();

        if (empty($assignedServices)) {
            VendorServiceAssociation::where('vendor_id', $vendor->id)->delete();
        } else {
            $servicesToDelete = array_diff($existingServices, $assignedServices);
            if (!empty($servicesToDelete)) {
                VendorServiceAssociation::where('vendor_id', $vendor->id)
                    ->whereIn('service_id', $servicesToDelete)
                    ->delete();
            }
            $servicesToAdd = array_diff($assignedServices, $existingServices);
            foreach ($servicesToAdd as $serviceId) {
                VendorServiceAssociation::create([
                    'vendor_id'  => $vendor->id,
                    'service_id' => $serviceId,
                ]);
            }
        }

        if ($request->hasFile('thumbnail')) {
            if ($vendor->thumbnail && Storage::disk('public')->exists($vendor->thumbnail)) {
                Storage::disk('public')->delete($vendor->thumbnail);
            }
            $vendor->thumbnail = $request->file('thumbnail')->store('vendors', 'public');
        } elseif ($request->input('remove_avatar') == '1') {
            if ($vendor->thumbnail && Storage::disk('public')->exists($vendor->thumbnail)) {
                Storage::disk('public')->delete($vendor->thumbnail);
            }
            $vendor->thumbnail = null;
        }
        $vendor->save();
        return redirect()->route('vendors.list')->with('success', 'Vendor Updated Successfully.');
    }

    public function destroy($vendorId)
    {
        $vendor = Vendor::find($vendorId);

        if (!$vendor) {
            return response()->json(['success' => false, 'message' => 'Vendor not found.']);
        }

        // Delete all staff associations for this vendor
        $associations = VendorStaffAssociation::where('vendor_id', $vendorId)->get();

        foreach ($associations as $association) {
            // Check if this user is primary staff
            $isPrimaryStaff = Staff::where('user_id', $association->user_id)
            ->where('primary_staff', 1)
            ->exists();
            

            // Delete user only if NOT primary staff
            if ($isPrimaryStaff) {
                Staff::where('user_id', $association->user_id)->delete();
                User::where('id', $association->user_id)->delete();
            }

            $association->delete();
        }

        // Delete vendor thumbnail if exists
        if ($vendor->thumbnail && Storage::disk('public')->exists($vendor->thumbnail)) {
            Storage::disk('public')->delete($vendor->thumbnail);
        }

        // Finally delete vendor
        $vendor->delete();

        return response()->json(['success' => true, 'message' => 'Vendor Deleted Successfully.']);
    }
}
