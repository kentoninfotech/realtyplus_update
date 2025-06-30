<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lease extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_id',
        'property_id',
        'property_unit_id',
        'tenant_id',
        'start_date',
        'end_date',
        'rent_amount',
        'deposit_amount',
        'payment_frequency', // e.g., 'monthly', 'quarterly', 'annually'
        'renewal_date', // Date when the lease can be renewed
        'status', // e.g., 'active', 'terminated', 'renewed'
        'terms', // JSON or text field for renewal terms
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'renewal_date' => 'date',
    ];

    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    public function propertyUnit()
    {
        return $this->belongsTo(PropertyUnit::class);
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function transactions()
    {
        return $this->morphMany(PropertyTransaction::class, 'transactionable');
    }

    public function documents()
    {
        return $this->morphMany(Document::class, 'documentable');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class); // 
    }

    public function tasks()
    {
        return $this->morphMany(PropertyTask::class, 'taskable');
    }

}
