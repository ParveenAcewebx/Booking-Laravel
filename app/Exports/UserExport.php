<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class UserExport implements FromCollection, WithHeadings
{
    /**
     * Return the collection of data to be exported.
     *
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        // Return specific columns or the full user data
        return User::select('id', 'name', 'email', 'phone_code', 'phone_number', 'email_verified_at', 'status', 'created_at', 'updated_at', 'avatar')->get();
    }

    /**
     * Set the headings for the Excel file.
     *
     * @return array
     */
    public function headings(): array
    {
        return [
            'ID',
            'Name',
            'Email',
            'Phone Code',
            'Phone Number',
            'Email Verified At',
            'Status',
            'Created At',
            'Updated At',
            'Avatar',
        ];
    }
}
