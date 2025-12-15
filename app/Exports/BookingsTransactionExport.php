<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class BookingsTransactionExport implements FromArray, WithHeadings
{
    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function array(): array
    {
        return $this->data;
    }

    public function headings(): array
    {
        return [
            'Transaction ID',
            'Booking ID',
            'Customer Name',
            'Vendor Name',
            'Payment ID',
            'Status',
            'Amount',
            'Currency',
            'Created At',
        ];
    }
}
