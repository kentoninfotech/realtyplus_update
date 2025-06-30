<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;
    

    protected $fillable = [
        'lease_id', 
        'transaction_id', 
        'payer_type', 
        'payer_id', 
        'amount',
        'payment_date', 
        'payment_method', 
        'status', 
        'notes',
    ];

    protected $casts = [
        'payment_date' => 'date',
    ];

    public function lease()
    {
        return $this->belongsTo(Lease::class);
    }

    public function transaction()
    {
        return $this->belongsTo(PropertyTransaction::class);
    }

    public function payer()
    {
        return $this->morphTo(); // Assuming you want to link directly to Tenant, Client, or Owner
    }

    
}
