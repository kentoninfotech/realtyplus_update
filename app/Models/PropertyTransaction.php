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
        'payer_type',
        'payer_id',
        'type', // credit or debit
        'purpose', // full_payment, partial_payment, deposit, maintenance_expense, refund
        'amount',
        'transaction_date',
        'payment_method',
        'reference_number',
        'status',
        'description',
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

    public function payer()
    {
        return $this->morphTo();
    }

    // public function payments()
    // {
    //     return $this->hasMany(Payment::class);
    // }
    
}
