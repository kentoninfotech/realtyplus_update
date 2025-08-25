<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_id',
        'property_id',
        'property_unit_id',
        'buyer_id', 
        'sale_date', 
        'purchase_price', 
        'status', // e.g. 'pending', 'closed', 'cancelled'
        'sale_terms', // JSON or text field for specific terms and conditions
    ];

    protected $casts = [
        'sale_date' => 'date',
    ];

    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    public function propertyUnit()
    {
        return $this->belongsTo(PropertyUnit::class);
    }

    public function buyer() 
    {
        return $this->belongsTo(Client::class); 
    }

    public function transactions()
    {
        return $this->morphMany(PropertyTransaction::class, 'transactionable');
    }

    public function documents()
    {
        return $this->morphMany(Document::class, 'documentable');
    }
    
}
