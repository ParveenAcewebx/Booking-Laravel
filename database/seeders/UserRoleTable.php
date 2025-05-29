<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;


class UserRoleTable extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        Role::insert([
            ['id' => 1, 'name' => 'Administrator', 'guard_name' => 'web'],
            ['id' => 2, 'name' => 'Staff', 'guard_name' => 'web'],
            ['id' => 3, 'name' => 'Booking Manager', 'guard_name' => 'web'],
            ['id' => 4, 'name' => 'Customer', 'guard_name' => 'web'],
        ]);
    }
}
