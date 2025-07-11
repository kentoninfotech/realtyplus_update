<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Business;

class BusinessesTableSeeder extends Seeder
{
    public function run()
    {
        $user = User::first();
        if (!$user) {
            echo "No users found. Please seed users first.\n";
            return;
        }

        $businesses = [
            [
                'user_id' => 1, // Assuming the first user is the owner
                'business_name' => 'RealtyPlus HQ',
                'motto' => 'Your Home, Our Priority',
                'logo' => 'realtyplus-logo.png',
                'address' => '123 Main Street, City, Country',
                'background' => 'office.jpg',
                'primary_color' => '#0000FF',
                'secondary_color' => '#5e9a52',
                'mode' => 'production',
                'deployment_type' => 'cloud',
            ],
            [
                'business_name' => 'RealtyPlus Branch',
                'motto' => 'Find Your Place',
                'logo' => 'branch-logo.png',
                'address' => '456 Side Avenue, City, Country',
                'background' => 'branch.jpg',
                'primary_color' => '#FF5733',
                'secondary_color' => '#33C1FF',
                'mode' => 'test',
                'deployment_type' => 'on-premise',
            ],
        ];

        foreach ($businesses as $data) {
            Business::firstOrCreate(
                ['business_name' => $data['business_name']],
                array_merge($data, ['user_id' => $user->id])
            );
        }
    }
}
