<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorStaffAssociation extends Model
{
    use HasFactory;

    protected $fillable = ['vendor_id', 'user_id'];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function staff()
    {
        // Link staff table with user_id
        return $this->hasOne(Staff::class, 'user_id', 'user_id');
    }
}
