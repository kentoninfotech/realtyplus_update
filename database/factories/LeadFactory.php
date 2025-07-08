<?php

namespace Database\Factories;

use App\Models\Lead;
use App\Models\Agent;
use Illuminate\Database\Eloquent\Factories\Factory;

class LeadFactory extends Factory
{
    protected $model = Lead::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'business_id'              => null, // Set in seeder if needed
            'agent_id'                 => Agent::inRandomOrder()->first() ?? Agent::factory(),
            'first_name'               => $this->faker->firstName,
            'last_name'                => $this->faker->lastName,
            'email'                    => $this->faker->optional()->safeEmail,
            'phone'                    => $this->faker->optional()->phoneNumber,
            'preferred_contact_method' => $this->faker->optional()->randomElement(['email', 'phone', 'both']),
            'source'                   => $this->faker->optional()->randomElement(['Website', 'Referral', 'Walk-in', 'Social Media']),
            'status'                   => $this->faker->randomElement(['new', 'contacted', 'qualified', 'viewing_scheduled', 'closed_won', 'closed_lost']),
            'notes'                    => $this->faker->optional()->paragraph,
            'budget'                   => $this->faker->optional()->randomFloat(2, 1000000, 100000000),
            'property_type_interest'   => $this->faker->optional()->randomElement(['Apartment', 'House', 'Land', 'Commercial Space']),
            'bedrooms_interest'        => $this->faker->optional()->numberBetween(1, 6),
        ];
    }
}
