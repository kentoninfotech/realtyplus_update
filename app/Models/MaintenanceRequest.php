<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaintenanceRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_id',
        'property_id', 
        'property_unit_id', 
        'reported_by_user_id', 
        'title',
        'description', 
        'priority', 
        'status', 
        'assigned_to_personnel_id',
        'reported_at', 
        'completed_at',
    ];
    
    protected $casts = [
        'reported_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    public function propertyUnit()
    {
        return $this->belongsTo(PropertyUnit::class);
    }

    public function reporter()
    {
        return $this->belongsTo(User::class, 'reported_by_user_id');
    }

    public function assignedPersonnel()
    {
        return $this->belongsTo(Personnel::class, 'assigned_to_personnel_id');
    }

    public function tasks()
    {
        return $this->morphMany(PropertyTask::class, 'taskable');
    }


}
