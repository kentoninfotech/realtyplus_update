<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Amenity;

class AmenitiesTableSeeder extends Seeder
{
    public function run()
    {
        $amenities = [
            ['name' => 'Swimming Pool', 'icon' => 'fa-water-ladder'],
            ['name' => 'Gym', 'icon' => 'fa-dumbbell'],
            ['name' => 'Parking', 'icon' => 'fa-square-parking'],
            ['name' => '24/7 Security', 'icon' => 'fa-shield-halved'],
            ['name' => 'Elevator', 'icon' => 'fa-elevator'],
            ['name' => 'Balcony', 'icon' => 'fa-building'],
            ['name' => 'Garden', 'icon' => 'fa-tree'],
            ['name' => 'Playground', 'icon' => 'fa-child-reaching'],
            ['name' => 'Pet Friendly', 'icon' => 'fa-paw'],
            ['name' => 'Air Conditioning', 'icon' => 'fa-fan'],
            ['name' => 'Furnished', 'icon' => 'fa-couch'],
            ['name' => 'Water Supply', 'icon' => 'fa-faucet-drip'],
            ['name' => 'Electricity', 'icon' => 'fa-bolt'],
            ['name' => 'Internet', 'icon' => 'fa-wifi'],
            ['name' => 'Generator', 'icon' => 'fa-charging-station'],
        ];

        foreach ($amenities as $amenity) {
            Amenity::firstOrCreate(['name' => $amenity['name']], $amenity);
        }

        Amenity::factory(5)->create();
    }
}
