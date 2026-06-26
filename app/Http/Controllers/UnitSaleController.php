<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUnitSaleRequest;
use App\Models\PropertyUnit;
use App\Models\UnitSale;
use App\Models\Owner;
use App\Models\Tenant;
use App\Models\Client;
use App\Models\PropertyTransaction;
use App\Models\PaymentPlan;
use App\Models\PaymentInstallment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use Carbon\Carbon;

class UnitSaleController extends Controller
{
    /**
     * Show the form to initiate a unit sale
     */
    public function showSaleForm($unitId)
    {
        $unit = PropertyUnit::with(['property', 'property.owner'])->findOrFail($unitId);

        // Check if unit is available for sale
        if ($unit->status !== 'available') {
            return redirect()->back()->with('error', 'This unit is not available for sale.');
        }

        // Get list of potential buyers
        $owners = Owner::select('id', 'first_name', 'last_name', 'company_name', 'email', 'phone_number')
            ->where('business_id', auth()->user()->business_id)
            ->orderBy('first_name')
            ->get();

        $tenants = Tenant::select('id', 'first_name', 'last_name', 'email', 'phone_number')
            ->where('business_id', auth()->user()->business_id)
            ->orderBy('first_name')
            ->get();

        $clients = Client::select('id', 'name', 'email', 'phone_number')
            ->where('business_id', auth()->user()->business_id)
            ->orderBy('name')
            ->get();

        return view('units.sell-unit', compact('unit', 'owners', 'tenants', 'clients'));
    }

    /**
     * Store the unit sale and show payment page
     */
    public function processSale(CreateUnitSaleRequest $request)
    {
        $validated = $request->validated();
        $unitId = $validated['property_unit_id'];
        $unit = PropertyUnit::findOrFail($unitId);

        return DB::transaction(function () use ($validated, $unit) {
            // Determine buyer details
            $buyerType = $validated['buyer_type'];
            $buyerId = $validated['buyer_id'] ?? null;

            // If "create new buyer", create the buyer first
            if (!$buyerId) {
                $buyerId = $this->createNewBuyer($buyerType, $validated);
            }

            // Map buyer type to model class
            $buyerClass = $this->getBuyerClass($buyerType);

            // Create unit sale record
            $sale = UnitSale::create([
                'business_id' => auth()->user()->business_id,
                'property_unit_id' => $unit->id,
                'buyer_type' => $buyerClass,
                'buyer_id' => $buyerId,
                'sale_price' => $validated['sale_price'],
                'status' => 'draft',
                'notes' => $validated['notes'] ?? null,
            ]);

            // Redirect to payment page
            return redirect()->route('unit.sale.payment', $sale->id)
                ->with('success', 'Sale initiated. Please proceed to payment.');
        });
    }

    /**
     * Show the payment page where user finalizes and generates receipt
     */
    public function showPaymentPage($saleId)
    {
        $sale = UnitSale::with(['propertyUnit.property', 'buyer'])->findOrFail($saleId);

        // Check ownership
        if ($sale->business_id !== auth()->user()->business_id) {
            return redirect()->back()->with('error', 'Unauthorized access.');
        }

        // Payment methods
        $paymentMethods = ['Bank Transfer', 'Cash', 'Credit Card', 'Cheque', 'Mobile Money'];

        // Get buyer info for display
        $buyer = $sale->buyer;
        $buyerName = $sale->buyer_name;
        $buyerEmail = $sale->buyer_email;

        return view('units.sale-payment', compact(
            'sale',
            'buyer',
            'buyerName',
            'buyerEmail',
            'paymentMethods'
        ));
    }

    /**
     * Complete the sale and create transaction/receipt
     */
    public function completeSale(Request $request, $saleId)
    {
        $request->validate([
            'payment_type' => ['required', 'in:full,installment'],
            'payment_method' => ['required', 'in:Bank Transfer,Cash,Credit Card,Cheque,Mobile Money'],
            'reference_number' => ['nullable', 'string', 'max:255'],
            'transaction_date' => ['required', 'date'],
            'payment_advice' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'], // 5MB max
            // Installment-specific validations
            'total_installments' => ['nullable', 'required_if:payment_type,installment', 'integer', 'min:2', 'max:12'],
            'first_payment_amount' => ['nullable', 'required_if:payment_type,installment', 'numeric', 'min:0'],
            'payment_start_date' => ['nullable', 'required_if:payment_type,installment', 'date'],
            'payment_frequency' => ['nullable', 'required_if:payment_type,installment', 'in:monthly,bi-weekly,quarterly,bi-annual,annual'],
        ]);

        $sale = UnitSale::findOrFail($saleId);

        // Check ownership
        if ($sale->business_id !== auth()->user()->business_id) {
            return redirect()->back()->with('error', 'Unauthorized access.');
        }

        return DB::transaction(function () use ($request, $sale) {
            // Mark sale as completed
            $sale->markCompleted();

            $paymentType = $request->input('payment_type', 'full');
            
            if ($paymentType === 'installment') {
                // Handle installment payment
                return $this->processInstallmentPayment($request, $sale);
            } else {
                // Handle full payment
                return $this->processFullPayment($request, $sale);
            }
        });
    }

    /**
     * Process full payment
     */
    private function processFullPayment($request, $sale)
    {
        // Create PaymentPlan for full payment
        $paymentPlan = PaymentPlan::create([
            'business_id' => auth()->user()->business_id,
            'payable_type' => UnitSale::class,
            'payable_id' => $sale->id,
            'payment_type' => 'full',
            'total_amount' => $sale->sale_price,
            'amount_paid' => $sale->sale_price,
            'balance' => 0,
            'status' => 'completed',
            'total_installments' => 1,
            'installments_paid' => 1,
            'start_date' => $request->transaction_date,
            'end_date' => $request->transaction_date,
            'last_payment_date' => $request->transaction_date,
            'notes' => 'Full payment at purchase',
        ]);

        // Create single installment record
        PaymentInstallment::create([
            'business_id' => auth()->user()->business_id,
            'payment_plan_id' => $paymentPlan->id,
            'installment_number' => 1,
            'amount_due' => $sale->sale_price,
            'amount_paid' => $sale->sale_price,
            'due_date' => $request->transaction_date,
            'paid_date' => $request->transaction_date,
            'status' => 'paid',
            'payment_method' => $request->payment_method,
            'reference_number' => $request->reference_number,
        ]);

        // Create PropertyTransaction
        $transaction = PropertyTransaction::create([
            'business_id' => auth()->user()->business_id,
            'transactionable_type' => UnitSale::class,
            'transactionable_id' => $sale->id,
            'payer_type' => $sale->buyer_type,
            'payer_id' => $sale->buyer_id,
            'type' => 'credit',
            'purpose' => 'unit_sale_full_payment',
            'amount' => $sale->sale_price,
            'transaction_date' => $request->transaction_date,
            'payment_method' => $request->payment_method,
            'reference_number' => $request->reference_number ?? 'SALE-' . $sale->id . '-' . now()->timestamp,
            'status' => 'completed',
            'description' => "Unit Sale (Full Payment) - {$sale->propertyUnit->unit_number} - {$sale->property->name}",
        ]);

        // Upload payment advice if provided
        $this->uploadPaymentAdvice($request, $transaction);

        return redirect()->route('show.transaction', $transaction->id)
            ->with('success', 'Unit sale completed successfully with full payment! Receipt generated.');
    }

    /**
     * Process installment payment
     */
    private function processInstallmentPayment($request, $sale)
    {
        $totalAmount = $sale->sale_price;
        $firstPaymentAmount = floatval($request->first_payment_amount);
        $totalInstallments = intval($request->total_installments);
        $paymentFrequency = $request->payment_frequency;
        $startDate = Carbon::parse($request->payment_start_date);

        // Calculate remaining balance
        $remainingBalance = $totalAmount - $firstPaymentAmount;
        $installmentAmount = round($remainingBalance / ($totalInstallments - 1), 2);

        // Create PaymentPlan
        $paymentPlan = PaymentPlan::create([
            'business_id' => auth()->user()->business_id,
            'payable_type' => UnitSale::class,
            'payable_id' => $sale->id,
            'payment_type' => 'installment',
            'total_amount' => $totalAmount,
            'amount_paid' => $firstPaymentAmount,
            'balance' => $remainingBalance,
            'status' => $firstPaymentAmount > 0 ? 'partial' : 'pending',
            'total_installments' => $totalInstallments,
            'installments_paid' => $firstPaymentAmount > 0 ? 1 : 0,
            'start_date' => Carbon::now()->toDateString(),
            'end_date' => $this->calculateEndDate($startDate, $totalInstallments, $paymentFrequency),
            'last_payment_date' => $firstPaymentAmount > 0 ? Carbon::now()->toDateString() : null,
            'notes' => "Installment plan: {$totalInstallments} payments, {$paymentFrequency}",
        ]);

        // Create first installment (immediate payment)
        if ($firstPaymentAmount > 0) {
            PaymentInstallment::create([
                'business_id' => auth()->user()->business_id,
                'payment_plan_id' => $paymentPlan->id,
                'installment_number' => 1,
                'amount_due' => $firstPaymentAmount,
                'amount_paid' => $firstPaymentAmount,
                'due_date' => Carbon::now()->toDateString(),
                'paid_date' => Carbon::now()->toDateString(),
                'status' => 'paid',
                'payment_method' => $request->payment_method,
                'reference_number' => $request->reference_number,
                'notes' => 'Initial payment',
            ]);
        }

        // Create remaining installments
        $currentDate = $startDate;
        for ($i = 2; $i <= $totalInstallments; $i++) {
            $currentDate = $this->getNextDueDate($currentDate, $paymentFrequency);
            
            // For last installment, ensure it covers any rounding differences
            $amountDue = ($i === $totalInstallments) 
                ? ($remainingBalance - ($installmentAmount * ($totalInstallments - 2)))
                : $installmentAmount;

            PaymentInstallment::create([
                'business_id' => auth()->user()->business_id,
                'payment_plan_id' => $paymentPlan->id,
                'installment_number' => $i,
                'amount_due' => $amountDue,
                'amount_paid' => 0,
                'due_date' => $currentDate->toDateString(),
                'paid_date' => null,
                'status' => $currentDate->isPast() ? 'overdue' : 'pending',
                'days_overdue' => $currentDate->isPast() ? now()->diffInDays($currentDate) : 0,
                'notes' => "Installment {$i} of {$totalInstallments}",
            ]);
        }

        // Create initial PropertyTransaction for first payment
        $transaction = PropertyTransaction::create([
            'business_id' => auth()->user()->business_id,
            'transactionable_type' => UnitSale::class,
            'transactionable_id' => $sale->id,
            'payer_type' => $sale->buyer_type,
            'payer_id' => $sale->buyer_id,
            'type' => 'credit',
            'purpose' => 'unit_sale_installment_payment',
            'amount' => $firstPaymentAmount > 0 ? $firstPaymentAmount : $totalAmount,
            'transaction_date' => $request->transaction_date,
            'payment_method' => $request->payment_method,
            'reference_number' => $request->reference_number ?? 'SALE-INST-' . $sale->id . '-' . now()->timestamp,
            'status' => 'completed',
            'description' => "Unit Sale (Installment Plan - {$totalInstallments} payments) - {$sale->propertyUnit->unit_number} - {$sale->property->name}",
        ]);

        // Upload payment advice if provided
        $this->uploadPaymentAdvice($request, $transaction);

        return redirect()->route('show.transaction', $transaction->id)
            ->with('success', 'Installment payment plan created successfully! Payment schedule has been set up.');
    }

    /**
     * Calculate the next due date based on frequency
     */
    private function getNextDueDate($currentDate, $frequency)
    {
        switch ($frequency) {
            case 'bi-weekly':
                return $currentDate->addWeeks(2);
            case 'monthly':
                return $currentDate->addMonth();
            case 'quarterly':
                return $currentDate->addMonths(3);
            case 'bi-annual':
                return $currentDate->addMonths(6);
            case 'annual':
                return $currentDate->addYear();
            default:
                return $currentDate->addMonth();
        }
    }

    /**
     * Calculate payment plan end date
     */
    private function calculateEndDate($startDate, $totalInstallments, $frequency)
    {
        $endDate = $startDate->copy();
        for ($i = 1; $i < $totalInstallments; $i++) {
            $endDate = $this->getNextDueDate($endDate, $frequency);
        }
        return $endDate;
    }

    /**
     * Upload payment advice document
     */
    private function uploadPaymentAdvice($request, $transaction)
    {
        if ($request->hasFile('payment_advice')) {
            $file = $request->file('payment_advice');
            $uploadDir = public_path('documents/transactions');
            
            // Ensure directory exists
            if (!File::isDirectory($uploadDir)) {
                File::makeDirectory($uploadDir, 0755, true);
            }
            
            $fileName = 'payment_advice_' . $transaction->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $filePath = 'documents/transactions/' . $fileName;
            
            // Store file in public storage
            $file->move($uploadDir, $fileName);
            
            // Create document record
            \App\Models\Document::create([
                'business_id' => auth()->user()->business_id,
                'documentable_type' => PropertyTransaction::class,
                'documentable_id' => $transaction->id,
                'title' => 'Payment Advice - ' . $transaction->reference_number,
                'file_path' => $filePath,
                'file_type' => $file->getClientOriginalExtension(),
                'description' => 'Payment advice for unit sale transaction',
                'uploaded_by_user_id' => auth()->id(),
            ]);
        }
    }

    /**
     * Create a new buyer (Owner, Tenant, or Client)
     */
    private function createNewBuyer($buyerType, $validated)
    {
        $businessId = auth()->user()->business_id;

        if ($buyerType === 'owner') {
            $owner = Owner::create([
                'business_id' => $businessId,
                'first_name' => $validated['buyer_first_name'],
                'last_name' => $validated['buyer_last_name'],
                'email' => $validated['buyer_email'] ?? null,
                'phone_number' => $validated['buyer_phone'] ?? null,
            ]);
            return $owner->id;
        } elseif ($buyerType === 'tenant') {
            $tenant = Tenant::create([
                'business_id' => $businessId,
                'first_name' => $validated['buyer_first_name'],
                'last_name' => $validated['buyer_last_name'],
                'email' => $validated['buyer_email'] ?? null,
                'phone_number' => $validated['buyer_phone'] ?? null,
            ]);
            return $tenant->id;
        } elseif ($buyerType === 'client') {
            $client = Client::create([
                'business_id' => $businessId,
                'name' => $validated['buyer_name'],
                'email' => $validated['buyer_email'] ?? null,
                'phone_number' => $validated['buyer_phone'] ?? null,
            ]);
            return $client->id;
        }
    }

    /**
     * Map buyer type string to model class
     */
    private function getBuyerClass($buyerType)
    {
        $mapping = [
            'owner' => Owner::class,
            'tenant' => Tenant::class,
            'client' => Client::class,
        ];
        return $mapping[$buyerType] ?? Owner::class;
    }

    /**
     * View sale history for a unit
     */
    public function viewUnitSaleHistory($unitId)
    {
        $unit = PropertyUnit::with('property')->findOrFail($unitId);
        $sales = UnitSale::where('property_unit_id', $unitId)
            ->with('buyer', 'transactions')
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('units.sale-history', compact('unit', 'sales'));
    }
}
