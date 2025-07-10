<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaffAssociation extends Model
{
    use HasFactory;
    protected $fillable = [
        'staff_member',
        'service_id',
    ];
    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id');
    }

    public function staff()
    {
        return $this->belongsTo(User::class, 'staff_member');
    }
}
