<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MaintenanceRequest;
use App\Models\Property;
use App\Models\User;
use App\Models\Personnel;

class MaintenanceRequestsTableSeeder extends Seeder
{
    public function run()
    {
        if (Property::count() === 0 || User::count() === 0 || Personnel::count() === 0) {
            echo "Skipping MaintenanceRequestsTableSeeder: Not enough properties, users, or personnel found.\n";
            return;
        }
        MaintenanceRequest::factory(30)->create();
    }
}
