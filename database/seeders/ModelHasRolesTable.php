<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ModelHasRolesTable extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        User::find(1)?->assignRole('Administrator');
        User::find(2)?->assignRole('Staff');
        User::find(3)?->assignRole('Booking Manager');
        User::find(4)?->assignRole('Customer');
    }
}
