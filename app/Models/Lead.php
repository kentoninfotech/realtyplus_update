<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_id',
        'agent_id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'preferred_contact_method',
        'source', // e.g., 'website', 'referral', 'social_media'
        'status', // e.g., 'new', 'contacted', 'interested', 'not_interested'
        'notes',
        'budget',
        'property_type_interest',
        'bedrooms_interest',
    ];

    public function agent()
    {
        return $this->belongsTo(Agent::class);
    }

    public function viewings()
    {
        return $this->hasMany(Viewing::class);
    }

    public function tasks()
    {
        return $this->morphMany(PropertyTask::class, 'taskable');
    }

}
