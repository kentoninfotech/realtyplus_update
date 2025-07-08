<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Lease;
use App\Models\PropertyUnit;
use App\Models\Tenant;

class LeasesTableSeeder extends Seeder
{
    public function run()
    {
        // Ensure there are enough property units and tenants
        if (PropertyUnit::count() === 0) {
            echo "Skipping LeasesTableSeeder: No property units found. Run PropertiesTableSeeder first.\n";
            return;
        }
        if (Tenant::count() === 0) {
            echo "Skipping LeasesTableSeeder: No tenants found. Run TenantsTableSeeder first.\n";
            return;
        }

        Lease::factory(30)->create();
    }
}
