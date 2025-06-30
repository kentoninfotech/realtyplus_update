<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Viewing extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_id',
        'property_id',
        'property_unit_id',
        'lead_id',
        'agent_id',
        'client_name',
        'client_email',
        'client_phone',
        'scheduled_at',
        'status', // e.g., 'scheduled', 'completed', 'cancelled'
        'notes',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
    ];

    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }

    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    public function propertyUnit()
    {
        return $this->belongsTo(PropertyUnit::class);
    }

    public function agent()
    {
        return $this->belongsTo(Agent::class);
    }
    
}
