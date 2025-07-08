<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PropertyTask;
use App\Models\Lead;
use App\Models\Property;
use App\Models\Lease;
use App\Models\MaintenanceRequest;

class PropertyTasksTableSeeder extends Seeder
{
    public function run()
    {
        if (Lead::count() === 0 || Property::count() === 0 || Lease::count() === 0 || MaintenanceRequest::count() === 0) {
            echo "Skipping TasksTableSeeder: Not enough base records for polymorphic relations.\n";
            return;
        }
        PropertyTask::factory(50)->create();
    }
}
