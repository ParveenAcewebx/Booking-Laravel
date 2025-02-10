<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bookingform extends Model
{
    use HasFactory;

    protected $fillable = [
        'data',
        'form_name',
       
    ];
}
