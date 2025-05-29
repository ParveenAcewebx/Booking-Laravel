<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionTable extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $permissions = ['view', 'edit', 'manage'];

        foreach ($permissions as $index => $permission) {
            Permission::create([
                'id' => $index + 1,
                'name' => $permission,
                'guard_name' => 'web',
            ]);
        }
    }
}
