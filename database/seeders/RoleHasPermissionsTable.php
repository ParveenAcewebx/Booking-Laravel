<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
        $admin = Role::findByName('Administrator');
        $admin->givePermissionTo(Permission::all());

        $staff = Role::findByName('Staff');
        $staff->givePermissionTo(['view', 'edit']);

        $manager = Role::findByName('Booking Manager');
        $manager->givePermissionTo(['view', 'manage']);

        $customer = Role::findByName('Customer');
        $customer->givePermissionTo(['view']);
    }
}
