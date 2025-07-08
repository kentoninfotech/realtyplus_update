<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Lead;
use App\Models\Agent;

class LeadsTableSeeder extends Seeder
{
    public function run()
    {
        if (Agent::count() === 0) {
            echo "Skipping LeadsTableSeeder: No agents found. Run AgentsTableSeeder first.\n";
            return;
        }
        Lead::factory(40)->create();
    }
}
