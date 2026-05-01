<?php

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PlansSeeder extends Seeder
{
    public function run()
    {
        $plans = [
            [
                'name' => 'Starter',
                'description' => 'For small landlords just getting started.',
                'price' => 0,
                'currency' => 'NGN',
                'billing_cycle' => 'monthly',
                'trial_days' => 14,
                'max_users' => 2,
                'max_properties' => 5,
                'max_personnel' => 2,
                'features' => ['Up to 5 properties', '2 team members', 'Tenant & lease tracking', 'Email support'],
                'is_featured' => false,
                'sort_order' => 1,
            ],
            [
                'name' => 'Professional',
                'description' => 'For growing real-estate businesses.',
                'price' => 25000,
                'currency' => 'NGN',
                'billing_cycle' => 'monthly',
                'trial_days' => 14,
                'max_users' => 10,
                'max_properties' => 50,
                'max_personnel' => 15,
                'features' => ['Up to 50 properties', '15 team members', 'Maintenance workflow', 'Reports & dashboards', 'Priority email support'],
                'is_featured' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Enterprise',
                'description' => 'For agencies and multi-branch operators.',
                'price' => 75000,
                'currency' => 'NGN',
                'billing_cycle' => 'monthly',
                'trial_days' => 30,
                'max_users' => null,
                'max_properties' => null,
                'max_personnel' => null,
                'features' => ['Unlimited properties', 'Unlimited team', 'Custom branding', 'API access', 'Dedicated account manager'],
                'is_featured' => false,
                'sort_order' => 3,
            ],
        ];

        foreach ($plans as $p) {
            $p['slug'] = Str::slug($p['name']);
            Plan::updateOrCreate(['slug' => $p['slug']], $p);
        }
    }
}
