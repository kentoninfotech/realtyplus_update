<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUnitSaleRequest;
use App\Models\PropertyUnit;
use App\Models\UnitSale;
use App\Models\Owner;
use App\Models\Tenant;
use App\Models\Client;
use App\Models\PropertyTransaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;

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
            'payment_method' => ['required', 'in:Bank Transfer,Cash,Credit Card,Cheque,Mobile Money'],
            'reference_number' => ['nullable', 'string', 'max:255'],
            'transaction_date' => ['required', 'date'],
            'payment_advice' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'], // 5MB max
        ]);

        $sale = UnitSale::findOrFail($saleId);

        // Check ownership
        if ($sale->business_id !== auth()->user()->business_id) {
            return redirect()->back()->with('error', 'Unauthorized access.');
        }

        return DB::transaction(function () use ($request, $sale) {
            // Mark sale as completed
            $sale->markCompleted();

            // Create PropertyTransaction for record and receipt generation
            $transaction = PropertyTransaction::create([
                'business_id' => auth()->user()->business_id,
                'transactionable_type' => UnitSale::class,
                'transactionable_id' => $sale->id,
                'payer_type' => $sale->buyer_type,
                'payer_id' => $sale->buyer_id,
                'type' => 'credit',
                'purpose' => 'unit_sale',
                'amount' => $sale->sale_price,
                'transaction_date' => $request->transaction_date,
                'payment_method' => $request->payment_method,
                'reference_number' => $request->reference_number ?? 'SALE-' . $sale->id . '-' . now()->timestamp,
                'status' => 'completed',
                'description' => "Unit Sale - {$sale->propertyUnit->unit_number} - {$sale->property->name}",
            ]);

            // Upload payment advice document if provided
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

            // Redirect to receipt/transaction view
            return redirect()->route('show.transaction', $transaction->id)
                ->with('success', 'Unit sale completed successfully! Receipt generated.');
        });
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
