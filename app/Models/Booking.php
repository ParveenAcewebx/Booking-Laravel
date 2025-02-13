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
}
