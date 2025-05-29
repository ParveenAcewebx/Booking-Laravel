<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run()
    {
        $this->call([
            UserRoleTable::class,
            PermissionTable::class,
            RoleHasPermissionsTable::class,
            UsersTable::class,
            ModelHasRolesTable::class,
        ]);
    }

}
