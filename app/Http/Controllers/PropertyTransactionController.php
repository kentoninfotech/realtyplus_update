<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PropertyTransaction;
use App\Services\TransactionService;
use App\Models\Lease;
use App\Models\Tenant;
use App\Models\Property;
use App\Models\PropertyUnit;
use App\Http\Requests\CreatePropertyTransactionRequest;
use App\Http\Requests\UpdatePropertyTransactionRequest;

class PropertyTransactionController extends Controller
{
    /**
     * This controller will handle transactions
     * Show transactions,
     * create, update and delete transactions.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Show form to create transactions,
     */
    public function addLeaseTransaction($id)
    {
        $lease = Lease::findOrFail($id);

        $payerType = '\App\Models\Tenant::class';
        $payerId = $lease->tenant->id;
        $status = ['pending', 'completed', 'failed', 'reversed'];
        $purposes = TransactionService::PURPOSES;

        return view('properties.transactions.new-lease-transaction', compact('lease', 'status', 'purposes', 'payerType','payerId'));
    }
    /**
     * store transactions record,
     */
    public function createLeaseTransaction(CreatePropertyTransactionRequest $request, $id, TransactionService $service)
    {
        $lease = Lease::findOrFail($id);

        $validated = $request->validated();

        $validated['transactionable_type'] = 'App\Models\Lease'; 
        $validated['transactionable_id'] = $lease->id;

        $validated['payer_type'] = 'App\Models\Tenant'; 
        $validated['payer_id'] = $lease->tenant_id;

        $files = $request->file('documents', []);

        $transaction = $service->create($validated, $files);

        return redirect()->route('show.transaction', $transaction->id)
            ->with('message', 'Transaction for lease created successfully.');
    }
    /**
     * store transactions record,
     */
    public function showTransaction($id)
    {
        $transaction = PropertyTransaction::findOrFail($id);
        return view('properties.transactions.show-transaction', compact('transaction'));
    }

}
