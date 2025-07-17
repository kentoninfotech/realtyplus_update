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
use Illuminate\Support\Facades\DB;

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
     * Show property.
     */
    public function showProperty($id)
    {
        $property = Property::with(['propertyType', 'agent', 'owner', 'amenities', 'images'])->findOrFail($id);
        return view('properties.property', compact('property'));
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
        $validatedData = $request->validated();
        // Add businness ID
        $validatedData['business_id'] = auth()->user()->business_id;
        // Fetch the selected property type again, as it's needed for unit creation logic
        $propertyType = PropertyType::find($validatedData['property_type_id']);

        DB::transaction(function () use ($validatedData, $propertyType, $request) {
            // Create the Property record
            $property = Property::create([
                'property_type_id' => $validatedData['property_type_id'],
                'agent_id' => $validatedData['agent_id'],
                'business_id' => $validatedData['business_id'],
                'owner_id' => $validatedData['owner_id'],
                'name' => $validatedData['name'],
                'address' => $validatedData['address'],
                'state' => $validatedData['state'],
                'country' => $validatedData['country'],
                'description' => $validatedData['description'] ?? null,
                'status' => 'available', // Default status for new property
                'latitude' => $validatedData['latitude'] ?? null,
                'longitude' => $validatedData['longitude'] ?? null,
                'has_units' => $validatedData['has_units'],
                'total_units' => $validatedData['has_units'] ? ($validatedData['total_units'] ?? 0) : 1, // Set total_units based on has_units
                'area_sqft' => $validatedData['area_sqft'] ?? null,
                'lot_size_sqft' => $validatedData['lot_size_sqft'] ?? null,
                'year_built' => $validatedData['year_built'] ?? null,
                'purchase_price' => $validatedData['purchase_price'] ?? null,
                'sale_price' => $validatedData['sale_price'] ?? null,
                'rent_price' => $validatedData['rent_price'] ?? null,
                'date_acquired' => $validatedData['date_acquired'] ?? null,
                'listing_type' => $validatedData['listing_type'],
                'listed_at' => $validatedData['listed_at'] ?? null,
            ]);

            // Handle Unit Creation based on `properties.has_units`
            if (!$property->has_units) {
                // This is a single-unit property, create one unit record
                $unitData = [
                    'property_id' => $property->id,
                    'status' => 'available', // Default status for new unit
                    'rent_price' => $validatedData['rent_price'] ?? null, // Default to property's rent_price
                    'sale_price' => $validatedData['sale_price'] ?? null, // Default to property's sale_price
                    'description' => $validatedData['description'] ?? null, // Use property description for unit
                ];

                if ($propertyType->slug === 'land-parcel') {
                    // Specifics for a single land unit
                    $unitData['unit_number'] = $validatedData['cadastral_id_single'] ?? 'Plot 1';
                    $unitData['area_sqm'] = $validatedData['area_sqm_single'];
                    $unitData['zoning_type'] = $validatedData['zoning_type_single'] ?? null;
                    $unitData['square_footage'] = null; // No built area for land
                    $unitData['bedrooms'] = null;
                    $unitData['bathrooms'] = null;
                    $unitData['unit_type'] = 'Land'; // Set unit_type for land
                } else {
                    // Specifics for a single residential/commercial unit
                    $unitData['unit_number'] = 'Main'; // Default for single built units
                    $unitData['bedrooms'] = $validatedData['bedrooms'] ?? null;
                    $unitData['bathrooms'] = $validatedData['bathrooms'] ?? null;
                    $unitData['square_footage'] = $validatedData['area_sqft'] ?? null; // Property's area_sqft is this unit's size
                    $unitData['area_sqm'] = null; // No land area for built unit
                    $unitData['zoning_type'] = null; // Not applicable for residential
                    // Determine unit_type based on property type, e.g., 'Residential', 'Commercial'
                    $unitData['unit_type'] = $propertyType->is_residential ? 'Residential' : 'Commercial';
                }
                $property->units()->create($unitData);

            } else {
                // This is a multi-unit property. Units will be added/managed separately.
                // You might want to redirect to a unit management page here.
            }

            // Sync amenities
            if (isset($validatedData['amenities'])) {
                $property->amenities()->sync($validatedData['amenities']);
            } else {
                $property->amenities()->detach(); // Detach all if no amenities selected
            }
        });

        return redirect()->route('properties')->with('message', 'Property created successfully.');
    }
    /**
     * Show the form for editing the specified property.
     */
    public function editProperty($id)
    {
        $property = Property::with(['units', 'amenities'])->findOrFail($id);
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
        
        // The validated data is available via $request->validated()
        $validatedData = $request->validated();
        // Add businness ID
        $validatedData['business_id'] = auth()->user()->business_id;

        // Fetch the selected property type again, as it's needed for unit creation logic
        $propertyType = PropertyType::find($validatedData['property_type_id']);

        DB::transaction(function () use ($validatedData, $property, $propertyType, $request) {
            // Update the Property record
            $property->update([
                'property_type_id' => $validatedData['property_type_id'],
                'agent_id' => $validatedData['agent_id'],
                'owner_id' => $validatedData['owner_id'],
                'name' => $validatedData['name'],
                'address' => $validatedData['address'],
                'state' => $validatedData['state'],
                'country' => $validatedData['country'],
                'description' => $validatedData['description'] ?? null,
                'latitude' => $validatedData['latitude'] ?? null,
                'longitude' => $validatedData['longitude'] ?? null,
                'has_units' => $validatedData['has_units'],
                'total_units' => $validatedData['has_units'] ? ($validatedData['total_units'] ?? 0) : 1, // Update total_units
                'area_sqft' => $validatedData['area_sqft'] ?? null,
                'lot_size_sqft' => $validatedData['lot_size_sqft'] ?? null,
                'year_built' => $validatedData['year_built'] ?? null,
                'purchase_price' => $validatedData['purchase_price'] ?? null,
                'sale_price' => $validatedData['sale_price'] ?? null,
                'rent_price' => $validatedData['rent_price'] ?? null,
                'date_acquired' => $validatedData['date_acquired'] ?? null,
                'listing_type' => $validatedData['listing_type'],
                'listed_at' => $validatedData['listed_at'] ?? null,
            ]);

            // Handle Unit Update/Creation/Deletion based on `properties.has_units`
            if (!$property->has_units) {
                // If it's now a single-unit property:
                // Find or create the primary unit. If multiple units exist, delete extras.
                $unit = $property->units()->firstOrNew([]); // Find first unit or create new instance

                $unitData = [
                    'status' => 'Available', // Default status for unit
                    'rent_price' => $validatedData['rent_price'] ?? null,
                    'sale_price' => $validatedData['sale_price'] ?? null,
                    'deposit_amount' => null, // Not on form, default null
                    'available_from' => null, // Not on form, default null
                    'description' => $validatedData['description'] ?? null,
                ];

                if ($propertyType->slug === 'land-parcel') {
                    // Specifics for a single land unit
                    $unitData['unit_number'] = $validatedData['cadastral_id_single'] ?? 'Plot 1';
                    $unitData['area_sqm'] = $validatedData['area_sqm_single'];
                    $unitData['zoning_type'] = $validatedData['zoning_type_single'] ?? null;
                    $unitData['square_footage'] = null; // No built area for land
                    $unitData['bedrooms'] = null;
                    $unitData['bathrooms'] = null;
                    $unitData['unit_type'] = 'Land';
                } else {
                    // Specifics for a single residential/commercial unit
                    $unitData['unit_number'] = 'Main'; // Default for single built units
                    $unitData['bedrooms'] = $validatedData['bedrooms'] ?? null;
                    $unitData['bathrooms'] = $validatedData['bathrooms'] ?? null;
                    $unitData['square_footage'] = $validatedData['area_sqft'] ?? null; // Property's area_sqft is this unit's size
                    $unitData['area_sqm'] = null;
                    $unitData['zoning_type'] = null;
                    $unitData['unit_type'] = $propertyType->is_residential ? 'Residential' : 'Commercial';
                }
                $unit->fill($unitData)->save(); // Fill and save the unit

                // Delete any other units if the property is now marked as single-unit
                $property->units()->where('id', '!=', $unit->id)->delete();

            } else {
                // If it's now a multi-unit property:
                // If it was previously a single unit, we might need to clear its specific data
                // or convert its single unit into a placeholder/first unit of the complex.
                // For simplicity, we'll just ensure no single-unit specific data remains
                // and units will be managed externally.
                // If you had a single unit and now it's multi, you might want to delete the old unit
                // and manage new units through a separate interface.
                $property->units()->delete(); // Delete all existing units, they'll be managed externally
            }

            // Sync amenities
            if (isset($validatedData['amenities'])) {
                $property->amenities()->sync($validatedData['amenities']);
            } else {
                $property->amenities()->detach(); // Detach all if no amenities selected
            }
        });

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
