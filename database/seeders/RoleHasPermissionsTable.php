<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleHasPermissionsTable extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $roleGroups = config('constants.role_groups');

        $admin = Role::findByName('Administrator');
        $admin->givePermissionTo(Permission::all());

        $staff = Role::findByName('Staff');
        $staff->givePermissionTo(
            array_merge(
                $roleGroups['bookings']['roles'],
                $roleGroups['templates']['roles']
            )
        );

        $manager = Role::findByName('Booking Manager');
        $manager->givePermissionTo($roleGroups['bookings']['roles']);

        $customer = Role::findByName('Customer');
        $customer->givePermissionTo([
        ]);
    }
}
