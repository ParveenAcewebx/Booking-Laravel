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
        'status'
    ];

    public function form()
    {
        return $this->belongsTo(\App\Models\BookingTemplate::class, 'booking_template_id');
    }
    
    public function staff()
    {
        return $this->belongsTo(User::class, 'selected_staff');
    }
}
