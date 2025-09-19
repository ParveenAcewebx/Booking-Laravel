<?php

namespace App\Import;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class UsersImport implements ToModel, WithHeadingRow
{
    protected $sendEmail;

    public function __construct($sendEmail = true)
    {
        $this->sendEmail = $sendEmail;
    }

    public function model(array $row)
    {
        $plainPassword = $row['password'] ?? (Str::random(4) . rand(0, 9) . Str::random(2) . '!@#$%^&*()_+'[rand(0, 11)] . Str::random(2));
        $user = User::create([
            'name'          => $row['name'],
            'email'         => $row['email'],
            'phone_number'  => $row['phone_number'],
            'password'      => Hash::make($plainPassword),
            'status'        => isset($row['status']) 
                                ? ($row['status'] == 'Active' 
                                    ? config('constants.status.active') 
                                    : config('constants.status.inactive')) 
                                : config('constants.status.active'),
        ]);

        if ($this->sendEmail) {
            $macros = [
                '{USER_NAME}'     => $user->name,
                '{USER_EMAIL}'    => $user->email,
                '{USER_PASSWORD}' => $plainPassword, 
                '{SITE_TITLE}'    => get_setting('site_title'),
            ];

            newcustomerregister('vendor_login_email_notification', $user->email, $macros);
            sendAdminTemplateEmail('admin_new_user_notification', get_setting('owner_email'), $macros);
        }
        return $user;
    }
}
