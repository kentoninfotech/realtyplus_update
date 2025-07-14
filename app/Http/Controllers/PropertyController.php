<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Property;
use App\Models\PropertyType;
use App\Models\Amenity;
use App\Models\Agent;
use App\Models\Owner;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\CreatePropertyRequest;
use App\Http\Requests\UpdatePropertyRequest;

class PropertyController extends Controller
{
    /**
     * This controller will handle 
     * Show Properties, 
     * create, update and delete properties.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Show all properties.
     */
    public function index()
    {
        $properties = Property::with(['propertyType', 'agent', 'owner'])->paginate(30);
        return view('properties.index', compact('properties'));
    }

    /**
     * Show the form for creating a new property.
     */
    public function newProperty()
    {
        $propertyTypes = PropertyType::all();
        $amenities = Amenity::all();
        $owners = Owner::all();
        $agents = Agent::all();
        return view('properties.new-property', compact('propertyTypes', 'amenities', 'owners', 'agents'));
    }

    /**
     * Store a newly created property in storage.
     */
    public function createProperty(CreatePropertyRequest $request)
    {
        $data = $request->validated();
        // Add businness ID
        $data['business_id'] = auth()->user()->business_id;
        // Remove amenities from $data if present
        $amenities = $data['amenities'] ?? [];
        unset($data['amenities']);

        $property = Property::create($data);
        if (!empty($amenities)) {
            $property->amenities()->sync($amenities);
        }
        return redirect()->route('properties')->with('message', 'Property created successfully.');
    }
    /**
     * Show the form for editing the specified property.
     */
    public function editProperty($id)
    {
        $property = Property::findOrFail($id);
        $propertyTypes = PropertyType::all();
        $amenities = Amenity::all();
        $owners = Owner::all();
        $agents = Agent::all();

        return view('properties.edit-property', compact('property', 'propertyTypes', 'amenities', 'owners', 'agents'));
    }
    /**
     * Update the specified property in storage.
     */
    public function updateProperty(UpdatePropertyRequest $request, $id)
    {
        $property = Property::findOrFail($id);
        $data = $request->validated();
        // Add businness ID
        $data['business_id'] = auth()->user()->business_id;
        // Remove amenities from $data if present
        $amenities = $data['amenities'] ?? [];
        unset($data['amenities']);

        $property->update($data);
        if (!empty($amenities)) {
            $property->amenities()->sync($amenities);
        } else {
            $property->amenities()->detach();
        }

        return redirect()->route('properties')->with('message', 'Property updated successfully.');
    }  
    /**
     * Remove the specified property from storage.
     */
    public function deleteProperty($id)
    {
        $property = Property::findOrFail($id);
        $property->amenities()->detach(); // Detach amenities before deleting

        // Delete linked images from storage and database
        if ($property->images && $property->images->count()) {
            foreach ($property->images as $image) {
                if ($image->path && Storage::exists($image->path)) {
                    Storage::delete($image->path);
                }
                $image->delete();
            }
        }
        $property->delete();
        return redirect()->route('properties')->with('message', 'Property deleted successfully.');
    }

}
