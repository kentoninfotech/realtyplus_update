<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PropertyTransaction;
use App\Models\Property;
use App\Models\Lease;


class PropertyTransactionsTableSeeder extends Seeder
{
    public function run()
    {
        if (Lease::count() === 0 && Property::count() === 0) {
            echo "Skipping TransactionsTableSeeder: No leases or properties found. Run LeasesTableSeeder and PropertiesTableSeeder first.\n";
            return;
        }
        PropertyTransaction::factory(50)->create();
    }
}
