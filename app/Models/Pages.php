<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pages extends Model
{
    use HasFactory;
    protected $table = 'pages';

    protected $fillable = [
        'title',
        'content',
        'slug',
        'status',
        'created_by',
        'feature_image',
        'meta_title',
        'meta_keywords',
        'meta_description'
    ];

    protected $hidden = [];
    protected $casts = [
        'status' => 'string',
    ];
}
