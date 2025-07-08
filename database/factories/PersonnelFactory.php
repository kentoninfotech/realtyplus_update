<?php

namespace Database\Factories;

use App\Models\Personnel;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

class PersonnelFactory extends Factory
{
    protected $model = Personnel::class;

    public function definition()
    {
        return [
            'business_id'         => null, // Set in seeder if needed
            'user_id'             => User::factory()->personnel(),
            'lastname'            => $this->faker->lastName,
            'firstname'           => $this->faker->firstName,
            'othername'           => $this->faker->optional()->firstName,
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
