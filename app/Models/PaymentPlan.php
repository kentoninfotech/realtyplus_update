<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\BelongsToBusiness;
use Illuminate\Database\Eloquent\Model;

class PaymentPlan extends Model
{
    use HasFactory, BelongsToBusiness;

    protected $fillable = [
        'business_id',
        'payable_type',
        'payable_id',
        'payment_type',
        'total_amount',
        'amount_paid',
        'balance',
        'status',
        'total_installments',
        'installments_paid',
        'start_date',
        'end_date',
        'last_payment_date',
        'notes',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'last_payment_date' => 'date',
    ];

    /**
     * Get the payable model (UnitSale or Lease)
     */
    public function payable()
    {
        return $this->morphTo();
    }

    /**
     * Get all installments for this payment plan
     */
    public function installments()
    {
        return $this->hasMany(PaymentInstallment::class, 'payment_plan_id');
    }

    /**
     * Get paid installments
     */
    public function paidInstallments()
    {
        return $this->installments()->where('status', 'paid');
    }

    /**
     * Get pending installments
     */
    public function pendingInstallments()
    {
        return $this->installments()->where('status', 'pending');
    }

    /**
     * Get overdue installments
     */
    public function overdueInstallments()
    {
        return $this->installments()->where('status', 'overdue');
    }

    /**
     * Get partial installments
     */
    public function partialInstallments()
    {
        return $this->installments()->where('status', 'partial');
    }

    /**
     * Check if payment plan is complete
     */
    public function isComplete()
    {
        return $this->status === 'completed' || $this->balance <= 0;
    }

    /**
     * Check if payment plan is overdue
     */
    public function isOverdue()
    {
        if ($this->status === 'completed') {
            return false;
        }
        
        // Check if any installment is overdue
        $overdueCount = $this->installments()
            ->where('status', 'overdue')
            ->orWhere(function($query) {
                $query->where('status', 'pending')
                    ->where('due_date', '<', now()->toDateString());
            })->count();
            
        return $overdueCount > 0;
    }

    /**
     * Get next due installment
     */
    public function getNextDueInstallment()
    {
        return $this->installments()
            ->where('status', '!=', 'paid')
            ->orderBy('due_date')
            ->first();
    }

    /**
     * Record a payment for an installment
     */
    public function recordPayment($installmentId, $amountPaid, $paymentMethod, $referenceNumber = null)
    {
        $installment = $this->installments()->findOrFail($installmentId);
        
        // Update installment
        $newPaid = $installment->amount_paid + $amountPaid;
        $installment->update([
            'amount_paid' => $newPaid,
            'status' => $newPaid >= $installment->amount_due ? 'paid' : 'partial',
            'paid_date' => $newPaid >= $installment->amount_due ? now()->toDateString() : $installment->paid_date,
            'payment_method' => $paymentMethod,
            'reference_number' => $referenceNumber,
        ]);

        // Update payment plan totals
        $totalPaid = $this->installments()->sum('amount_paid');
        $this->update([
            'amount_paid' => $totalPaid,
            'balance' => $this->total_amount - $totalPaid,
            'installments_paid' => $this->paidInstallments()->count(),
            'last_payment_date' => now()->toDateString(),
            'status' => $totalPaid >= $this->total_amount ? 'completed' : ($totalPaid > 0 ? 'partial' : 'pending'),
        ]);

        return $installment;
    }

    /**
     * Get status badge color
     */
    public function getStatusBadgeColor()
    {
        switch ($this->status) {
            case 'completed':
                return 'success';
            case 'partial':
                return 'info';
            case 'overdue':
                return 'danger';
            case 'pending':
                return 'warning';
            case 'defaulted':
                return 'dark';
            default:
                return 'secondary';
        }
    }

    /**
     * Calculate payment progress percentage
     */
    public function getProgressPercentage()
    {
        if ($this->total_amount <= 0) {
            return 0;
        }
        return round(($this->amount_paid / $this->total_amount) * 100, 2);
    }
}
