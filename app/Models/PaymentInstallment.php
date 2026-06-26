<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\BelongsToBusiness;
use Illuminate\Database\Eloquent\Model;

class PaymentInstallment extends Model
{
    use HasFactory, BelongsToBusiness;

    protected $fillable = [
        'business_id',
        'payment_plan_id',
        'installment_number',
        'amount_due',
        'amount_paid',
        'due_date',
        'paid_date',
        'days_overdue',
        'status',
        'payment_method',
        'reference_number',
        'notes',
    ];

    protected $casts = [
        'due_date' => 'date',
        'paid_date' => 'date',
    ];

    /**
     * Get the payment plan
     */
    public function paymentPlan()
    {
        return $this->belongsTo(PaymentPlan::class);
    }

    /**
     * Check if installment is overdue
     */
    public function isOverdue()
    {
        if ($this->status === 'paid') {
            return false;
        }
        return $this->due_date < now()->toDateString();
    }

    /**
     * Calculate days overdue
     */
    public function calculateDaysOverdue()
    {
        if ($this->status === 'paid' || !$this->isOverdue()) {
            return 0;
        }
        
        return now()->diffInDays($this->due_date);
    }

    /**
     * Get remaining balance for this installment
     */
    public function getRemainingBalance()
    {
        return $this->amount_due - $this->amount_paid;
    }

    /**
     * Get payment percentage
     */
    public function getPaymentPercentage()
    {
        if ($this->amount_due <= 0) {
            return 0;
        }
        return round(($this->amount_paid / $this->amount_due) * 100, 2);
    }

    /**
     * Get status badge color
     */
    public function getStatusBadgeColor()
    {
        switch ($this->status) {
            case 'paid':
                return 'success';
            case 'partial':
                return 'info';
            case 'overdue':
                return 'danger';
            case 'pending':
                if ($this->isOverdue()) {
                    return 'warning';
                }
                return 'secondary';
            case 'waived':
                return 'light';
            default:
                return 'secondary';
        }
    }

    /**
     * Mark as overdue
     */
    public function markAsOverdue()
    {
        if ($this->status !== 'paid') {
            $this->update([
                'status' => 'overdue',
                'days_overdue' => $this->calculateDaysOverdue(),
            ]);
        }
        return $this;
    }
}
