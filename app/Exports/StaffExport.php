<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class StaffExport implements FromArray, WithHeadings
{
    protected $staffData;

    // Constructor to accept staff data
    public function __construct(array $staffData)
    {
        $this->staffData = $staffData;
    }

    // Return the data to be exported
    public function array(): array
    {
        return $this->staffData;
    }

    // Define the headings for the Excel file
    public function headings(): array
    {
        return [
            'Name',
            'Email',
            'Phone Number',
            'Service Name',
            'Vendor Name',
            'Work Hours',
            'Day Off',
        ];
    }
}
