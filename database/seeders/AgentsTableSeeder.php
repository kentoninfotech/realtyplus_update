<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Agent;
use App\Models\User;

class AgentsTableSeeder extends Seeder
{
    public function run()
    {
        // Link each agent to a user with 'agent' type
        $agentUser = User::where('email', 'agent@example.com')->first();
        if ($agentUser) {
            Agent::firstOrCreate(
                ['user_id' => $agentUser->id],
                [
                    'first_name' => 'John',
                    'last_name' => 'Agent',
                    'email' => 'agent@example.com',
                    'phone' => '08011223344',
                    'license_number' => 'AB12345',
                    'commission_rate' => 5.00,
                    'status' => 'active',
                ]
            );
        }

        Agent::factory(4)->create();
    }
}
