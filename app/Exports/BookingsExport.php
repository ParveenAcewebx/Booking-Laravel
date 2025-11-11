<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class BookingsExport implements FromArray, WithHeadings
{
    protected $bookings;

    // Constructor to accept the booking data
    public function __construct(array $bookings)
    {
        $this->bookings = $bookings;
    }

    // Return the array of booking data to be exported
    public function array(): array
    {
        return $this->bookings;
    }

    // Define the headings for the Excel file
    public function headings(): array
    {
        return [
            'Booking ID',
            'Booking Template Name',
            'Customer Name',
            'Booking DateTime',
            'Status',
            'First Name',
            'Last Name',
            'Phone Number',
            'Email',
            'Service Name',
            'Vendor Name',
            'Slot Detail',
        ];
    }
}
