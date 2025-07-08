<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Property;
use App\Models\Amenity;

class PropertyAmenityTableSeeder extends Seeder
{
    public function run()
    {
        $properties = Property::all();
        $amenities = Amenity::all();

        if ($properties->isEmpty() || $amenities->isEmpty()) {
            echo "Skipping PropertyAmenityTableSeeder: No properties or amenities found. Run PropertiesTableSeeder and AmenitiesTableSeeder first.\n";
            return;
        }

        foreach ($properties as $property) {
            // Attach 1 to 5 random amenities to each property
            $property->amenities()->attach(
                $amenities->random(rand(1, min(5, $amenities->count())))->pluck('id')->toArray()
            );
        }
    }
}
