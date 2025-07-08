<?php

namespace Database\Factories;

use App\Models\PropertyType;
use Illuminate\Database\Eloquent\Factories\Factory;

class PropertyTypeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PropertyType::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->unique()->randomElement(['Apartment', 'House', 'Land', 'Commercial Space', 'Office', 'Warehouse', 'Duplex', 'Bungalow']),
            'description' => $this->faker->sentence(),
        ];
    }
    
}