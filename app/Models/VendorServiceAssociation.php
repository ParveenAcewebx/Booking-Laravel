<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Vendor;

class VendorServiceAssociation extends Model
{
    use HasFactory;
    protected $fillable = ['vendor_id', 'service_id'];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }
    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id');
    }
}
