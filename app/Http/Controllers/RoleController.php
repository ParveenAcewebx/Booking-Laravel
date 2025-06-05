<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    public function index()
    {
        $allroles = Role::with('permissions')->get();
        return view('role.index', ['allroles' => $allroles]);
    }

    public function roleAdd()
    {
        $roleGroups = config('constants.role_groups');
        return view('role.add', compact('roleGroups'));
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
        $role = Role::findOrFail($id);
        $rolePermissions = $role->permissions->pluck('name')->toArray();
        $roleGroups = config('constants.role_groups');
        return view('role.edit', compact('role', 'roleGroups', 'rolePermissions'));
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
