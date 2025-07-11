<?php

namespace Database\Factories;

use App\Models\Business;
use App\Models\Client;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClientFactory extends Factory
{
    protected $model = Client::class;

    public function definition()
    {
        $business = Business::inRandomOrder()->first();
        if (!$business) {
            throw new \Exception('No businesses found. Please seed businesses before running this factory.');
        }
        return [
            'business_id' => $business->id,
            'user_id' => User::factory()->client(),
            'name' => $this->faker->name(),
            'company_name' => $this->faker->boolean(50) ? $this->faker->company() : null,
            'email' => $this->faker->unique()->safeEmail(),
            'phone_number' => $this->faker->phoneNumber(),
            'address' => $this->faker->address(),
            'about' => $this->faker->paragraph(),
        ];
    }
}
