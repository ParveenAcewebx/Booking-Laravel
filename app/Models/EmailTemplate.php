<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailTemplate extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'status',
        'subject',
        'email_content',
        'created_at',
    ];
    use HasFactory;
}
