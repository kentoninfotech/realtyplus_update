<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Property;
use App\Models\PropertyImage;
use App\Models\PropertyUnit;


class PropertiesTableSeeder extends Seeder
{
    public function run()
    {
        Property::factory(15)
            ->has(PropertyUnit::factory()->count(rand(0, 5)), 'units') // Create 0-5 units per property
            ->create()
            ->each(function ($property) {
                // Create 3-7 images per property
                PropertyImage::factory(rand(3, 7))->create([
                    'property_id' => $property->id,
                    'property_unit_id' => null, // Ensure these are property-level images
                ]);

                // For properties with units, add images to some units
                if ($property->units->count() > 0) {
                    $property->units->each(function ($unit) {
                        PropertyImage::factory(rand(1, 3))->create([
                            'property_id' => $unit->property_id,
                            'property_unit_id' => $unit->id,
                        ]);
                    });
                }
            });
    }
}
