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
        $viewings = Viewing::where('property_id', $property->id)->paginate(10);
        return view('properties.property-viewings', compact('viewings','property'));
    }

}
