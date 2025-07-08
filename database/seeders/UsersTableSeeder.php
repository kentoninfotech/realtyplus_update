<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create a super admin
        User::factory()->admin()->create([
            'name' => 'Super Admin',
            'email' => 'admin@realtyplus.com',
            'password' => Hash::make('password'),
        ]);

        // Create a few specific role users for testing
        User::factory()->agent()->create([
            'name' => 'Agent John',
            'email' => 'agent@example.com',
            'password' => Hash::make('password'),
        ]);

        User::factory()->tenant()->create([
            'name' => 'Tenant Jane',
            'email' => 'tenant@example.com',
            'password' => Hash::make('password'),
        ]);

        User::factory()->owner()->create([
            'name' => 'Owner Smith',
            'email' => 'owner@example.com',
            'password' => Hash::make('password'),
        ]);

        User::factory()->personnel()->create([
            'name' => 'Manager Manuel',
            'email' => 'manager@example.com',
            'password' => Hash::make('password'),
        ]);

        // Create demo users for each type
        User::factory()->count(1)->admin()->create();
        User::factory()->count(4)->agent()->create();
        User::factory()->count(4)->owner()->create();
        User::factory()->count(4)->tenant()->create();
        User::factory()->count(4)->client()->create();
        User::factory()->count(9)->personnel()->create();
    }
}
