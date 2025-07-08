<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tenant;
use App\Models\User;

class TenantsTableSeeder extends Seeder
{
    public function run()
    {
        // Link each tenant to a user with 'tenant' type
        $tenantUser = User::where('email', 'tenant@example.com')->first();
        if ($tenantUser) {
            Tenant::firstOrCreate(
                ['user_id' => $tenantUser->id],
                [
                    'first_name' => 'Jane',
                    'last_name' => 'Doe',
                    'email' => 'tenant@example.com',
                    'phone_number' => '07012345678',
                    'address' => '789 Tenant Rd, City, State',
                    'emergency_contact_name' => 'John Doe',
                    'emergency_contact_phone' => '08098765432',
                ]
            );
        }

        Tenant::factory(4)->create();
    }
}
