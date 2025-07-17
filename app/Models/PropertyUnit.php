<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PropertyUnit extends Model
{
    use HasFactory;

    protected $fillable = [
        'property_id',
        'unit_number',
        'unit_type',
        'description',
        'square_footage',
        'area_sqm',
        'zoning_type',
        'status',
        'floor_number', // nullable
        'bedrooms',
        'bathrooms',
        'sale_price',
        'rent_price',
        'deposit_amount',
        'available_from',
    ];

    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    public function images()
    {
        return $this->hasMany(PropertyImage::class);
    }

    public function leases()
    {
        return $this->hasMany(Lease::class);
    }

    public function viewings()
    {
        return $this->hasMany(Viewing::class);
    }

    public function maintenanceRequests()
    {
        return $this->hasMany(MaintenanceRequest::class);
    }
    

}
