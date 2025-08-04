<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    use HasFactory;

    protected $table = 'staffs';

    protected $fillable = [
        'user_id',
        'work_hours',
        'days_off',
        'vendor_id',
        'primary_staff'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function services()
    {
        return $this->hasMany(StaffServiceAssociation::class, 'service_id', 'id');
    }

    public function getServiceNamesAttribute()
    {
        return $this->services->pluck('name')->toArray();
    }
}
