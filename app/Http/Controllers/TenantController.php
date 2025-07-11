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
        $tenants = Tenant::with('user', 'properties')->paginate(10);
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
     * Show Tenant form.
     **
     */
    public function showTenant($id)
    {
        //
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
                'status' => $request->status ?? 'Active',
                'business_id' => auth()->user()->business_id,
            ]);

            // Assign tenant role
            $user->assignRole('tenant');

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
                'status' => $request->status ?? 'Active',
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

}
