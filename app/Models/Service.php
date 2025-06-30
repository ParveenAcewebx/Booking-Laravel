<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'description',
        'category',
        'duration',
        'thumbnail',
        'staff_member',
        'status',
        'price',
        'currency',
        'gallery',
        'appointment_status',
        'cancelling_unit',
        'cancelling_value',
        'redirect_url',
        'payment_mode',
        'payment__is_live',
        'stripe_test_secret_key',
        'stripe_test_site_key',
        'stripe_live_site_key',
        'stripe_live_secret_key',
        'payment_account'
    ];
}
