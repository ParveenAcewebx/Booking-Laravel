<?php

namespace App\Import;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class UsersImport implements ToModel, WithHeadingRow, WithValidation, WithChunkReading
{
    protected $sendEmail;

    public function __construct($sendEmail = true)
    {
        $this->sendEmail = $sendEmail;
    }

    public function model(array $row)
    {
        // Generate random password if not provided
        $plainPassword = $row['password'] ?? (
            Str::random(4) . rand(0, 9) . Str::random(2) .
            '!@#$%^&*()_+'[rand(0, 11)] . Str::random(2)
        );

        // Create user
        $user = User::create([
            'name'          => $row['name'],
            'email'         => $row['email'],
            'phone_number'  => $row['phone_number'],
            'password'      => Hash::make($plainPassword),
            'status'        => $row['status'] === 'Active'
                                ? config('constants.status.active')
                                : config('constants.status.inactive'),
        ]);

        // Assign role
        $user->assignRole('customer');

        // Send email notifications if enabled
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

    // Process 1000 rows per chunk to save memory
    public function chunkSize(): int
    {
        return 1000;
    }

    // Validation rules
    public function rules(): array
    {
        return [
            '*.name'         => 'required|string|max:255',
            '*.email'        => 'required|email|unique:users,email',
            '*.phone_number' => 'required|string|max:20',
            '*.status'       => 'required|in:Active,Inactive',
        ];
    }

    // Custom error messages
    public function customValidationMessages()
    {
        return [
            '*.name.required'         => 'Name is required.',
            '*.email.required'        => 'Email is required.',
            '*.email.email'           => 'Email must be valid.',
            '*.email.unique'          => 'This email already exists.',
            '*.phone_number.required' => 'Phone number is required.',
            '*.status.in'             => 'Status must be Active or Inactive.',
        ];
    }
}
