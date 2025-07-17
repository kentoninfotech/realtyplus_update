<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PropertyType;
use Illuminate\Support\Str;

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
            [
                'name' => 'Single Family Home',
                'description' => 'A detached house, typically for one family.',
                'is_residential' => true,
                'can_have_multiple_units' => false,
            ],
            [
                'name' => 'Duplex',
                'description' => 'A single building containing two separate homes.',
                'is_residential' => true,
                'can_have_multiple_units' => true,
            ],
            [
                'name' => 'Triplex',
                'description' => 'A single building containing three separate homes.',
                'is_residential' => true,
                'can_have_multiple_units' => true,
            ],
            [
                'name' => 'Fourplex',
                'description' => 'A single building containing four separate homes.',
                'is_residential' => true,
                'can_have_multiple_units' => true,
            ],
            [
                'name' => 'Townhouse',
                'description' => 'A multi-story home that shares one or two walls with adjacent properties.',
                'is_residential' => true,
                'can_have_multiple_units' => false,
            ],
            [
                'name' => 'Condo',
                'description' => 'An individually owned unit within a larger building or complex.',
                'is_residential' => true,
                'can_have_multiple_units' => false,
            ],
            [
                'name' => 'Apartment',
                'description' => 'A rented unit within a larger residential building.',
                'is_residential' => true,
                'can_have_multiple_units' => false,
            ],
            [
                'name' => 'Apartment Complex',
                'description' => 'A large property consisting of multiple apartment buildings.',
                'is_residential' => true,
                'can_have_multiple_units' => true,
            ],
            [
                'name' => 'Multi-Family (Other)',
                'description' => 'Other residential properties with multiple units (e.g., more than fourplex).',
                'is_residential' => true,
                'can_have_multiple_units' => true,
            ],
            [
                'name' => 'Land Parcel',
                'description' => 'An undeveloped piece of land.',
                'is_residential' => false,
                'can_have_multiple_units' => true,
            ],
            [
                'name' => 'Commercial Building',
                'description' => 'A building primarily used for business activities.',
                'is_residential' => false,
                'can_have_multiple_units' => true,
            ],
            [
                'name' => 'Office Space',
                'description' => 'Dedicated space for office work, often within a commercial building.',
                'is_residential' => false,
                'can_have_multiple_units' => false,
            ],
            [
                'name' => 'Retail Space',
                'description' => 'Space designed for retail businesses.',
                'is_residential' => false,
                'can_have_multiple_units' => false,
            ],
            [
                'name' => 'Industrial Property',
                'description' => 'Property used for manufacturing, storage, distribution, etc.',
                'is_residential' => false,
                'can_have_multiple_units' => false,
            ],
            [
                'name' => 'Warehouse',
                'description' => 'A building for storing goods.',
                'is_residential' => false,
                'can_have_multiple_units' => false,
            ],
            [
                'name' => 'Mixed-Use',
                'description' => 'Property combining residential, commercial, or industrial uses.',
                'is_residential' => true, // Can be residential + commercial
                'can_have_multiple_units' => true,
            ],
            [
                'name' => 'Farm/Ranch',
                'description' => 'Agricultural land with or without residential structures.',
                'is_residential' => true, // Can have residential component
                'can_have_multiple_units' => false, // Typically managed as one unit (the whole farm)
            ],
            [
                'name' => 'Vacation Rental',
                'description' => 'Property rented for short-term stays.',
                'is_residential' => true,
                'can_have_multiple_units' => false,
            ],
            [
                'name' => 'Mobile Home',
                'description' => 'A manufactured home designed to be transportable.',
                'is_residential' => true,
                'can_have_multiple_units' => false,
            ],
            [
                'name' => 'Timeshare',
                'description' => 'A property with shared ownership or rights of use for a specific period.',
                'is_residential' => true,
                'can_have_multiple_units' => false,
            ],
        ];

        foreach ($propertyTypes as $type) {
            // Generate slug from the name
            $slug = Str::slug($type['name']);
            PropertyType::firstOrCreate([
                'name'                     => $type['name'],
                'description'              => $type['description'],
                'slug'                     => $slug,
                'is_residential'           => $type['is_residential'],
                'can_have_multiple_units'  => $type['can_have_multiple_units'],
            ]);
        }

        // PropertyType::factory(5)->create();
    }
}
