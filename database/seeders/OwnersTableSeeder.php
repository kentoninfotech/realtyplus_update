<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Owner;
use App\Models\User;

class OwnersTableSeeder extends Seeder
{
    public function run()
    {
        // Link each owner to a user with 'owner' type
        $ownerUser = User::where('email', 'owner@example.com')->first();
        if ($ownerUser) {
            Owner::firstOrCreate(
                ['user_id' => $ownerUser->id],
                [
                    'first_name' => 'Smith',
                    'last_name' => 'Family',
                    'company_name' => null,
                    'email' => 'owner@example.com',
                    'phone_number' => '09055667788',
                    'address' => '101 Owner Estate, City, State',
                    'bank_account_details' => '1234567890 (Dummy Bank)',
                ]
            );
        }

        Owner::factory(4)->create();
    }
}
