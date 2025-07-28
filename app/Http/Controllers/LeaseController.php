<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lease;

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
        //
    }
    /**
     * Display a leases of the property and units.
     *
     */
    public function propertyLease($id)
    {
        $leases = Lease::where('property_id', $id)->paginate();
        return view('properties.leases', compact('lease'));
    }

}
