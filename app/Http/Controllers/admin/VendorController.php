<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Staff;
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

class VendorController extends Controller
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

            $query = User::with('roles')->whereHas('roles', function ($q) {
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

    public function add()
    {
        $roles = Role::where('name', 'Staff')->first();
        $staffUsers = User::role('staff')->get();
        $activeStatus = config('constants.status.active');
        $weekDays = config('constants.week_days');
        $phoneCountries = config('phone_countries');
        $services = Service::where('status', $activeStatus)->get();
        $loginId = session('impersonate_original_user');
        $loginUser = $loginId ? User::find($loginId) : null;
        return view('admin.staff.add', compact('roles', 'services', 'staffUsers', 'phoneCountries', 'weekDays', 'loginUser'));
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

        $avatarPath = null;
        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'avatar' => $avatarPath,
            'phone_number' => $request->phone_number,
            'phone_code' => $request->code,
            'status' => $request->status,
        ]);

        $role = Role::findById($request->role, 'web');
        $user->assignRole($role->name);

        $submittedServices = $request->input('assigned_services', []);
        if (is_array($submittedServices)) {
            foreach ($submittedServices as $serviceId) {
                StaffAssociation::create([
                    'staff_member' => $user->id,
                    'service_id' => $serviceId,
                ]);
            }
        }

        $workingHours = [];
        if ($request->has('working_days')) {
            foreach ($request->working_days as $day => $data) {
                $start = $data['start'] ?? null;
                $end = $data['end'] ?? null;

                $workingHours[$day] = [
                    'start' => $start,
                    'end' => $end,
                    'services' => $submittedServices ?? [],

                ];
            }
        }

        $dayOffsGrouped = [];
        if ($request->has('day_offs') && is_array($request->day_offs)) {
            foreach ($request->day_offs as $off) {
                $label = $off['offs'] ?? null;
                $dateRange = $off['date'] ?? null;

                if ($label && $dateRange) {
                    try {
                        [$start, $end] = explode(' - ', $dateRange);
                        $startDate = Carbon::createFromFormat('F j, Y', trim($start));
                        $endDate = Carbon::createFromFormat('F j, Y', trim($end));
                        $period = CarbonPeriod::create($startDate, $endDate);

                        $group = [];
                        foreach ($period as $date) {
                            $group[] = [
                                'label' => $label,
                                'date' => $date->format('F j, Y'),
                            ];
                        }
                        $dayOffsGrouped[] = $group;
                    } catch (\Exception $e) {
                        continue;
                    }
                }
            }
        }

        Staff::create([
            'staff_id' => $user->id,
            'work_hours' => json_encode($workingHours),
            'days_off' => json_encode($dayOffsGrouped),
        ]);
        return redirect()->route('staff.list')->with('success', 'Staff Created Successfully!');
    }
    public function edit(User $staff)
    {
        $roles = Role::where('name', 'Staff')->first();
        $phoneCountries = config('phone_countries');
        $weekDays = config('constants.week_days');
        $staffMeta = Staff::where('staff_id', $staff->id)->first();
        $assignedServices = Service::whereIn('id', StaffAssociation::where('staff_member', $staff->id)->pluck('service_id'))
            ->with('category')
            ->get();

        $services = Service::all();
        $loginId = session('impersonate_original_user');
        $loginUser = $loginId ? User::find($loginId) : null;

        $groupedDayOffs = [];
        if (!empty($staffMeta->days_off)) {
            $decoded = json_decode($staffMeta->days_off, true);
            $flattened = collect($decoded)->flatten(1);
            $groupedDayOffs = $flattened->groupBy('label')->map(function ($items, $label) {
                $dates = collect($items)->pluck('date')->sort()->values();
                $start = $dates->first();
                $end = $dates->last();

                return [
                    'label' => $label,
                    'range' => $start . ' - ' . $end
                ];
            })->values()->toArray();
        }

        return view('admin.staff.edit', compact(
            'staff',
            'roles',
            'phoneCountries',
            'assignedServices',
            'services',
            'loginUser',
            'weekDays',
            'staffMeta',
            'groupedDayOffs'
        ));
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

        $staff->name = $request->name;
        $staff->email = $request->email;
        $staff->phone_number = $request->phone_number;
        $staff->phone_code = $request->code;
        $staff->status = $request->status ? config('constants.status.active') : config('constants.status.inactive');

        // Avatar upload or removal
        if ($request->hasFile('avatar')) {
            if ($staff->avatar && Storage::disk('public')->exists($staff->avatar)) {
                Storage::disk('public')->delete($staff->avatar);
            }
            $staff->avatar = $request->file('avatar')->store('avatars', 'public');
        } elseif ($request->input('remove_avatar') == '1') {
            if ($staff->avatar && Storage::disk('public')->exists($staff->avatar)) {
                Storage::disk('public')->delete($staff->avatar);
            }
            $staff->avatar = null;
        }

        if ($request->filled('password')) {
            $staff->password = Hash::make($request->password);
        }

        $staff->save();

        $role = Role::findById($request->role, 'web');
        $staff->syncRoles([$role->name]);

        $submittedServices = $request->input('assigned_services', []);
        $staff->services()->sync($submittedServices);

        $weekDays = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
        $workingHours = [];

        foreach ($weekDays as $day) {
            $data = $request->working_days[$day] ?? [];
            $workingHours[$day] = [
                'start' => $data['start'] ?? '00:00',
                'end' => $data['end'] ?? '00:00',
                'services' => $submittedServices ?? [],

            ];
        }

        $dayOffsRaw = $request->input('day_offs', []);
        $nestedDayOffs = [];

        foreach ($dayOffsRaw as $entry) {
            $label = $entry['offs'] ?? null;
            $range = $entry['date'] ?? null;

            if ($label && $range) {
                try {
                    [$startDate, $endDate] = explode(' - ', $range);
                    $start = Carbon::createFromFormat('F j, Y', trim($startDate));
                    $end = Carbon::createFromFormat('F j, Y', trim($endDate));

                    if ($start->gt($end)) {
                        [$start, $end] = [$end, $start];
                    }

                    $block = [];
                    while ($start->lte($end)) {
                        $block[] = [
                            'label' => $label,
                            'date' => $start->format('F j, Y'),
                        ];
                        $start->addDay();
                    }

                    $nestedDayOffs[] = $block;
                } catch (\Exception $e) {
                    continue;
                }
            }
        }

        $staffMeta = Staff::firstOrNew(['staff_id' => $staff->id]);
        $staffMeta->work_hours = json_encode($workingHours);

        if (!empty($nestedDayOffs)) {
            $staffMeta->days_off = json_encode($nestedDayOffs);
        }

        $staffMeta->save();

        return redirect()->route('staff.list')->with('success', 'Staff Updated Successfully!');
    }




    public function destroy($id)
    {
        $user = User::find($id);
        $authuser_id = Auth::user()->id;

        if ($authuser_id != $id) {
            if ($user) {
                $user->delete();

                Staff::where('staff_id', $id)->delete();
                StaffAssociation::where('staff_member', $id)->delete();
                return response()->json(['success' => true]);
            }

            return response()->json(['success' => false, 'message' => 'User not found.']);
        } else {
            return response()->json(['success' => 'login', 'message' => 'Cannot delete logged-in user.']);
        }
    }
}
