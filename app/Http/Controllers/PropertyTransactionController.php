<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\TransactionService;
use App\Services\MorphSearchService;
use App\Models\PropertyTransaction;
use App\Models\Lease;
use App\Models\Owner;
use App\Models\Agent;
use App\Models\Tenant;
use App\Models\Property;
use App\Models\PropertyUnit;
use App\Models\MaintenanceRequest;
use App\Http\Requests\CreatePropertyTransactionRequest;
use App\Http\Requests\UpdatePropertyTransactionRequest;

class PropertyTransactionController extends Controller
{
    /**
     * This controller will handle transactions
     * Show transactions,
     * create, update and delete transactions.
     */
    protected $transactionService;
    protected $morphSearch;

    public function __construct(TransactionService $transactionService, MorphSearchService $morphSearch)
    {
        $this->transactionService = $transactionService;
        $this->morphSearch = $morphSearch;

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
        $transactionable = ['App\Models\Lease', 'App\Models\Property', 'App\Models\MaintenanceRequest'];
        $status = ['pending', 'completed', 'failed', 'reversed'];
        $method = ['Bank Transfer', 'Cash', 'Credit Card', 'Cheque'];
        $purposes = $this->transactionService::PURPOSES;

        return view('properties.transactions.new-transaction', compact('status', 'method', 'purposes', 'payerType', 'transactionable'));
    }
    /**
     * store transaction records
     */
    public function createTransaction(CreatePropertyTransactionRequest $request)
    {
        $validated = $request->validated();

        $files = $request->file('documents', []); // array of UploadedFile
        
        $transaction = $this->transactionService->create($validated, $files);

        return redirect()->route('show.transaction', $transaction->id)
            ->with('message', 'Transaction created successfully.');
    }
    /**
     * Show form to create lease transactions,
     */
    public function addLeaseTransaction($id)
    {
        $lease = Lease::findOrFail($id);

        $payerType = 'App\Models\Tenant';
        $payerId = $lease->tenant->id;
        $status = ['pending', 'completed', 'failed', 'reversed'];
        $method = ['Bank Transfer', 'Cash', 'Credit Card', 'Cheque'];
        $purposes = TransactionService::PURPOSES;

        return view('properties.transactions.new-lease-transaction', compact('lease', 'method', 'status', 'purposes', 'payerType','payerId'));
    }
    /**
     * store transactions record,
     */
    public function createLeaseTransaction(CreatePropertyTransactionRequest $request)
    {
        $validated = $request->validated();

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
    public function searchPayers(Request $request, MorphSearchService $morphSearch)
    {
        $results = $this->morphSearch->search(
            $request->query('type'),
            $request->query('q'),
        );

        return response()->json($results);
    }
    /**
     * Search for transactionables
     */
    public function searchTransactionables(Request $request)
    {
        $results = $this->morphSearch->search(
            $request->query('type'),
            $request->query('q'),
        );

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
