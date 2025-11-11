<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $fillable = [
        'category_name',
        'slug',
        'created_at',
        'status',
        'thumbnail',
    ];
    public function services()
    {
        return $this->hasMany(Service::class, 'id');
    }
}
