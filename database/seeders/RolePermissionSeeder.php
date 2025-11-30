<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Clear cached permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            // Permissions by Model
            'view project', 'create project', 'edit project', 'delete project',
            'view task', 'create task', 'edit task', 'delete task',
            'view milestone_report', 'create milestone_report', 'edit milestone_report', 'delete milestone_report',
            'view payment', 'create payment', 'edit payment', 'delete payment',
            'view transaction', 'create transaction', 'edit transaction', 'delete transaction',
            'view accounthead', 'create accounthead', 'edit accounthead', 'delete accounthead',
            'view user', 'create user', 'edit user', 'delete user', 'view client', 'create client', 'edit client', 'delete client',
            'view material_checkout', 'create material_checkout', 'edit material_checkout', 'delete material_checkout',
            'view material_stock', 'create material_stock', 'edit material_stock', 'delete material_stock',
            'view material_supply', 'create material_supply', 'edit material_supply', 'delete material_supply',
            'view material', 'create material', 'edit material', 'delete material',
            'view owner', 'create owner', 'edit owner', 'delete owner',
            'view property', 'create property', 'edit property', 'delete property',
            'view tenant', 'create tenant', 'edit tenant', 'delete tenant',
            'view agent', 'create agent', 'edit agent', 'delete agent',
            'manage businesses', 'create maintenance_request', // Admin exception
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Roles and Their Permissions
        $superAdmin = Role::firstOrCreate(['name' => 'Super Admin']);
        $superAdmin->givePermissionTo(Permission::all());

        $admin = Role::firstOrCreate(['name' => 'System Admin']);
        $admin->givePermissionTo(Permission::all()->filter(fn($p) => $p->name !== 'manage businesses'));

        $manager = Role::firstOrCreate(['name' => 'Manager']);
        $manager->givePermissionTo([
            'view project', 'create project', 'edit project',
            'view task', 'create task', 'edit task', 'delete task',
            'view material_checkout', 'create material_checkout', 'edit material_checkout',
            'view material_stock', 'create material_stock', 'edit material_stock',
            'view material_supply', 'create material_supply', 'edit material_supply',
            'view material', 'create material', 'edit material',
            'view milestone_report', 'create milestone_report', 'edit milestone_report',
            'view client', 'create client', 'edit client', 'view user', 'create user', 'edit user',
        ]);

        $finance = Role::firstOrCreate(['name' => 'Finance']);
        $finance->givePermissionTo([
            'view payment', 'create payment', 'edit payment',
            'view transaction', 'create transaction', 'edit transaction',
            'view accounthead', 'create accounthead', 'edit accounthead', 'view project',
        ]);

        $staff = Role::firstOrCreate(['name' => 'Staff']);
        $staff->givePermissionTo([
            'view task', 'edit task', 'view project',
            'view milestone_report', 'edit milestone_report',// Add condition in controller for task with user_id
        ]);

        $client = Role::firstOrCreate(['name' => 'Client']);
        $client->givePermissionTo(['view project']); // Add condition in controller
    
    }
}
