<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\CreateTenantRequest;
use App\Http\Requests\UpdateTenantRequest;
use Illuminate\Validation\Rule;

class TenantController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Show all Tenants.
     * Modify tenants records, properties, and transactions.
     **
     */
    public function index()
    {
        $tenants = Tenant::with('user')->paginate(10);
        return view('personnel.tenants.tenants', compact('tenants'));
    }

    /**
     * Show add new Tenant form.
     **
     */
    public function newTenant()
    {
        return view('personnel.tenants.new-tenant');
    }
    /**
     * Show Tenant form with comprehensive details.
     **
     */
    public function showTenant($id)
    {
        // Show tenant details with all related information
        $tenant = Tenant::with(['user', 'leases.property', 'leases.propertyUnit'])->findOrFail($id);
        
        // Get all leases for the tenant
        $leases = $tenant->leases()->with(['property', 'propertyUnit', 'payments'])->get();
        
        // Calculate payment statistics
        $totalRent = 0;
        $totalPaidRent = 0;
        $totalOutstanding = 0;
        $activeLeases = 0;
        $nextPaymentDate = null;
        
        foreach ($leases as $lease) {
            if ($lease->status === 'active') {
                $activeLeases++;
                $totalRent += $lease->rent_amount;
                
                // Get PropertyTransaction records for this lease (which have accurate payment tracking)
                $transactions = \App\Models\PropertyTransaction::where('transactionable_type', 'App\Models\Lease')
                    ->where('transactionable_id', $lease->id)
                    ->where('type', 'credit')
                    ->get();
                
                // Calculate total paid from successful PropertyTransaction records
                $transactionPaid = $transactions->sum('amount');
                $totalPaidRent += $transactionPaid;
                
                // Calculate outstanding amount based on PropertyTransaction balance tracking
                // If any transaction has is_partial_payment = true or balance_due > 0, sum those balances
                $leaseOutstanding = 0;
                foreach ($transactions as $transaction) {
                    if ($transaction->is_partial_payment && $transaction->balance_due > 0) {
                        $leaseOutstanding += $transaction->balance_due;
                    }
                }
                
                // If no transactions or all are fully paid, check if there's unpaid rent
                if ($leaseOutstanding === 0) {
                    $leaseOutstanding = $lease->rent_amount - $transactionPaid;
                }
                
                $totalOutstanding += max(0, $leaseOutstanding);
                
                // Get next payment date (renewal date or based on payment frequency)
                if (!$nextPaymentDate || $lease->renewal_date < $nextPaymentDate) {
                    $nextPaymentDate = $lease->renewal_date;
                }
            }
        }
        
        // Get all PropertyTransaction records for this tenant's leases
        $allTransactions = \App\Models\PropertyTransaction::whereIn(
            'transactionable_id',
            $leases->pluck('id')
        )
            ->where('transactionable_type', 'App\Models\Lease')
            ->where('type', 'credit')
            ->with(['transactionable.property', 'transactionable.propertyUnit'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        // Get payment history (past 12 months) from PropertyTransaction
        $recentPayments = \App\Models\PropertyTransaction::whereIn(
            'transactionable_id',
            $leases->pluck('id')
        )
            ->where('transactionable_type', 'App\Models\Lease')
            ->where('type', 'credit')
            ->where('created_at', '>=', now()->subMonths(12))
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Get old Payment model records for display compatibility
        $payments = \App\Models\Payment::whereIn('lease_id', $leases->pluck('id'))
            ->with('lease')
            ->orderBy('payment_date', 'desc')
            ->paginate(10);
        
        return view('personnel.tenants.view-tenant', compact(
            'tenant', 
            'leases', 
            'payments',
            'totalRent',
            'totalPaidRent',
            'totalOutstanding',
            'activeLeases',
            'nextPaymentDate',
            'recentPayments',
            'allTransactions'
        ));
    }
    /**
     * Store a new Tenant.
     **
     */
    public function createTenant(CreateTenantRequest $request)
    {
        DB::transaction(function () use ($request){
            $userId = null;
            
            // Only create user account if email is provided
            if (!empty($request->email)) {
                //create user
                $user = User::create([
                    'name' => $request->first_name . ' ' . $request->last_name,
                    'email' => $request->email,
                    'phone_number' => $request->phone_number ?? null,
                    'password' => bcrypt($request->password ?? str()->random(12)),
                    'user_type' => 'tenant',
                    'status' => $request->status ?? 'active',
                    'business_id' => auth()->user()->business_id,
                ]);

                // Assign tenant role
                $user->assignRole('Tenant');
                
                $userId = $user->id;
            }

            //set user & business ID
            $tenantData = $request->except(['password', 'user_type', 'status']);
            $tenantData['user_id'] = $userId;
            $tenantData['business_id'] = auth()->user()->business_id;

            //create tenant's record linked to user (or without user if no email)
            Tenant::create($tenantData);

        });

        return redirect()->route('tenants')->with('message', 'Tenant created successfully.');

    }
    /**
     * Show edit Tenant form.
     **
     */
    public function editTenant($id)
    {
        $tenant = Tenant::findOrFail($id);
        return view('personnel.tenants.edit-tenant', compact('tenant'));
    }
    /**
     * Update Tenant records.
     **
     */
    public function updateTenant(UpdateTenantRequest $request, $id)
    {
        $tenant = Tenant::findOrFail($id);
        
        DB::transaction(function () use ($request, $tenant) {
            // If tenant has an associated user, update it
            if ($tenant->user_id) {
                $user = User::findOrFail($tenant->user_id);
                
                // Check If password is provided, hash it; otherwise, keep the existing password
                if (isset($request->password) && !empty($request->password)) {
                    $request->merge(['password' => bcrypt($request->password)]);
                } else {
                    $request->merge(['password' => $user->password]); // Keep the existing password if not provided 
                }

                // Update user
                $user->update([
                    'name' => $request->first_name . ' ' . $request->last_name,
                    'email' => $request->email,
                    'phone_number' => $request->phone_number ?? null,
                    'password' => $request->password,
                    'user_type' => 'tenant',
                    'status' => $request->status ?? 'active',
                ]);
            }

            // Update tenant
            $tenant->update($request->except(['password', 'user_type', 'status']));
        });

        return redirect()->route('tenants')->with('message', 'Tenant records updated successfully.');
    }
    /**
     * Delete an Tenant.
     **
     */
    public function deleteTenant($id)
    {
        $tenant = Tenant::findOrFail($id);
        
        // Delete associated user if exists
        if ($tenant->user_id) {
            $user = User::find($tenant->user_id);
            if ($user) {
                $user->delete();
            }
        }
        
        $tenant->delete();
        return redirect()->route('tenants')->with('message', 'Tenant deleted successfully.');
    }

    /**
     * Create tenant via AJAX for inline tenant creation.
     * Returns JSON response with new tenant data.
     */
    public function createTenantAjax(Request $request)
    {
        try {
            // Validate input - email is now optional
            $validated = $request->validate([
                'first_name' => 'required|string|max:100',
                'last_name' => 'required|string|max:100',
                'email' => [
                    'nullable',
                    'string',
                    'email',
                    'max:255',
                    Rule::unique('users')->where(function ($query) {
                        return $query->where('business_id', auth()->user()->business_id);
                    })->whereNull('deleted_at'),
                ],
                'phone_number' => 'nullable|string|max:150',
                'address' => 'nullable|string|max:200',
                'emergency_contact_name' => 'nullable|string|max:150',
                'emergency_contact_phone' => 'nullable|string|max:150',
            ]);

            $tenant = null;
            
            DB::transaction(function () use ($validated, &$tenant) {
                $userId = null;
                
                // Only create user account if email is provided
                if (!empty($validated['email'])) {
                    // Generate a temporary password
                    $tempPassword = str()->random(12);

                    // Create user
                    $user = User::create([
                        'name' => $validated['first_name'] . ' ' . $validated['last_name'],
                        'email' => $validated['email'],
                        'phone_number' => $validated['phone_number'] ?? null,
                        'password' => bcrypt($tempPassword),
                        'user_type' => 'tenant',
                        'status' => 'active',
                        'business_id' => auth()->user()->business_id,
                    ]);

                    // Assign tenant role
                    $user->assignRole('Tenant');
                    
                    $userId = $user->id;
                }

                // Create tenant record (with or without user account)
                $tenant = Tenant::create([
                    'user_id' => $userId,
                    'business_id' => auth()->user()->business_id,
                    'first_name' => $validated['first_name'],
                    'last_name' => $validated['last_name'],
                    'email' => $validated['email'] ?? null,
                    'phone_number' => $validated['phone_number'] ?? null,
                    'address' => $validated['address'] ?? null,
                    'emergency_contact_name' => $validated['emergency_contact_name'] ?? null,
                    'emergency_contact_phone' => $validated['emergency_contact_phone'] ?? null,
                ]);
            });

            return response()->json([
                'success' => true,
                'message' => 'Tenant created successfully!',
                'tenant' => [
                    'id' => $tenant->id,
                    'first_name' => $tenant->first_name,
                    'last_name' => $tenant->last_name,
                    'email' => $tenant->email,
                    'full_name' => "{$tenant->first_name} {$tenant->last_name}",
                ]
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating tenant: ' . $e->getMessage()
            ], 500);
        }
    }

}
