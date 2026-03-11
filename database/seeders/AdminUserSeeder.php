<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions to avoid cache conflicts during seeding
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. Create or fetch the Admin role
        $adminRole = Role::firstOrCreate(['name' => 'admin']);

        $adminUser = User::updateOrCreate(
            ['email' => 'admin@admin.com'], 
            [
                'name' => 'Kevin Thompson',
                'password' => Hash::make('pass@admin'),
                'email_verified_at' => now(),
            ]
        );

        // 3. Assign the admin role to the user
        // Using syncRoles ensures the user exactly has this role and no others
        $adminUser->syncRoles([$adminRole]);

        $this->command->info('Admin user seeded successfully with the Admin role!');
    }
}