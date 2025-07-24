<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    use HasRoles;
    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
        'status',
        'phone_number',
        'phone_code',
    ];
    protected $hidden = [
        'password',
        'remember_token',
    ];
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function services()
    {
        return $this->belongsToMany(Service::class, 'staff_service_associations', 'staff_member', 'service_id')
            ->withTimestamps();
    }

    public function vendorAssociations()
    {
        return $this->hasMany(VendorStaffAssociation::class);
    }

    public function staff()
    {
        return $this->hasOne(Staff::class, 'user_id', 'id');
    }
}
