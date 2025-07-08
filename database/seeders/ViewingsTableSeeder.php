<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Viewing;
use App\Models\Lead;
use App\Models\Property;

class ViewingsTableSeeder extends Seeder
{
    public function run()
    {
        if (Lead::count() === 0 && Property::count() === 0 && Agent::count() === 0) {
            echo "Skipping ViewingsTableSeeder: Not enough leads, properties, or agents found.\n";
            return;
        }
        Viewing::factory(30)->create();
    }
}
