<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PropertyType;

class PropertyTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $propertyTypes = [
            ['name' => 'Apartment', 'description' => 'A self-contained housing unit that occupies only part of a building.'],
            ['name' => 'House', 'description' => 'A building that serves as living quarters for one or a few families.'],
            ['name' => 'Land', 'description' => 'Undeveloped real estate, often used for future construction.'],
            ['name' => 'Commercial Space', 'description' => 'Property used for business activities, such as offices, retail stores, or warehouses.'],
            ['name' => 'Duplex', 'description' => 'A house divided into two apartments, with a separate entrance for each.'],
            ['name' => 'Bungalow', 'description' => 'A single-story house.'],
        ];

        foreach ($propertyTypes as $type) {
            PropertyType::firstOrCreate(['name' => $type['name']], $type);
        }

        PropertyType::factory(5)->create();
    }
}
