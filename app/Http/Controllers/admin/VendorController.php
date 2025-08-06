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
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;


class VendorController extends Controller
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
            // Load vendors with pivot + nested service relation
            $vendors = Vendor::with('services')->select(['id', 'name', 'email', 'status', 'created_at']);

            return DataTables::of($vendors)
                ->addIndexColumn()
                ->editColumn('name', function ($row) {
                    return e($row->name);
                })
                ->editColumn('email', function ($row) {
                    return e($row->email);
                })
                ->addColumn('services', function ($row) {
                    if ($row->services->isEmpty()) {
                        return '<span class="badge badge-secondary">No Services</span>';
                    }

                    return $row->services->map(function ($assoc) {
                        return '<span class="badge badge-info mr-1">' . e(optional($assoc->service)->name) . '</span>';
                    })->implode(' ');
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

                ->rawColumns(['services', 'status', 'action'])
                ->make(true);
        }

        return view('admin.vendor.index', compact('loginUser'));
    }

    public function add()
    {
        $allusers = $this->allUsers;
        $loginId = getOriginalUserId();
        $loginUser = $loginId ? User::find($loginId) : null;
        $phoneCountries = config('phone_countries');
        $staffRole = Role::where('name', 'staff')->first();

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
            'loginUser',
            'allService',
            'availableStaff',
            'preAssignedStaffIds',
            'phoneCountries'
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
            'phone_number' => 'required',
        ]);

        try {
            $randomPassword = Str::random(4) . rand(0, 9) . Str::random(2) . '!@#$%^&*()_+'[rand(0, 11)] . Str::random(2);
            $thumbnailPath = null;
            if ($request->hasFile('thumbnail')) {
                $thumbnailPath = $request->file('thumbnail')->store('vendors', 'public');
            }

            $user = User::create([
                'name'          => $request->username,
                'email'         => $request->email,
                'password'      => Hash::make($randomPassword),
                'phone_code'    => $request->code,
                'phone_number'  => $request->phone_number,
                'status'        => config('constants.status.active'),
            ]);
            // try {        
            //     Mail::to($user->email)->send(view('admin.vendor.partials.email', compact('user', 'randomPassword')));
            //     \Log::info('Email sent successfully to ' . $user->email);
            // } catch (\Exception $e) {
            //     \Log::error('Failed to send email to ' . $user->email . ': ' . $e->getMessage());
            //     return back()->withInput()->with('error', 'Email sending failed: ' . $e->getMessage());
            // }
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
                'user_id'       => $user->id,
                'work_hours'    => json_encode(config('constants.defaultWorkHours')),
                'days_off'      => json_encode([]),
                'primary_staff' => 1,
            ]);

            if ($request->has('assigned_service') && is_array($request->assigned_service)) {
                foreach ($request->assigned_service as $serviceId) {
                    VendorServiceAssociation::create([
                        'vendor_id'  => $lastInsertId,
                        'service_id' => $serviceId,
                    ]);
                    StaffServiceAssociation::create([
                        'staff_member'  => $user->id,
                        'service_id' => $serviceId,
                    ]);
                }
            }

            $selectedStaffIds = $request->input('select_staff', []);
            // Convert to integer values
            $selectedStaffIds = array_filter($selectedStaffIds, function ($id) {
                return !empty($id) && intval($id) > 0;
            });
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

        $loginId = getOriginalUserId();
        $loginUser = $loginId ? User::find($loginId) : null;

        $phoneCountries = config('phone_countries');
        $vendor = Vendor::findOrFail($id);

        $getUserDetails = User::where('email', $vendor->email)->first();
        // 2. Vendor staff associations (with user + primary flag)
        $staffAssociation = VendorStaffAssociation::where('vendor_id', $id)
            ->with(['user:id,name,email', 'staff:id,user_id,primary_staff'])
            ->get();
        // 3. Vendor's assigned services
        $gsd = VendorServiceAssociation::where('vendor_id', $id)->pluck('service_id')->toArray();
        $allService = Service::where('status', config('constants.status.active'))->get();
        $staffRole = Role::where('name', 'staff')->first();

        // 6. All users with staff role
        $staffUsers = User::whereHas('roles', function ($query) use ($staffRole) {
            $query->where('id', $staffRole->id);
        })->get();
        $staffTableUserIds = Staff::pluck('user_id')->toArray();

        // 8. Pre-assigned staff to this vendor
        $assignedUserIds = $staffAssociation->pluck('user_id')->toArray();

        // 9. Staff users not in staff table (fresh staff)
        $roleStaffNotInStaffTable = $staffUsers->whereNotIn('id', $staffTableUserIds);

        // 10. Staff free from vendor_staff_associations (vendor_id NULL)
        $vendorFreeStaffIds = VendorStaffAssociation::whereNull('vendor_id')
            ->whereIn('user_id', $staffUsers->pluck('id'))
            ->pluck('user_id')
            ->toArray();

        // 11. Staff who are not in vendor_staff_associations at all (fresh + role=staff)
        $vendorAssociationUserIds = VendorStaffAssociation::pluck('user_id')->toArray();
        $freshRoleStaffIds = $staffUsers->pluck('id')
            ->diff($vendorAssociationUserIds)
            ->toArray();

        // 12. Merge: preassigned + free + fresh
        $mergedAvailableIds = array_unique(array_merge(
            $roleStaffNotInStaffTable->pluck('id')->toArray(),
            $vendorFreeStaffIds,
            $freshRoleStaffIds,
            $assignedUserIds
        ));

        // 13. Fetch available staff list
        $availableStaff = User::whereIn('id', $mergedAvailableIds)
            ->with('staff:id,user_id,primary_staff')
            ->get();
        // dd($availableStaff);
        // 14. Determine current primary staff (for switch dropdown preselect)
        $currentPrimary = $staffAssociation->firstWhere('staff.primary_staff', 1);

        // 15. Pre-assigned staff IDs for edit form
        $preAssignedStaffIds = $staffAssociation->pluck('user_id')->toArray();
        // $firstStaff = $availableStaff->first();

        return view('admin.vendor.edit', compact(
            'vendor',
            'staffAssociation',
            'availableStaff',
            'preAssignedStaffIds',
            'currentPrimary',
            'gsd',
            'allService',
            'loginUser',
            'phoneCountries',
            'getUserDetails'
        ));
    }

    public function getStaffServices($staffId)
    {
        $services = StaffServiceAssociation::join('services', 'services.id', '=', 'staff_service_associations.service_id')
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

        // --- Update vendor basic fields ---
        $status = $request->input('status') ? config('constants.status.active') : config('constants.status.inactive');
        $vendor->name                   = $request->input('username');
        $vendor->email                  = $request->input('email');
        $vendor->description            = $request->input('description');
        $phone_number                   = $request->input('phone_number');
        $code                           = $request->input('code');
        $vendor->status                 = $status;
        $vendor->stripe_mode            = $request->stripe_mode;
        $vendor->stripe_test_site_key   = $request->stripe_test_site_key;
        $vendor->stripe_test_secret_key = $request->stripe_test_secret_key;
        $vendor->stripe_live_site_key   = $request->stripe_live_site_key;
        $vendor->stripe_live_secret_key = $request->stripe_live_secret_key;

        // ====================================================
        // Primary Staff Handling
        // ====================================================
        if (!empty($phone_number) || !empty($code)) {
            $loginId = VendorStaffAssociation::where('vendor_id', $vendor->id)->pluck('user_id');
            if ($loginId->isNotEmpty()) {
                $loginUser = User::find($loginId->first());
                if ($loginUser) {
                    $dataToUpdate = [];

                    if (!empty($phone_number)) {
                        $dataToUpdate['phone_number'] = $phone_number;
                    }

                    if (!empty($code)) {
                        $dataToUpdate['phone_code'] = $code;
                    }
                    $loginUser->update($dataToUpdate);
                }
            }
        }


        $newPrimaryStaffId = $request->input('primary_staff');

        if ($newPrimaryStaffId) {
            // Get current primary staff for this vendor
            $currentPrimary = Staff::where('primary_staff', 1)
                ->whereIn('user_id', VendorStaffAssociation::where('vendor_id', $vendor->id)->pluck('user_id'))
                ->first();

            // If different, switch primary
            if (!$currentPrimary || $currentPrimary->user_id != $newPrimaryStaffId) {
                // Remove primary flag from old primary staff
                if ($currentPrimary) {
                    $currentPrimary->primary_staff = 0;
                    $currentPrimary->save();
                }

                // Assign primary to the new staff
                $newPrimaryStaff = Staff::where('user_id', $newPrimaryStaffId)->first();

                if ($newPrimaryStaff) {
                    // Update if exists
                    $newPrimaryStaff->primary_staff = 1;
                    $newPrimaryStaff->save();
                } else {
                    // Create with default work hours if doesn't exist
                    Staff::create([
                        'user_id'       => $newPrimaryStaffId,
                        'work_hours'    => json_encode(config('constants.defaultWorkHours')),
                        'days_off'      => json_encode([]),
                        'primary_staff' => 1,
                    ]);
                }

                // Ensure primary staff is linked to vendor in VendorStaffAssociation
                $linkExists = VendorStaffAssociation::where('vendor_id', $vendor->id)
                    ->where('user_id', $newPrimaryStaffId)
                    ->exists();

                if (!$linkExists) {
                    VendorStaffAssociation::create([
                        'vendor_id' => $vendor->id,
                        'user_id'   => $newPrimaryStaffId,
                    ]);
                }
            }
        }

        // ====================================================
        // Vendor Staff Association
        // ====================================================
        $selectedStaffIds = $request->input('select_staff', []);
        $selectedStaffIds = array_filter($selectedStaffIds, fn($id) => !empty($id) && intval($id) > 0);
        $selectedStaffIds = array_map('intval', $selectedStaffIds);

        // Remove associations not in request
        VendorStaffAssociation::where('vendor_id', $vendor->id)
            ->whereNotIn('user_id', $selectedStaffIds)
            ->delete();

        // Assign selected staff to vendor
        foreach ($selectedStaffIds as $userId) {
            $staffAssoc = VendorStaffAssociation::where('user_id', $userId)->first();
            $staffNotExsits = Staff::where('user_id', $userId)->first();
            if (!$staffNotExsits) {
                Staff::create([
                    'user_id'       => $userId,
                    'work_hours'    => json_encode(config('constants.defaultWorkHours')),
                    'days_off'      => json_encode([]),
                    'primary_staff' => 0,
                ]);
            }
            if ($staffAssoc) {
                $staffAssoc->vendor_id = $vendor->id;
                $staffAssoc->save();
            } else {
                VendorStaffAssociation::create([
                    'user_id'   => $userId,
                    'vendor_id' => $vendor->id,
                ]);
            }
        }

        // ====================================================
        // Vendor Service Association
        // ====================================================
        $assignedServices = $request->input('assigned_service', []);
        $assignedServices = array_map('intval', $assignedServices);

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

        // ====================================================
        // Handle Thumbnail Upload/Remove
        // ====================================================
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
