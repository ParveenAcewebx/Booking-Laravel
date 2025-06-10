<?php

namespace App\Http\Controllers;

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

    public function index(Request $request)
    {
        $loginId = session('previous_login_id');
        $loginUser = null;

        if ($loginId) {
            $loginUser = User::find($loginId);
        }

        // Handle Ajax call for DataTable
        if ($request->ajax()) {
            $roles = Role::with('permissions')->select('id', 'name', 'status'); // Explicit select!

            return DataTables::of($roles)
                ->addColumn('permissions', function ($role) {
                    $groupedPermissions = [];

                    foreach ($role->permissions as $permission) {
                        $parts = preg_split('/[\s_]+/', $permission->name);
                        $entity = strtolower(end($parts));

                        if (!isset($groupedPermissions[$entity])) {
                            $groupedPermissions[$entity] = [];
                        }

                        $groupedPermissions[$entity][] = $permission->name;
                    }

                    $html = '';
                    foreach ($groupedPermissions as $entity => $permissions) {
                        $permissionList = implode(', ', $permissions);
                        $html .= '<span class="badge badge-light-success" data-toggle="tooltip" data-placement="right" title="' . e($permissionList) . '" style="cursor: pointer;">'
                            . ucfirst($entity) . ' (' . count($permissions) . ')</span><br>';
                    }

                    return $html ?: '-';
                })
                ->editColumn('status', function ($role) {
                    if ($role->status == config('constants.status.active')) {
                        return '<span class="badge badge-success">Active</span>';
                    } else {
                        return '<span class="badge badge-danger">Inactive</span>';
                    }
                })
                ->addColumn('action', function ($role) {
                    $btn = '';

                    if (auth()->user()->can('edit roles')) {
                        $btn .= '<a href="' . route('roles.edit', $role->id) . '" class="btn btn-icon btn-success" data-toggle="tooltip" data-placement="top" title="Edit Role">
                                    <i class="fas fa-pencil-alt"></i>
                                 </a> ';
                    }

                    if (auth()->user()->can('delete roles')) {
                        $btn .= '<form action="' . route('roles.delete', $role->id) . '" method="POST" id="delete-role-' . $role->id . '" style="display:inline-block;">
                                    ' . csrf_field() . method_field('DELETE') . '
                                    <button class="btn btn-icon btn-danger" data-toggle="tooltip" data-placement="top" title="Delete Role" onclick="return confirm(\'Are you sure to delete this role?\');">
                                        <i class="feather icon-trash-2"></i>
                                    </button>
                                 </form> ';
                    }

                    return $btn ?: '-';
                })
                ->rawColumns(['permissions', 'status', 'action'])
                ->make(true);
        }

        return view('role.index', compact('loginUser'));
    }

    public function roleAdd()
    {
        $roleGroups = config('constants.role_groups');
        $allusers  = $this->allUsers;
        $loginId = session('previous_login_id');
        $loginUser = null;

        if ($loginId) {
            $loginUser = User::find($loginId);
        }
        return view('role.add', compact('roleGroups', 'allusers', 'loginUser'));
    }

    public function store(Request $request)
    {
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
        return redirect()->route('roles.list')->with('success', 'Role created successfully.');
    }

    public function roleDelete($id)
    {
        $role = Role::find($id);
        if ($role) {
            $role->permissions()->detach();
            $role->delete();
            return redirect()->route('roles.list')->with('success', 'Role and associated permissions deleted successfully!');
        } else {
            return redirect()->route('roles.list')->with('error', 'Role not found.');
        }
    }

    public function roleEdit($id)
    {
        $allusers  = $this->allUsers;
        $role = Role::findOrFail($id);
        $loginId = session('previous_login_id');
        $loginUser = null;

        if ($loginId) {
            $loginUser = User::find($loginId);
        }
        $rolePermissions = $role->permissions->pluck('name')->toArray();
        $roleGroups = config('constants.role_groups');
        return view('role.edit', compact('role', 'roleGroups', 'rolePermissions', 'allusers', 'loginUser'));
    }

    public function roleUpdate(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'permissions' => 'nullable|array',
            'permissions.*' => 'string|exists:permissions,name',
            'status' => 'nullable|boolean',
        ]);
        $role = Role::findOrFail($id);
        $exists = Role::where('name', $request->name)
            ->where('guard_name', 'web')
            ->where('id', '!=', $id)
            ->exists();
        if ($exists) {
            return back()
                ->withErrors(['name' => 'The name has already been taken.'])
                ->withInput();
        }
        $role->name = $request->name;
        $role->status = $request->has('status') ? 1 : 0;
        $role->save();
        $permissionNames = $request->permissions ?? [];
        $role->syncPermissions($permissionNames);
        return redirect()->route('roles.list')->with('success', 'Role updated successfully!');
    }
}
