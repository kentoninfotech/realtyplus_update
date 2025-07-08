<?php

namespace Database\Factories;

use App\Models\PropertyImage;
use App\Models\Property;
use App\Models\PropertyUnit;
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

        return [
            'business_id'       => null, // Set in seeder if needed
            'property_id' => $property->id,
            'property_unit_id' => $propertyUnit ? $propertyUnit->id : null,
            'image_path'        => 'https://placehold.co/800x600/E0E0E0/333333?text=' . urlencode($this->faker->word()),
            'caption'           => $this->faker->optional()->sentence,
            'is_featured'       => $this->faker->boolean(20), // 20% chance featured
            'order'             => $this->faker->optional()->numberBetween(1, 10),
        ];
    }
}
