<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MaintenanceRequest;
use App\Models\Property;
use App\Models\PropertyUnit;
use App\Models\Agent;
use App\Models\Lead;
use App\Models\User;
use App\Models\Personnel;
use App\Http\Requests\CreateMaintenanceRequest;
use App\Http\Requests\UpdateMaintenanceRequest;

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
        $users = User::where('user_type', 'tenant')->get();
        $personnel = User::where('user_type', 'staff')->with('roles')->get();
        $requestStatus = ['open','on_hold', 'in_progress', 'completed', 'cancelled'];
        $priorities = ['low', 'medium', 'high', 'urgent'];
        $maintenanceRequests = MaintenanceRequest::where('property_id', $property->id)->paginate(10);

        return view('properties.property-maintenance-requests', compact('maintenanceRequests','property', 'users', 'personnel', 'requestStatus', 'priorities'));
    }
    /**
     * Display a maintenanceRequests of the property.
     *
     */
    public function unitMaintenanceRequest($id)
    {
        $unit = PropertyUnit::findOrFail($id);
        $users = User::where('user_type', 'tenant')->get();
        $personnel = User::where('user_type', 'staff')->with('roles')->get();
        $requestStatus = ['open','on_hold', 'in_progress', 'completed', 'cancelled'];
        $priorities = ['low', 'medium', 'high', 'urgent'];
        $maintenanceRequests = MaintenanceRequest::where('property_unit_id', $unit->id)->paginate(10);

        return view('properties.units.unit-maintenance-requests', compact('maintenanceRequests','unit', 'users', 'personnel', 'requestStatus', 'priorities'));
    }
    /**
     * Store a newly created maintenance request.
     */
    public function createMaintenanceRequest(CreateMaintenanceRequest $request, $redir_to)
    {
        $validatedData = $request->validated();

        $maintenanceRequest = MaintenanceRequest::create($validatedData);

        if ($redir_to == 'property'){
            return redirect()->route('property.maintenanceRequest', $maintenanceRequest->property_id)
                         ->with('message', 'Maintenance request created successfully.');
        }else{
            return redirect()->route('unit.maintenanceRequest', $maintenanceRequest->property_unit_id)
                         ->with('message', 'Maintenance request created successfully.');
        }

    }

    /**
     * Update the specified maintenance request.
     */
    public function updateMaintenanceRequest(UpdateMaintenanceRequest $request, $id, $redir_to)
    {
        $maintenanceRequest = MaintenanceRequest::findOrFail($id);
        $maintenanceRequest->update($request->all());

        if ($redir_to == 'property'){
            return redirect()->route('property.maintenanceRequest', $maintenanceRequest->property_id)
                         ->with('message', 'Maintenance request updated successfully.');
        }else{
            return redirect()->route('unit.maintenanceRequest', $maintenanceRequest->property_unit_id)
                         ->with('message', 'Maintenance request updated successfully.');
        }

    }

    /**
     * Remove the specified maintenance request.
     */
    public function deleteMaintenanceRequest($id, $redir_to)
    {
        $maintenanceRequest = MaintenanceRequest::findOrFail($id);
        $maintenanceRequest->delete();

        if ($redir_to == 'property'){
            return redirect()->route('property.maintenanceRequest', $maintenanceRequest->property_id)
                         ->with('message', 'Maintenance request deleted successfully.');
        }else{
            return redirect()->route('unit.maintenanceRequest', $maintenanceRequest->property_unit_id)
                         ->with('message', 'Maintenance request deleted successfully.');
        }

    }

}
