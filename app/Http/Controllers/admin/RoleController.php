<?php

namespace App\Http\Controllers\admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use DataTables;

class RoleController extends Controller
{
    protected $allUsers;

    public function __construct()
    {
        $this->allUsers = User::all();
    }

    protected function syncPermissionsFromConfig()
    {
        $roleGroups = config('constants.role_groups');

        foreach ($roleGroups as $group) {
            foreach ($group['roles'] as $permissionName) {
                Permission::firstOrCreate([
                    'name' => $permissionName,
                    'guard_name' => 'web',
                ]);
            }
        }
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $roles = Role::with(['permissions', 'users'])->select('id', 'name', 'status');
            return DataTables::of($roles)
                ->addIndexColumn()
                ->addColumn('permissions', function ($role) {
                    $groupedPermissions = [];

                    foreach ($role->permissions as $permission) {
                        $parts = preg_split('/[\s_]+/', $permission->name);
                        $entity = strtolower(end($parts));
                        $groupedPermissions[$entity][] = $permission->name;
                    }

                    $html = '';
                    foreach ($groupedPermissions as $entity => $permissions) {
                        $html .= '<span class="badge badge-light-success" data-toggle="tooltip" title="' . e(implode(', ', $permissions)) . '">' .
                            ucfirst($entity) . ' (' . count($permissions) . ')</span><br>';
                    }

                    return $html ?: '';
                })
                ->editColumn('status', function ($role) {
                    return $role->status == config('constants.status.active')
                        ? '<span class="badge badge-success">Active</span>'
                        : '<span class="badge badge-danger">Inactive</span>';
                })
                ->addColumn('action', function ($role) {
                    $btn = '';

                    if (auth()->user()->can('edit roles')) {
                        $btn .= '<a href="' . route('roles.edit', $role->id) . '" class="btn btn-icon btn-success" data-toggle="tooltip" title="Edit Role">
                                    <i class="fas fa-pencil-alt"></i>
                                </a> ';
                    }

                    if (auth()->user()->can('delete roles')) {
                        if ($role->users->count() == 0) {
                            // show delete button only if no users assigned
                            $btn .= '<form action="' . route('roles.delete', $role->id) . '" method="POST" id="delete-role-' . $role->id . '" style="display:inline;">
                                        ' . csrf_field() . '
                                        <input type="hidden" name="_method" value="DELETE">
                                        <button type="button" onclick="deleteRole(' . $role->id . ')" class="btn btn-icon btn-danger" data-toggle="tooltip" title="Delete Role">
                                            <i class="feather icon-trash-2"></i>
                                        </button>
                                    </form>';
                        } else {
                            // optional: show disabled button with tooltip
                            $btn .= '<button type="button" class="btn btn-icon btn-secondary" data-toggle="tooltip" title="Cannot delete: role assigned to users" disabled>
                                        <i class="feather icon-trash-2"></i>
                                    </button>';
                        }
                    }

                    return $btn;
                })
                ->rawColumns(['permissions', 'status', 'action'])
                ->make(true);
        }
        return view('admin.role.index');
    }

    public function roleAdd()
    {
        $this->syncPermissionsFromConfig(); 
        $roleGroups = config('constants.role_groups');
        $permissions = Permission::all();
        $loginId = session('impersonate_original_user');
        $loginUser = $loginId ? User::find($loginId) : null;

        return view('admin.role.add', compact('roleGroups', 'permissions', 'loginUser'));
    }

    public function store(Request $request)
    {
        $this->syncPermissionsFromConfig(); 

        $validated = $request->validate([
            'name' => 'required|string|unique:roles,name',
            'permissions' => 'nullable|array',
            'permissions.*' => 'string',
        ]);

        $status = $request->has('status') ? 1 : 0;

        $role = Role::create([
            'name' => $validated['name'],
            'guard_name' => 'web',
            'status' => $status,
        ]);

        if (!empty($validated['permissions'])) {
            $role->syncPermissions($validated['permissions']);
        }

        return redirect()->route('roles.list')->with('success', 'Role Added Successfully.');
    }

    public function roleEdit($id)
    {
        $this->syncPermissionsFromConfig();

        $role = Role::findOrFail($id);
        $loginId = session('impersonate_original_user');
        $loginUser = $loginId ? User::find($loginId) : null;
        $rolePermissions = $role->permissions->pluck('name')->toArray();
        $roleGroups = config('constants.role_groups');
        $permissions = Permission::all();

        return view('admin.role.edit', compact('role', 'roleGroups', 'rolePermissions', 'permissions', 'loginUser'));
    }

    public function roleUpdate(Request $request, $id)
    {
        $this->syncPermissionsFromConfig(); 

        $request->validate([
            'name' => 'required|string|max:255',
            'permissions' => 'nullable|array',
            'permissions.*' => 'string|exists:permissions,name',
        ]);

        $role = Role::findOrFail($id);
        $exists = Role::where('name', $request->name)
            ->where('guard_name', 'web')
            ->where('id', '!=', $id)
            ->exists();

        if ($exists) {
            return back()->withErrors(['name' => 'The name has already been taken.'])->withInput();
        }

        $role->name = $request->name;
        $role->status = $request->has('status') ? 1 : 0;
        $role->save();

        $role->syncPermissions($request->permissions ?? []);

        return redirect()->route('roles.list')->with('success', 'Role Updated Successfully.');
    }

    public function roleDelete($id)
    {
        $role = Role::find($id);
        if ($role) {
            $role->permissions()->detach();
            $role->delete();
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false, 'message' => 'Role not found.']);
    }
}
