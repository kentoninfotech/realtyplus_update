<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Client;
use App\Models\User;

class ClientsTableSeeder extends Seeder
{
    public function run()
    {
        // Link each client to a user with 'client' type
        $clientUser = User::where('email', 'client@example.com')->first();
        if ($clientUser) {
            Client::firstOrCreate(
                ['user_id' => $clientUser->id],
                [
                    'name' => 'Client Sarah',
                    'company_name' => null,
                    'email' => 'client@example.com',
                    'phone_number' => '09011223344',
                    'address' => '456 Client Ave, City, State',
                    'about' => 'Looking for land for personal development.',
                ]
            );
        }

        Client::factory(4)->create();
    }
}
