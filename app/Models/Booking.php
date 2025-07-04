<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_template_id',
        'customer_id',
        'booking_datetime',
        'booking_data',
        'service',
        'selected_staff',
        'status',
        'first_name',
        'last_name',
        'phone_number',
        'email',
        'service_id',
    ];

    public function template()
    {
        return $this->belongsTo(BookingTemplate::class, 'booking_template_id');
    }

    public function staff()
    {
        return $this->belongsTo(User::class, 'selected_staff');
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }
      
}
