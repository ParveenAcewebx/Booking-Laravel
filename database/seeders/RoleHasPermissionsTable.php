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
        $staff->givePermissionTo(['view bookings', 'create bookings', 'delete bookings','edit bookings','view forms','create forms', 'delete forms','edit forms']);

        $manager = Role::findByName('Booking Manager');
        $manager->givePermissionTo(['view bookings', 'create bookings', 'delete bookings','edit bookings']);

        $customer = Role::findByName('Customer');
        $customer->givePermissionTo(['view bookings','view forms','view roles','view users']);
    }
}
