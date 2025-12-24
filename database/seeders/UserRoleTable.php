<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class UserRoleTable extends Seeder
{
    public function run()
    {
        $roles = [
            'Administrator',
            'Staff',
            'Booking Manager',
            'Customer',
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate(
                [
                    'name' => $role,
                    'guard_name' => 'web',
                ],
                [
                    'status' => 1,
                ]
            );
        }
    }
}
