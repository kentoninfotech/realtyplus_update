<?php

namespace Database\Factories;

use App\Models\Amenity;
use Illuminate\Database\Eloquent\Factories\Factory;

class AmenityFactory extends Factory
{
    protected $model = Amenity::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $amenities = [
            'Swimming Pool' => 'fa-swimming-pool',
            'Gym' => 'fa-dumbbell',
            'Parking' => 'fa-parking',
            '24/7 Security' => 'fa-shield-alt',
            'Elevator' => 'fa-elevator',
            'Balcony' => 'fa-building',
            'Garden' => 'fa-seedling',
            'Playground' => 'fa-child',
            'Pet Friendly' => 'fa-dog',
            'Air Conditioning' => 'fa-wind',
            'Furnished' => 'fa-couch',
            'Water Supply' => 'fa-tint',
            'Electricity' => 'fa-bolt',
            'Internet' => 'fa-wifi',
            'Generator' => 'fa-charging-station',
        ];
        $name = $this->faker->unique()->randomElement(array_keys($amenities));
        return [
            'business_id'  => null, // Set in seeder if needed
            'name'         => $name,
            'icon'         => $amenities[$name],
            'description'  => $this->faker->optional()->sentence,
        ];
    }
}
