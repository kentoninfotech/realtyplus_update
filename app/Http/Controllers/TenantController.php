<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\CreateTenantRequest;
use App\Http\Requests\UpdateTenantRequest;

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
                
                // Get payments for this lease
                $payments = $lease->payments()->where('status', 'paid')->sum('amount');
                $totalPaidRent += $payments;
                
                // Calculate outstanding amount
                $expectedPayments = $lease->rent_amount;
                $outstanding = $expectedPayments - $payments;
                $totalOutstanding += max(0, $outstanding);
                
                // Get next payment date (renewal date or based on payment frequency)
                if (!$nextPaymentDate || $lease->renewal_date < $nextPaymentDate) {
                    $nextPaymentDate = $lease->renewal_date;
                }
            }
        }
        
        // Get all payments with pagination
        $payments = \App\Models\Payment::whereIn('lease_id', $leases->pluck('id'))
            ->with('lease')
            ->orderBy('payment_date', 'desc')
            ->paginate(10);
        
        // Get payment history (past 12 months)
        $recentPayments = \App\Models\Payment::whereIn('lease_id', $leases->pluck('id'))
            ->where('status', 'paid')
            ->where('payment_date', '>=', now()->subMonths(12))
            ->orderBy('payment_date', 'desc')
            ->get();
        
        return view('personnel.tenants.view-tenant', compact(
            'tenant', 
            'leases', 
            'payments',
            'totalRent',
            'totalPaidRent',
            'totalOutstanding',
            'activeLeases',
            'nextPaymentDate',
            'recentPayments'
        ));
    }
    /**
     * Store a new Tenant.
     **
     */
    public function createTenant(CreateTenantRequest $request)
    {
        DB::transaction(function () use ($request){
            //create user
            $user = User::create([
                'name' => $request->first_name . ' ' . $request->last_name,
                'email' => $request->email,
                'phone_number' => $request->phone_number ?? null,
                'password' => bcrypt($request->password),
                'user_type' => 'tenant',
                'status' => $request->status ?? 'active',
                'business_id' => auth()->user()->business_id,
            ]);

            // Assign tenant role
            $user->assignRole('Tenant');

            //set user & business ID
            $request['user_id'] = $user->id;
            $request['business_id'] = auth()->user()->business_id;

            //create tenant's record linked to user
            Tenant::create($request->except(['password', 'user_type', 'status']));

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
        $user = User::findOrFail($tenant->user_id);

        DB::transaction(function () use ($request, $tenant, $user) {
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
        $user = User::findOrFail($tenant->user_id);
        $tenant->delete();
        $user->delete();
        return redirect()->route('tenants')->with('message', 'Tenant deleted successfully.');
    }

    /**
     * Create tenant via AJAX for inline tenant creation.
     * Returns JSON response with new tenant data.
     */
    public function createTenantAjax(Request $request)
    {
        try {
            // Validate input
            $validated = $request->validate([
                'first_name' => 'required|string|max:100',
                'last_name' => 'required|string|max:100',
                'email' => [
                    'required',
                    'string',
                    'email',
                    'max:255',
                    Rule::unique('users')->where(function ($query) {
                        return $query->where('business_id', auth()->user()->business_id);
                    }),
                ],
                'phone_number' => 'nullable|string|max:150',
                'address' => 'nullable|string|max:200',
                'emergency_contact_name' => 'nullable|string|max:150',
                'emergency_contact_phone' => 'nullable|string|max:150',
            ]);

            $tenant = null;
            
            DB::transaction(function () use ($validated, &$tenant) {
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

                // Create tenant record
                $tenant = Tenant::create([
                    'user_id' => $user->id,
                    'business_id' => auth()->user()->business_id,
                    'first_name' => $validated['first_name'],
                    'last_name' => $validated['last_name'],
                    'email' => $validated['email'],
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
