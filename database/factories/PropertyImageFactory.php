<?php

namespace Database\Factories;

use App\Models\PropertyImage;
use App\Models\Property;
use App\Models\PropertyUnit;
use App\Models\Business;
use Illuminate\Database\Eloquent\Factories\Factory;

class PropertyImageFactory extends Factory
{
    protected $model = PropertyImage::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $hasUnit = $this->faker->boolean(50); // 50% chance to link to a unit
        $property = Property::inRandomOrder()->first() ?? Property::factory()->create();
        $propertyUnit = $hasUnit ? (PropertyUnit::where('property_id', $property->id)->inRandomOrder()->first() ?? PropertyUnit::factory()->create(['property_id' => $property->id])) : null;

        $business = Business::inRandomOrder()->first() ?? Business::factory()->create();

        return [
            'business_id'       => $business->id,
            'property_id' => $property->id,
            'property_unit_id' => $propertyUnit ? $propertyUnit->id : null,
            'image_path'        => 'property_images/3038-view-' . $this->faker->numberBetween(1, 6) . '.jpg', // Example image path, adjust as needed
            'caption'           => $this->faker->optional()->sentence,
            'is_featured'       => $this->faker->boolean(20), // 20% chance featured
            'order'             => $this->faker->optional()->numberBetween(1, 10),
        ];
    }
}
