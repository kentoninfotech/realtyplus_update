<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Owner extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_id',
        'user_id',
        'first_name',
        'last_name',
        'company_name',
        'email',
        'phone_number',
        'address',
        'bank_account_details', 
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function properties()
    {
        return $this->hasMany(Property::class);
    }

    public function documents()
    {
        return $this->morphMany(Document::class, 'documentable');
    }

    public function reportedMaintenanceRequests()
    {
        return $this->hasMany(MaintenanceRequest::class, 'reported_by_user_id'); // If reported by owner's user ID
    }


}
