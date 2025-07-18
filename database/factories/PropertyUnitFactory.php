<?php

namespace Database\Factories;

use App\Models\PropertyUnit;
use App\Models\Property;
use App\Models\Business;
use Illuminate\Database\Eloquent\Factories\Factory;

class PropertyUnitFactory extends Factory
{
    protected $model = PropertyUnit::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $property = Property::inRandomOrder()->first() ?? Property::factory()->create();
        $listingType = $property->listing_type;
        $salePrice = ($listingType == 'sale' || $listingType == 'both') ? $this->faker->randomFloat(2, 500000, 20000000) : null;
        $rentPrice = ($listingType == 'rent' || $listingType == 'both') ? $this->faker->randomFloat(2, 30000, 300000) : null;

        $business = Business::inRandomOrder()->first() ?? Business::factory()->create();

        return [
            'business_id'      => $business->id,
            'property_id'      => $property->id,
            'unit_number'      => $this->faker->randomElement(['Unit', 'Plot', 'Block']) . $this->faker->unique()->bothify('###'),
            'unit_type'        => $this->faker->randomElement(['Studio', '1 Bed', '2 Bed', 'Retail', 'Office']),
            'description'      => $this->faker->optional()->sentence,
            'status'           => $this->faker->randomElement(['available', 'vacant', 'sold', 'leased', 'under_maintenance', 'unavailable']),
            'square_footage'   => $this->faker->optional()->randomFloat(2, 200, 5000),
            'area_sqm'         => $this->faker->optional()->randomFloat(2, 200, 5000),
            'zoning_type'      => $this->faker->randomElement(['Residential', 'Commercial', 'Mixed-Use']),
            'floor_number'     => $this->faker->optional()->numberBetween(1, 20),
            'bedrooms'         => $this->faker->optional()->numberBetween(0, 5),
            'bathrooms'        => $this->faker->optional()->randomFloat(1, 1, 4),
            'sale_price'       => $salePrice,
            'rent_price'       => $rentPrice,
            'deposit_amount'   => $this->faker->optional()->randomFloat(2, 50000, 1000000),
            'available_from'   => $this->faker->optional()->date(),
        ];
    }
}
