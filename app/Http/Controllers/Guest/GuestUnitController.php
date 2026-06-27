<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Models\PropertyUnit;
use Illuminate\Http\Request;

class GuestUnitController extends Controller
{
    /**
     * Display a specific unit for guests
     */
    public function show($id)
    {
        $unit = PropertyUnit::with([
            'property',
            'property.propertyType',
            'property.images',
            'property.amenities',
            'property.agent',
            'property.owner'
        ])->findOrFail($id);

        $featuredImage = $unit->property->images->firstWhere('is_featured', 1);
        $displayImage = $featuredImage ? $featuredImage->image_path : ($unit->property->images->count() > 0 ? $unit->property->images->first()->image_path : null);
        
        // Get all images for gallery
        $galleryImages = $unit->property->images;

        $settings = [
            'site_title' => config('app.name'),
        ];

        return view('guest.unit-detail', compact('unit', 'displayImage', 'galleryImages', 'settings'));
    }
}
