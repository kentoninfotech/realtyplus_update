<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class SuperAdminSeeder extends Seeder
{
    public function run()
    {
        $email    = env('SUPER_ADMIN_EMAIL', 'superadmin@realtyplus.com.ng');
        $password = env('SUPER_ADMIN_PASSWORD', 'Password123!');
        $name     = env('SUPER_ADMIN_NAME', 'Super Admin');

        $user = User::updateOrCreate(
            ['email' => $email],
            [
                'name'              => $name,
                'password'          => Hash::make($password),
                'is_super_admin'    => true,
                'status'            => 'active',
                'email_verified_at' => now(),
                'user_type'         => 'super_admin',
            ]
        );

        try {
            $role = Role::firstOrCreate(['name' => 'Super Admin']);
            $user->syncRoles([$role]);
        } catch (\Throwable $e) {
            // Spatie permission tables not yet seeded; ignore.
        }

        $this->command->info("Super admin: {$email} / {$password}");
    }
}
