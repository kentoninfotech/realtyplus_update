<?php

namespace Database\Factories;

use App\Models\Viewing;
use App\Models\Lead;
use App\Models\Property;
use App\Models\PropertyUnit;
use App\Models\Agent;
use Illuminate\Database\Eloquent\Factories\Factory;

class ViewingFactory extends Factory
{
    protected $model = Viewing::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $lead = Lead::inRandomOrder()->first() ?? Lead::factory()->create();
        $property = Property::inRandomOrder()->first() ?? Property::factory()->create();
        $propertyUnit = PropertyUnit::where('property_id', $property->id)->inRandomOrder()->first() ?? PropertyUnit::factory()->create(['property_id' => $property->id]);
        $agent = Agent::inRandomOrder()->first() ?? Agent::factory()->create();

        return [
            'business_id'      => null, // Set in seeder if needed
            'lead_id'          => $lead ? $lead->id : null,
            'property_id'      => $property->id,
            'property_unit_id' => $propertyUnit ? $propertyUnit->id : null,
            'agent_id'         => $agent->id,
            'client_name'      => $lead ? $lead->first_name . ' ' . $lead->last_name : $this->faker->name(),
            'client_email'     => $lead ? $lead->email : $this->faker->safeEmail(),
            'client_phone'     => $lead ? $lead->phone : $this->faker->phoneNumber(),
            'scheduled_at'     => $this->faker->dateTimeBetween('now', '+1 month'),
            'status'           => $this->faker->randomElement(['scheduled', 'completed', 'cancelled', 'rescheduled']),
            'notes'            => $this->faker->optional()->paragraph,
        ];
    }
}
