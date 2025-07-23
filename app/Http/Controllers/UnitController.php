<?php

namespace App\Http\Controllers;

use App\Models\PropertyUnit;
use App\Models\Property;
use App\Models\PropertyImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use App\Http\Requests\CreateUnitRequest;
use App\Http\Requests\UpdateUnitRequest;

class UnitController extends Controller
{
    /**
     * Display a listing of the units.
     *
     */
    public function index()
    {
        // Eager load related property for display
        $units = PropertyUnit::with('property')->paginate(10);
        return view('units.index', compact('units'));
    }

    /**
     * Show the form for creating a new unit.
     *
     */
    public function newUnit($id)
    {
        // Pass properties to select from, or pre-select if property_id is provided
        $property = Property::findOrFail($id);
        // You might want to filter properties here, e.g., only multi-unit properties

        $unitTypes = ['residential', 'commercial', 'land', 'other']; // Example static types

        return view('units.create', compact('properties', 'property', 'unitTypes'));
    }

    /**
     * Store a newly created unit in storage.
     *
     * @param  \App\Http\Requests\StoreUnitRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createUnit(CreateUnitRequest $request)
    {
        // The validated data is available via $request->validated()
        $validatedData = $request->validated();

        DB::transaction(function () use ($validatedData) {
            $unit = PropertyUnit::create($validatedData);

            $unit->property->increment('total_units');
        });

        return redirect()->route('property', $unit->property->id)->with('message', 'Unit created successfully!');
    }

    /**
     * Display the specified unit.
     *
     * @param  \App\Models\Unit  $unit
     * @return \Illuminate\View\View
     */
    public function showUnit($id)
    {
        $unit = PropertyUnit::with(['property', 'images'])->findOrFail($id);
        // If you want to display the first image as a featured image
        $featuredImage = $unit->images->firstWhere('is_featured', 1);
        $displayImage = $featuredImage ? $featuredImage->image_path : ($unit->images->count() > 0 ? $unit->images->first()->image_path : null);
        return view('properties.units.unit', compact('unit', 'displayImage', 'featuredImage'));
    }

    /**
     * Show the form for editing the specified unit.
     *
     * @param  \App\Models\Unit  $unit
     * @return \Illuminate\View\View
     */
    public function editUnit($id)
    {
        $unitTypes = ['residential', 'commercial', 'land', 'other']; // Example static types
        $unit = PropertyUnit::with('images')->findOrFail($id); // Eager load images for the edit form

        return view('units.edit', compact('unit', 'unitTypes'));
    }

    /**
     * Update the specified unit in storage.
     *
     */
    public function updateUnit(UpdateUnitRequest $request, Unit $unit) // Type-hint the custom request
    {
        $validatedData = $request->validated();

        DB::transaction(function () use ($validatedData, $unit) {
            $unit->update($validatedData);
        });

        return redirect()->route('units.show', $unit->id)->with('message', 'Unit updated successfully!');
    }

    /**
     * Remove the specified unit from storage.
     *
     */
    public function deleteUnit($id)
    {
        $unit = PropertyUnit::findOrFail($id);

        DB::transaction(function () use ($unit) {
            // Before deleting the unit, delete all associated image files
            foreach ($unit->images as $image) {
                $filePath = public_path($image->image_path);
                if (File::exists($filePath)) {
                    File::delete($filePath);
                }
            }
            $unit->delete();
        });

        return redirect()->route('units.index')->with('message', 'Unit deleted successfully!');
    }

    /**
     * Handle image uploads for a unit.
     *
     */
    public function uploadUnitImage(Request $request, $id)
    {
        $unit = PropertyUnit::findOrFail($id);
        $request->validate([
            'images' => ['required', 'array', 'min:1'],
            'images.*' => ['image', 'mimes:jpeg,png,jpg,gif,svg,webp', 'max:5120'], // Max 5MB per image
            'caption' => ['nullable', 'string', 'max:255'],
            'is_featured' => ['boolean'],
        ]);

        $isFeaturedSet = $request->boolean('is_featured');
        $existingFeatured = $unit->images()->where('is_featured', true)->first();

        DB::transaction(function () use ($request, $unit, $isFeaturedSet, $existingFeatured) {
            // If a new image is marked as featured, unmark any existing featured image for this unit
            if ($isFeaturedSet && $existingFeatured) {
                $existingFeatured->update(['is_featured' => false]);
            }

            foreach ($request->file('images') as $index => $image) {
                $fileName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $destinationPath = public_path('unit_images'); // Store unit images in a separate directory

                // Ensure the directory exists
                if (!File::isDirectory($destinationPath)) {
                    File::makeDirectory($destinationPath, 0755, true, true);
                }

                // Use the move() method to store the image
                $image->move($destinationPath, $fileName);

                $unit->images()->create([
                    'image_path' => 'unit_images/' . $fileName, // Store path relative to public
                    'caption' => $request->caption,
                    'is_featured' => ($isFeaturedSet && $index === 0) ? true : false, // Only set first uploaded as featured if checkbox is true
                    'order' => $unit->images()->count() + 1, // Simple ordering
                    'property_id' => null, // Explicitly set to null for unit images
                    'property_unit_id' => $unit->id, // Link to this unit
                ]);
            }
        });

        return redirect()->back()->with('message', 'Images uploaded successfully for the unit!');
    }

    /**
     * Set unit featured image.
     */
    public function setFeaturedImage($unitId, $imageId)
    {
        $unit = PropertyUnit::findOrFail($unitId);
        foreach ($unit->images as $img) {
            $img->is_featured = ($img->id == $imageId);
            $img->save();
        }
        return back()->with('message', 'Featured Image updated.');
    }

    /**
     * Remove a specific unit image from storage.
     *
     */
    public function deleteImage(PropertyImage $propertyImage)
    {
        // Ensure the image belongs to a unit (and not a property) before deleting
        if (is_null($propertyImage->property_unit_id)) {
            return redirect()->back()->with('error', 'Image does not belong to a unit.');
        }

        DB::transaction(function () use ($propertyImage) {
            // Delete the file from the public directory
            $filePath = public_path($propertyImage->image_path);
            if (File::exists($filePath)) {
                File::delete($filePath);
            }

            // Delete the record from the database
            $propertyImage->delete();
        });

        return redirect()->back()->with('message', 'Unit image deleted successfully!');
    }

}
