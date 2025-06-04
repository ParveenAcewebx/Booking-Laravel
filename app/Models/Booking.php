<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_form_id',
        'customer_id',
        'booking_datetime',       
        'booking_data',
        'service',
        'selected_staff',
        'status'
    ];

    public function form()
    {
        return $this->belongsTo(\App\Models\Bookingform::class, 'booking_form_id');
    }
    
    public function staff()
    {
        return $this->belongsTo(User::class, 'selected_staff');
    }
}
