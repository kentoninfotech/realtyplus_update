<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    use HasFactory;

    protected $fillable = [
        'property_type_id',
        'agent_id',
        'owner_id',
        'name',
        'address',
        'state',
        'country',
        'description',
        'status',
        'has_units',
        'total_units',
        'latitude',
        'longitude',
        'area_sqft',
        'lot_size_sqft',
        'year_built',
        'purchase_price',
        'sale_price',
        'rent_price',
        'date_acquired',
        'listing_type',
        'listed_at',
    ];


    protected $casts = [
        'has_units' => 'boolean',
        'date_acquired' => 'date',
        'listed_at'   => 'datetime'
    ];



    public function propertyType()
    {
        return $this->belongsTo(PropertyType::class);
    }

    public function owner()
    {
        return $this->belongsTo(Owner::class);
    }

    public function agent()
    {
        return $this->belongsTo(Agent::class); //Define model later
    }

    public function units()
    {
        return $this->hasMany(PropertyUnit::class);
    }

    public function images()
    {
        return $this->hasMany(PropertyImage::class);
    }

    public function amenities()
    {
        return $this->belongsToMany(Amenity::class, 'property_amenity');
    }

    public function leases()
    {
        return $this->hasMany(Lease::class);
    }

    public function viewings()
    {
        return $this->hasMany(Viewing::class);
    }

    public function documents()
    {
        return $this->morphMany(Document::class, 'documentable');
    }

    public function maintenanceRequests()
    {
        return $this->hasMany(MaintenanceRequest::class);
    }

    public function tasks()
    {
        return $this->morphMany(PropertyTask::class, 'taskable');
    }


}
