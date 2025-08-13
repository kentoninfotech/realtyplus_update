<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lease;
use App\Models\Property;
use App\Models\PropertyUnit;
use App\Models\Tenant;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\CreateLeaseRequest;
use App\Http\Requests\UpdateLeaseRequest;

class LeaseController extends Controller
{
    /**
     * This controller will handle
     * Show leases,
     * create, update and delete lease.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Show all lease.
     */
    public function index()
    {
        $leases = Lease::with(['property', 'propertyUnit', 'tenant'])->paginate(50);
        return view('properties.leases.leases', compact('leases'));
    }
    /**
     * Display leases of the property.
     *
     */
    public function propertyLease($id)
    {
        $property = Property::findOrFail($id);
        $leases = Lease::where('property_id', $property->id)->paginate(20);
        return view('properties.leases.property-leases', compact('leases','property'));
    }
    /**
     * Display leases of the units.
     *
     */
    public function unitLease($id)
    {
        $unit = PropertyUnit::findOrFail($id);
        $leases = Lease::where('property_unit_id', $unit->id)->paginate(20);
        return view('properties.leases.unit-leases', compact('leases','unit'));
    }
    /**
     * Show the form for creating a new lease.
     *
     */
    public function newLease(PropertyUnit $unit = null)
    {
        $properties = Property::all();
        $tenants = Tenant::all();
        $paymentFrequencies = ['monthly', 'quarterly', 'annually', 'bi-annually'];
        $leaseStatuses = ['active', 'pending', 'terminated', 'renewed', 'expired'];

        // If a unit is pre-selected, fetch its property for display and filter units
        $selectedProperty = null;
        $unitsForDropdown = collect(); // Initialize as empty collection

        if ($unit) {
            $selectedProperty = $unit->property;
            $unitsForDropdown = $selectedProperty->units; // Only units of the pre-selected property
        }

        return view('properties.leases.new-lease', compact(
            'properties',
            'tenants',
            'paymentFrequencies',
            'leaseStatuses',
            'unit', // Pass the pre-selected unit if available
            'selectedProperty', // Pass the selected property
            'unitsForDropdown' // Pass units related to the selected property (or empty)
        ));
    }
    /**
     * Show the form for creating a new lease.
     *
     */
    public function newPropertyLease($id)
    {
        $property = Property::findOrFail($id);
        $units = $property->units;
        $tenants = Tenant::all();
        $paymentFrequencies = ['monthly', 'quarterly', 'annually', 'bi-annually'];
        $leaseStatuses = ['active', 'pending', 'terminated', 'renewed', 'expired'];

        // If a unit is pre-selected, fetch its property for display and filter units
        $selectedProperty = null;
        $unitsForDropdown = collect(); // Initialize as empty collection

        // if ($unit) {
        //     $selectedProperty = $unit->property;
        //     $unitsForDropdown = $selectedProperty->units; // Only units of the pre-selected property
        // }

        return view('properties.leases.new-property-lease', compact(
            'property',
            'tenants',
            'paymentFrequencies',
            'leaseStatuses',
            'units', // Pass the pre-selected unit if available
            'selectedProperty', // Pass the selected property
            'unitsForDropdown' // Pass units related to the selected property (or empty)
        ));
    }
     /**
     * Store a newly created lease in storage.
     *
     */
    public function createLease(CreateLeaseRequest $request)
    {
        $validatedData = $request->validated();

        DB::transaction(function () use ($validatedData) {
            $lease = Lease::create($validatedData);

            // Update unit status if lease is active/occupied and a unit is associated
            if ($lease->property_unit_id && ($lease->status === 'active' || $lease->status === 'pending')) {
                $unit = PropertyUnit::find($lease->property_unit_id);
                if ($unit) {
                    $unit->update(['status' => 'occupied']);
                }
            }
        });

        return redirect()->route('leases')->with('message', 'Lease created successfully!');
    }
    /**
     * Get units by property ID (for dynamic dropdowns in forms)
     */
    public function getUnitsByProperty(Request $request)
    {
        $propertyId = $request->input('property_id');
        $units = PropertyUnit::where('property_id', $propertyId)->get(['id', 'unit_number']);
        return response()->json($units);
    }

}
