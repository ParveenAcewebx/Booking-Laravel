<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UsersTable extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $users = [
            [
                'id' => 1,
                'name' => 'Admin User',
                'email' => 'admin@example.com',
                'password' => Hash::make('password'),
                'phone_code'=>'+91',
                'phone_number'=>'1234222567890',
                'avatar' => '',
                'status' => '1',
            ],
            [
                'id' => 2,
                'name' => 'Staff User',
                'email' => 'staff@example.com',
                'password' => Hash::make('password'),
                'phone_code'=>'+91',
                'phone_number'=>'85234567890',
                'avatar' => '',
                'status' => '1'
            ],
            [
                'id' => 3,
                'name' => 'Manager User',
                'email' => 'manager@example.com',
                'password' => Hash::make('password'),
                'phone_code'=>'+91',
                'phone_number'=>'11234567890',
                'avatar' => '',
                'status' => '1'
            ],
            [
                'id' => 4,
                'name' => 'Customer User',
                'email' => 'customer@example.com',
                'password' => Hash::make('password'),
                'phone_code'=>'+91',
                'phone_number'=>'12345567890',
                'avatar' => '',
                'status' => '1'
            ],
        ];

        foreach ($users as $user) {
            \App\Models\User::updateOrCreate(['email' => $user['email']], $user);
        }
    }
}