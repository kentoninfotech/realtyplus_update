<?php

namespace Database\Factories;

use App\Models\Personnel;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Business;

class PersonnelFactory extends Factory
{
    protected $model = Personnel::class;

    public function definition()
    {
        $business = Business::inRandomOrder()->first();
        if (!$business) {
            throw new \Exception('No businesses found. Please seed businesses before running this factory.');
        }
        return [
            'business_id'         => $business->id,
            'user_id'             => User::factory()->personnel(),
            'last_name'            => $this->faker->lastName,
            'first_name'           => $this->faker->firstName,
            'other_name'           => $this->faker->optional()->firstName,
            'designation'         => $this->faker->jobTitle,
            'phone_number'        => $this->faker->phoneNumber,
            'email'               => $this->faker->unique()->safeEmail,
            'address'             => $this->faker->address,
            'department'          => $this->faker->randomElement(['Sales', 'Admin', 'Support', 'Management']),
            'salary'              => $this->faker->numberBetween(30000, 120000),
            'highest_certificate' => $this->faker->randomElement(['BSc', 'MSc', 'PhD', 'HND', 'OND', 'SSCE']),
            'staff_id'            => null, // Auto-generated in model
            'cv'                  => $this->faker->optional()->url,
            'dob'                 => $this->faker->date('Y-m-d'),
            'state_of_origin'     => $this->faker->state,
            'nationality'         => $this->faker->country,
            'marital_status'      => $this->faker->randomElement(['single', 'married', 'divorced', 'widowed']),
            'employment_date'     => $this->faker->date('Y-m-d'),
            'picture'             => $this->faker->optional()->imageUrl(200, 200, 'people'),
        ];
    }
}
