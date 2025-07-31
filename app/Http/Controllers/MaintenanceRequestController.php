<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MaintenanceRequest;
use App\Models\Property;
use App\Models\PropertyUnit;
use App\Models\Agent;
use App\Models\Lead;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\CreateMaintenanceRequestRequest;
use App\Http\Requests\UpdateMaintenanceRequestRequest;

class MaintenanceRequestController extends Controller
{
    /**
     * This controller will handle
     * Show maintenanceRequests,
     * create, update and delete Maintenance Request.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Show all Maintenance Request.
     */
    public function index()
    {
        //
    }
    /**
     * Display a maintenanceRequests of the property.
     *
     */
    public function propertyMaintenanceRequest($id)
    {
        $property = Property::findOrFail($id);
        $maintenanceRequests = MaintenanceRequest::where('property_id', $property->id)->paginate(10);
        return view('properties.property-maintenance-requests', compact('maintenanceRequests','property'));
    }

}
