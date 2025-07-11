<?php

namespace Database\Factories;

use App\Models\Agent;
use App\Models\User;
use App\Models\Business;
use Illuminate\Database\Eloquent\Factories\Factory;

class AgentFactory extends Factory
{
    protected $model = Agent::class;

    public function definition()
    {
        $business = Business::inRandomOrder()->first();
        if (!$business) {
            throw new \Exception('No businesses found. Please seed businesses before running this factory.');
        }
        return [
            'business_id' => $business->id,
            'user_id' => User::factory()->agent(),
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'email' => $this->faker->unique()->safeEmail(),
            'phone_number' => $this->faker->phoneNumber(),
            'license_number' => $this->faker->unique()->regexify('[A-Z]{2}[0-9]{5}'),
            'commission_rate' => $this->faker->randomFloat(2, 2, 10), // 2% to 10%
            'status' => $this->faker->randomElement(['active', 'inactive']),
        ];
    }
}
