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
        'macro',
        'subject',
        'email_content',
        'dummy_template',
        'created_at',
    ];
    use HasFactory;
}
