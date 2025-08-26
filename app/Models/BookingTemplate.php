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
        'status'
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
