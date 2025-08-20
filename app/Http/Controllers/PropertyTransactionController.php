<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PropertyTransaction;
use App\Services\TransactionService;
use App\Models\Lease;
use App\Models\Owner;
use App\Models\Agent;
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
    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;

        $this->middleware('auth');
    }
    /**
     * Show all transactions,
     */
    public function index()
    {
        $transactions = PropertyTransaction::with('payer', 'transactionable')->paginate(20);
        return view('properties.transactions.transactions', compact('transactions'));
    }
    /**
     * Show form to create transactions,
     */
    public function newTransaction()
    {

        $payerType = ['App\Models\Tenant', 'App\Models\Owner', 'App\Models\Agent'];
        // $payerType = ['tenant', 'owner', 'agent'];
        $transactionable = ['App\Models\Lease', 'App\Models\Property', 'App\Models\MaintenanceRequest'];
        $status = ['pending', 'completed', 'failed', 'reversed'];
        $purposes = TransactionService::PURPOSES;

        return view('properties.transactions.new-transaction', compact('status', 'purposes', 'payerType', 'transactionable'));
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
        $method = ['Bank Transfer', 'Cash', 'Credit Card', 'Cheque'];
        $purposes = TransactionService::PURPOSES;

        return view('properties.transactions.new-lease-transaction', compact('lease', 'method', 'status', 'purposes', 'payerType','payerId'));
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

        $transaction = $this->transactionService->create($validated, $files);

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
    /**
     * search for payers,
     */
    public function search(Request $request)
    {
        $type = $request->query('type');
        $term = $request->query('q');

        // Check if the requested type corresponds to a valid model class
        if (class_exists($type)) {
            $model = new $type;

            $results = $model->where('first_name', 'like', "%{$term}%")
                ->orWhere('last_name', 'like', "%{$term}%")
                ->limit(10)
                ->get(['id', 'first_name', 'last_name']);
        } else {
            $results = []; // Return an empty array if the model type is invalid
        }

        return response()->json($results);
    }
    /**
     * delete transaction records
     */
    public function deleteTransaction($id)
    {
        $transaction = PropertyTransaction::findOrFail($id);
        $transaction->delete();
        return redirect()->route('property.transaction')->with('message', 'Transaction deleted successful!');
    }

}
