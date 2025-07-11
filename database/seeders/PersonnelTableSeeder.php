<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Personnel;
use App\Models\User;

class PersonnelTableSeeder extends Seeder
{
    public function run()
    {
        // Link each personnel to a user with 'staff' type
        $managerUser = User::where('email', 'manager@example.com')->first();
        if ($managerUser) {
            Personnel::firstOrCreate(
                ['user_id' => $managerUser->id],
                [
                    'first_name' => 'Manuel',
                    'last_name' => 'Lee',
                    'email' => 'manager@example.com',
                    'phone_number' => '08012345678',
                    'address' => '123 Admin St, City, State',
                ]
            );
        }

        Personnel::factory(9)->create();
    }
}
