<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Viewing;
use App\Models\Property;
use App\Models\PropertyUnit;
use App\Models\Agent;
use App\Models\Lead;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\CreateViewingRequest;
use App\Http\Requests\UpdateViewingRequest;

class ViewingController extends Controller
{
    /**
     * This controller will handle
     * Show viewings,
     * create, update and delete viewing.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Show all viewing.
     */
    public function index()
    {
        //
    }
    /**
     * Display a viewings of the property.
     *
     */
    public function propertyViewing($id)
    {
        $property = Property::findOrFail($id);
        $agents = Agent::all();
        $viewingStatus = ['scheduled', 'cancelled', 'completed', 'rescheduled'];
        $viewings = Viewing::where('property_id', $property->id)
                           ->with(['agent', 'lead', 'property', 'propertyUnit'])
                           ->orderBy('scheduled_at', 'asc')
                           ->paginate(10);
        return view('properties.property-viewings', compact('viewings','property', 'agents', 'viewingStatus'));
    }
    /**
     * Store a newly created viewing in storage.
     */
    public function createViewing(CreateViewingRequest $request)
    {
        $validatedData = $request->validated();

        $viewing = Viewing::create($validatedData);

        return redirect()->route('property.viewing', $viewing->property_id)
            ->with('message', 'Viewing scheduled successfully!');
    }

    /**
     * Update the specified viewing.
     */
    public function updateViewing(UpdateViewingRequest $request, $id)
    {
        $validatedData = $request->validated();

        $viewing = Viewing::findOrFail($id);
        $viewing->update($validatedData);

        return redirect()->route('property.viewing', $viewing->property_id)
            ->with('message', 'Viewing updated successfully!');
    }
    /**
     * Remove the specified viewing.
     */
    public function deleteViewing($id)
    {
        $viewing = Viewing::findOrFail($id);
        $viewing->delete();

        return redirect()->route('property.viewing', $viewing->property_id)
            ->with('message', 'Viewing deleted successfully!');
    }

}
