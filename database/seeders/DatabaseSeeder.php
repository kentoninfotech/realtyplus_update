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
            BusinessesTableSeeder::class,
            RolePermissionSeeder::class, // Roles and permissions must be set up first 
            UsersTableSeeder::class, // Users must exist first after business
            PropertyTypesTableSeeder::class,
            PersonnelTableSeeder::class,
            ClientsTableSeeder::class,
            TenantsTableSeeder::class,
            AgentsTableSeeder::class,
            OwnersTableSeeder::class,
            PropertiesTableSeeder::class, // Properties depend on PropertyTypes, Owners, Agents
            AmenitiesTableSeeder::class,
            PropertyAmenityTableSeeder::class, // Pivot table
            LeasesTableSeeder::class, // Leases depend on Properties, PropertyUnits, Tenants
            LeadsTableSeeder::class, // Leads depend on Agents
            PropertyTransactionsTableSeeder::class, // Transactions can depend on Leases, Properties
            ViewingsTableSeeder::class, // Viewings depend on Leads, Properties, PropertyUnits, Agents
            DocumentsTableSeeder::class, // Documents depend on various models including Users
            // PaymentsTableSeeder::class, // Payments depend on Leases, Transactions, Payers (Tenant, Client, Owner)
            MaintenanceRequestsTableSeeder::class, // Depends on Properties, PropertyUnits, Users, Personnel
            PropertyTasksTableSeeder::class, // Depends on various models including Users
            UpdatePropertyFakeRecordSeeder::class, // Updates property total_units and has_units records
        ]);
        // \App\Models\User::factory(10)->create();
    }
}
