<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingTemplate extends Model
{
    use HasFactory;
    protected $fillable = [
        'data',
        'template_name',
        'created_by',
        'slug',
        'status',
        'vendor_id'
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function bookings()
    {
        return $this->hasMany(Booking::class, 'booking_template_id');
    }
}

