<?php

namespace App\Http\Controllers;

use App\Models\PaymentPlan;
use App\Models\PaymentInstallment;
use App\Models\UnitSale;
use App\Models\Lease;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PaymentTrackingController extends Controller
{
    /**
     * Show payment tracking dashboard
     */
    public function dashboard()
    {
        $businessId = auth()->user()->business_id;

        // Get all payment plans
        $paymentPlans = PaymentPlan::with(['payable', 'installments'])
            ->where('business_id', $businessId)
            ->latest()
            ->get();

        // Calculate statistics
        $stats = [
            'total_sales' => UnitSale::where('business_id', $businessId)
                ->where('status', 'completed')
                ->count(),
            
            'completed_payments' => PaymentPlan::where('business_id', $businessId)
                ->where('status', 'completed')
                ->count(),
            
            'partial_payments' => PaymentPlan::where('business_id', $businessId)
                ->where('status', 'partial')
                ->count(),
            
            'debtors' => PaymentPlan::where('business_id', $businessId)
                ->where('status', 'pending')
                ->count(),
            
            'overdue_payments' => PaymentInstallment::where('business_id', $businessId)
                ->where('status', 'overdue')
                ->count(),
            
            'total_amount_due' => PaymentPlan::where('business_id', $businessId)
                ->where('status', '!=', 'completed')
                ->sum('balance'),
        ];

        // Get debtors (those who haven't made any payment)
        $debtors = PaymentPlan::with(['payable', 'installments'])
            ->where('business_id', $businessId)
            ->where('status', 'pending')
            ->where('amount_paid', 0)
            ->get();

        // Get installment payers (partial payments)
        $installmentPayers = PaymentPlan::with(['payable', 'installments'])
            ->where('business_id', $businessId)
            ->where('status', 'partial')
            ->latest()
            ->get();

        // Get completed payments
        $completedPayments = PaymentPlan::with(['payable', 'installments'])
            ->where('business_id', $businessId)
            ->where('status', 'completed')
            ->latest()
            ->get();

        // Get overdue payments
        $overduePayments = PaymentInstallment::with(['paymentPlan'])
            ->where('business_id', $businessId)
            ->where('status', 'overdue')
            ->latest()
            ->get();

        return view('payments.tracking-dashboard', compact(
            'stats',
            'paymentPlans',
            'debtors',
            'installmentPayers',
            'completedPayments',
            'overduePayments'
        ));
    }

    /**
     * Show payment plan details
     */
    public function showPaymentPlan($paymentPlanId)
    {
        $paymentPlan = PaymentPlan::with(['payable', 'installments'])
            ->findOrFail($paymentPlanId);

        // Check authorization
        if ($paymentPlan->business_id !== auth()->user()->business_id) {
            abort(403, 'Unauthorized');
        }

        // Get payable details (UnitSale or Lease)
        $payable = $paymentPlan->payable;
        $payableDetails = $this->getPayableDetails($payable);

        return view('payments.payment-plan-detail', compact(
            'paymentPlan',
            'payable',
            'payableDetails'
        ));
    }

    /**
     * Record an installment payment
     */
    public function recordInstallmentPayment(Request $request, $installmentId)
    {
        $request->validate([
            'amount_paid' => ['required', 'numeric', 'min:0.01'],
            'payment_method' => ['required', 'string'],
            'payment_date' => ['required', 'date'],
            'reference_number' => ['nullable', 'string'],
        ]);

        $installment = PaymentInstallment::findOrFail($installmentId);

        // Check authorization
        if ($installment->business_id !== auth()->user()->business_id) {
            abort(403, 'Unauthorized');
        }

        // Get the payment plan
        $paymentPlan = $installment->paymentPlan;

        // Record the payment
        $amountPaid = floatval($request->amount_paid);
        $paymentPlan->recordPayment(
            $installmentId,
            $amountPaid,
            $request->payment_method,
            $request->reference_number
        );

        return redirect()->route('payment.plan.detail', $paymentPlan->id)
            ->with('success', "Payment of ₦" . number_format($amountPaid, 2) . " recorded successfully!");
    }

    /**
     * Get details about the payable (UnitSale or Lease)
     */
    private function getPayableDetails($payable)
    {
        if ($payable instanceof UnitSale) {
            $buyer = $payable->buyer;
            $buyerName = $payable->buyer_name;
            $buyerEmail = $payable->buyer_email;
            
            return [
                'type' => 'Unit Sale',
                'description' => "Unit {$payable->propertyUnit->unit_number} - {$payable->property->name}",
                'property_name' => $payable->property->name,
                'unit_number' => $payable->propertyUnit->unit_number,
                'buyer_name' => $buyerName,
                'buyer_email' => $buyerEmail,
                'buyer_type' => class_basename($payable->buyer_type),
            ];
        } elseif ($payable instanceof Lease) {
            $tenant = $payable->tenant;
            
            return [
                'type' => 'Lease/Rental',
                'description' => "Unit {$payable->propertyUnit->unit_number} - {$payable->property->name}",
                'property_name' => $payable->property->name,
                'unit_number' => $payable->propertyUnit->unit_number,
                'tenant_name' => "{$tenant->first_name} {$tenant->last_name}",
                'tenant_email' => $tenant->email,
                'tenant_phone' => $tenant->phone_number,
            ];
        }

        return [];
    }

    /**
     * Export debtors report
     */
    public function exportDebtorsReport()
    {
        $businessId = auth()->user()->business_id;

        $debtors = PaymentPlan::with(['payable', 'payable.buyer'])
            ->where('business_id', $businessId)
            ->where('status', 'pending')
            ->where('amount_paid', 0)
            ->get();

        // Generate CSV
        $filename = "debtors_report_" . date('Y-m-d_H-i-s') . ".csv";
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function() use ($debtors) {
            $file = fopen('php://output', 'w');

            // Header row
            fputcsv($file, [
                'Property',
                'Unit',
                'Buyer/Tenant Name',
                'Contact',
                'Amount Due',
                'Sale/Lease Type',
                'Due Date',
                'Days Overdue',
            ]);

            // Data rows
            foreach ($debtors as $plan) {
                $payable = $plan->payable;
                $details = $this->getPayableDetails($payable);
                $nextDue = $plan->getNextDueInstallment();

                fputcsv($file, [
                    $details['property_name'] ?? 'N/A',
                    $details['unit_number'] ?? 'N/A',
                    $details['buyer_name'] ?? $details['tenant_name'] ?? 'N/A',
                    $details['buyer_email'] ?? $details['tenant_email'] ?? 'N/A',
                    '₦' . number_format($plan->balance, 2),
                    $details['type'],
                    $nextDue ? $nextDue->due_date->format('Y-m-d') : 'N/A',
                    $nextDue && $nextDue->due_date->isPast() ? now()->diffInDays($nextDue->due_date) : 0,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Get overdue details for a specific installment
     */
    public function showOverdueInstallment($installmentId)
    {
        $installment = PaymentInstallment::with(['paymentPlan', 'paymentPlan.payable'])
            ->findOrFail($installmentId);

        // Check authorization
        if ($installment->business_id !== auth()->user()->business_id) {
            abort(403, 'Unauthorized');
        }

        $paymentPlan = $installment->paymentPlan;
        $payable = $paymentPlan->payable;
        $payableDetails = $this->getPayableDetails($payable);

        return view('payments.overdue-installment', compact(
            'installment',
            'paymentPlan',
            'payable',
            'payableDetails'
        ));
    }
}
