<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agent extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_id',
        'user_id',
        'first_name',
        'last_name',
        'email',
        'phone_number',
        'license_number',
        'commission_rate',
        'status',
    ];
    /**
     * Get the Agent's full name.
     *
     * @return string
     */
    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    } 

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function properties()
    {
        return $this->hasMany(Property::class);
    }

    public function leads()
    {
        return $this->hasMany(Lead::class);
    }

    public function viewings()
    {
        return $this->hasMany(Viewing::class);
    }
    
}
