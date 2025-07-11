<?php

namespace Database\Factories;

use App\Models\Lease;
use App\Models\Property;
use App\Models\PropertyUnit;
use App\Models\Tenant;
use App\Models\Business;
use Illuminate\Database\Eloquent\Factories\Factory;

class LeaseFactory extends Factory
{
    protected $model = Lease::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $tenant = Tenant::inRandomOrder()->first() ?? Tenant::factory()->create();
        $propertyUnit = PropertyUnit::inRandomOrder()->first() ?? PropertyUnit::factory()->create();
        $property = $propertyUnit->property; // Ensure consistency

        $startDate = $this->faker->dateTimeBetween('-2 years', 'now');
        $endDate = (clone $startDate)->modify('+1 year');
        $renewalDate = (clone $endDate)->modify('+1 month');

        $business = Business::inRandomOrder()->first() ?? Business::factory()->create();

        return [
            'business_id'        => $business->id,
            'property_id'        => $property->id,
            'property_unit_id'   => $propertyUnit->id,
            'tenant_id'          => $tenant->id,
            'start_date'         => $startDate->format('Y-m-d'),
            'end_date'           => $endDate->format('Y-m-d'),
            'rent_amount'        => $propertyUnit->rent_price ?? $this->faker->randomFloat(2, 50000, 500000),
            'deposit_amount'     => $this->faker->optional()->randomFloat(2, 50000, 500000),
            'payment_frequency'  => $this->faker->randomElement(['monthly', 'quarterly', 'annually']),
            'renewal_date'       => $renewalDate->format('Y-m-d'),
            'status'             => $this->faker->randomElement(['pending', 'active', 'terminated', 'expired']),
            'terms'              => $this->faker->optional()->paragraph,
        ];
    }
}
