<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionTable extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $roleGroups = config('constants.role_groups');

        $allPermissions = [];

        foreach ($roleGroups as $group) {
            foreach ($group['roles'] as $permission) {
                $allPermissions[] = $permission;
            }
        }

        $uniquePermissions = array_unique($allPermissions);

        foreach ($uniquePermissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web',
            ]);
        }
    }
}
