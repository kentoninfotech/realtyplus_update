<?php

namespace Database\Factories;

use App\Models\Business;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TenantFactory extends Factory
{
    protected $model = Tenant::class;

    public function definition()
    {
        $business = Business::inRandomOrder()->first();
        if (!$business) {
            throw new \Exception('No businesses found. Please seed businesses before running this factory.');
        }
        return [
            'business_id' => $business->id,
            'user_id' => User::factory()->tenant(),
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'email' => $this->faker->unique()->safeEmail(),
            'phone_number' => $this->faker->phoneNumber(),
            'address' => $this->faker->address(),
            'emergency_contact_name' => $this->faker->name(),
            'emergency_contact_phone' => $this->faker->phoneNumber(),
        ];
    }
}
