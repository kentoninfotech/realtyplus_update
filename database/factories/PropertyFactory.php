<?php

namespace Database\Factories;

use App\Models\PropertyType;
use App\Models\User;
use App\Models\Business;
use Illuminate\Database\Eloquent\Factories\Factory;

class PropertyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $listingType = $this->faker->randomElement(['sale', 'rent', 'both']);
        $salePrice = ($listingType == 'sale' || $listingType == 'both') ? $this->faker->randomFloat(2, 1000000, 50000000) : null;
        $rentPrice = ($listingType == 'rent' || $listingType == 'both') ? $this->faker->randomFloat(2, 50000, 500000) : null;

        $business = Business::inRandomOrder()->first() ?? Business::factory()->create();

        // Use existing PropertyType, Agent and Owner or create if none exists
        $propertyType = PropertyType::inRandomOrder()->first();// ?? PropertyType::factory()->create();
        $agent = User::where('user_type', 'agent')->inRandomOrder()->first();// ?? User::factory()->agent()->create();
        $owner = User::where('user_type', 'owner')->inRandomOrder()->first();// ?? User::factory()->owner()->create();

        return [
            'business_id'        => $business->id,
            'property_type_id'   => $propertyType->id,
            'agent_id'           => $agent->id,
            'owner_id'           => $owner->id,
            'name'               => $this->faker->streetName . ' ' . $this->faker->buildingNumber,
            'zoning_type'        => $this->faker->randomElement(['Residential', 'Commercial', 'Mixed-Use']),
            'cadastral_id'       => 'RP'. $this->faker->uuid(),
            'address'            => $this->faker->address,
            'state'              => $this->faker->state,
            'country'            => 'Nigeria',
            'description'        => $this->faker->optional()->paragraph,
            'has_units'          => $propertyType->can_have_multiple_units ?? false,
            'status'             => $this->faker->randomElement(['available', 'vacant', 'sold', 'leased', 'under_maintenance', 'unavailable']),
            'latitude'           => $this->faker->optional()->latitude(-90, 90),
            'longitude'          => $this->faker->optional()->longitude(-180, 180),
            'area_sqft'          => $this->faker->optional()->numberBetween(500, 10000),
            'lot_size_sqft'      => $this->faker->optional()->numberBetween(1000, 20000),
            'year_built'         => $this->faker->optional()->year,
            'purchase_price'     => $this->faker->optional()->randomFloat(2, 1000000, 100000000),
            'sale_price'         => $salePrice,
            'rent_price'         => $rentPrice,
            'date_acquired'      => $this->faker->optional()->date(),
            'listing_type'       => $listingType,
            'listed_at'          => $this->faker->optional()->date(),
        ];
    }
}
