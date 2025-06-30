<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PropertyTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'transactionable_type', 
        'transactionable_id', 
        'type', 
        'amount',
        'transaction_date', 
        'description', 
        'status', 
        'payment_method', 
        'reference_number',
    ];

    protected $casts = [
        'transaction_date' => 'date',
    ];

    public function transactionable()
    {
        return $this->morphTo();
    }

    public function documents()
    {
        return $this->morphMany(Document::class, 'documentable');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
    
}
