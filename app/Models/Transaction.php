<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'booking_id',
        'customer_id',
        'template_id',
        'vendor_id',
        'payment_id',
        'status',
        'amount',
        'currency',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }
    public function bookingTemplate()
    {
        return $this->belongsTo(BookingTemplate::class, 'template_id');
    }
}
