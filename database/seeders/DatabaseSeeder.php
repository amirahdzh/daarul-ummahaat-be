<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create roles
        $adminRole = Role::create(['name' => 'admin']);
        $userRole = Role::create(['name' => 'user']);
        $editorRole = Role::create(['name' => 'editor']);

        // Create admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'phone' => '+1234567890',
            'role_id' => $adminRole->id,
        ]);

        // Create regular user
        User::create([
            'name' => 'Regular User',
            'email' => 'user@example.com',
            'password' => Hash::make('password'),
            'phone' => '+1234567891',
            'role_id' => $userRole->id,
        ]);

        // Create editor user
        User::create([
            'name' => 'Editor User',
            'email' => 'editor@example.com',
            'password' => Hash::make('password'),
            'phone' => '+1234567892',
            'role_id' => $editorRole->id,
        ]);

        // Call other seeders
        $this->call([
            ImageSeeder::class,
            ProgramSeeder::class,
            DonationPackageSeeder::class,
            FundraiserSeeder::class,
            ActivitySeeder::class,
            DonationSeeder::class,
            // AdminLogSeeder::class,
        ]);
    }
}
