<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Vendor extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'email',
        'description',
        'thumbnail',
        'status',
        'stripe_mode',
        'stripe_test_site_key',
        'stripe_test_secret_key',
        'stripe_live_site_key',
        'stripe_live_secret_key'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}
