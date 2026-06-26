<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnitSale extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_id',
        'property_unit_id',
        'buyer_type',           // App\Models\Owner, App\Models\Tenant, App\Models\Client
        'buyer_id',
        'sale_price',
        'sale_date',
        'status',               // draft, completed, cancelled
        'notes',
    ];

    protected $casts = [
        'sale_date' => 'date',
        'sale_price' => 'decimal:2',
    ];

    /**
     * Get the property unit for this sale
     */
    public function propertyUnit()
    {
        return $this->belongsTo(PropertyUnit::class);
    }

    /**
     * Get the property through the unit
     */
    public function property()
    {
        return $this->hasOneThrough(
            Property::class,
            PropertyUnit::class,
            'id',           // Foreign key on property_units table
            'id',           // Foreign key on properties table
            'property_unit_id',  // Local key on unit_sales table
            'property_id'   // Local key on property_units table
        );
    }

    /**
     * Get the buyer (polymorphic) - Owner, Tenant, or Client
     */
    public function buyer()
    {
        return $this->morphTo(__FUNCTION__, 'buyer_type', 'buyer_id');
    }

    /**
     * Get the transaction(s) for this sale
     */
    public function transactions()
    {
        return $this->morphMany(PropertyTransaction::class, 'transactionable');
    }

    /**
     * Get the main transaction (usually just one for a sale)
     */
    public function transaction()
    {
        return $this->transactions()->first();
    }

    /**
     * Get the buyer's display name
     */
    public function getBuyerNameAttribute()
    {
        $buyer = $this->buyer;
        if ($buyer instanceof Owner) {
            return "{$buyer->first_name} {$buyer->last_name}";
        } elseif ($buyer instanceof Tenant) {
            return "{$buyer->first_name} {$buyer->last_name}";
        } elseif ($buyer instanceof Client) {
            return $buyer->name;
        }
        return 'Unknown Buyer';
    }

    /**
     * Get the buyer's email
     */
    public function getBuyerEmailAttribute()
    {
        $buyer = $this->buyer;
        return $buyer->email ?? 'N/A';
    }

    /**
     * Mark sale as completed (after payment)
     */
    public function markCompleted()
    {
        $this->update([
            'status' => 'completed',
            'sale_date' => now()->toDateString(),
        ]);

        // Update unit status to sold
        $this->propertyUnit->update(['status' => 'sold']);

        return $this;
    }

    /**
     * Cancel the sale
     */
    public function cancel()
    {
        $this->update(['status' => 'cancelled']);
        return $this;
    }
}
