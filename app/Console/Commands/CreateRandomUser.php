<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Str;

class CreateRandomUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:create-random-user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = 'User ' . Str::random(5);
        $email = 'user'. Str::random(5).'@example.com';
        $password = bcrypt('password123');

        $phoneCode = '+91'; 
        $phoneNumber = '9'.rand(100000000, 999999999);

        $avatarPath = 'avatars/default.png'; 

        $status = rand(0,1); 
        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'avatar' => $avatarPath,
            'phone_number' => $phoneNumber,
            'phone_code' => $phoneCode,
            'status' => $status,
        ]);

        $this->info('Random user created: '.$user->email);
    }
}
