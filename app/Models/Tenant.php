<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_id',
        'user_id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'address',
        'emergency_contact_name',
        'emergency_contact_phone',
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function leases()
    {
        return $this->hasMany(Lease::class);
    }

    public function documents()
    {
        return $this->morphMany(Document::class, 'documentable');
    }

    public function reportedMaintenanceRequests()
    {
        return $this->hasMany(MaintenanceRequest::class, 'reported_by_user_id'); // If reported by tenant's user ID
    }

    
}
