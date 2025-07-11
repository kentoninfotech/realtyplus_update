<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Document;
use App\Models\User;
use App\Models\Client;
use App\Models\Property;
use App\Models\Lease;

class DocumentsTableSeeder extends Seeder
{
    public function run()
    {
        // Ensure there are some records for polymorphic relations
        if (User::count() === 0 || Property::count() === 0 || Lease::count() === 0 || Client::count() === 0) {
            echo "Skipping DocumentsTableSeeder: Not enough base records for polymorphic relations.\n";
            return;
        }
        Document::factory(60)->create();
    }
}
