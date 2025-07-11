<?php

namespace Database\Factories;

use App\Models\Business;
use Illuminate\Database\Eloquent\Factories\Factory;

class BusinessFactory extends Factory
{
    protected $model = Business::class;

    public function definition()
    {
        return [
            'user_id' => null, // Set to null for now, can be assigned later
            'business_name' => 'RealtyPlus HQ',
            'motto' => 'Your Home, Our Priority',
            'logo' => 'realtyplus-logo.png',
            'address' => '123 Main Street, City, Country',
            'background' => 'office.jpg',
            'primary_color' => '#0000FF',
            'secondary_color' => '#5e9a52',
            'mode' => 'Test',
            'deployment_type' => 'Local',

            // Uncomment the following lines if you want to use faker for generating random data
            // 'user_id'        => null,
            // 'business_name'  => $this->faker->company,
            // 'motto'          => $this->faker->catchPhrase,
            // 'logo'           => $this->faker->optional()->imageUrl(200, 200, 'business'),
            // 'address'        => $this->faker->address,
            // 'background'     => $this->faker->optional()->word,
            // 'primary_color'  => $this->faker->safeHexColor,
            // 'secondary_color'=> $this->faker->safeHexColor,
            // 'mode'           => $this->faker->randomElement(['production', 'test']),
            // 'deployment_type'=> $this->faker->randomElement(['cloud', 'on-premise']),
        ];
    }
}