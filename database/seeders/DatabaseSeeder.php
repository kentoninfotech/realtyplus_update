<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            RolePermissionSeeder::class,
            SuperAdminSeeder::class,
            PlansSeeder::class,
            LandingContentSeeder::class,
            BusinessesTableSeeder::class,
            UsersTableSeeder::class,
            PropertyTypesTableSeeder::class,
            PersonnelTableSeeder::class,
            ClientsTableSeeder::class,
            TenantsTableSeeder::class,
            AgentsTableSeeder::class,
            OwnersTableSeeder::class,
            PropertiesTableSeeder::class,
            AmenitiesTableSeeder::class,
            PropertyAmenityTableSeeder::class,
            LeasesTableSeeder::class,
            LeadsTableSeeder::class,
            PropertyTransactionsTableSeeder::class,
            ViewingsTableSeeder::class,
            DocumentsTableSeeder::class,
            MaintenanceRequestsTableSeeder::class,
            PropertyTasksTableSeeder::class,
            UpdatePropertyFakeRecordSeeder::class,
        ]);
    }
}
