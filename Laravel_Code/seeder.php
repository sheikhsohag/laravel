<!-- php artisan make:seeder UserSeeder
php artisan make:seeder ProductSeeder -->

<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User; // Don't forget to import your User model

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Create a single specific user (e.g., an admin)
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'email_verified_at' => now(),
            'password' => bcrypt('password'), // Always hash passwords!
            'remember_token' => \Illuminate\Support\Str::random(10),
        ]);

        // 2. Create multiple users using the factory
        // This will use the definition() method from UserFactory.php
        User::factory()->count(20)->create(); // Creates 20 random users

        // 3. Create users with specific states (if defined in your UserFactory)
        // User::factory()->count(5)->suspended()->create(); // Example: if you have a 'suspended' state
    }
}

// parent seeder


// $this->call([
//             UserSeeder::class,    // Create users first
//             ProductSeeder::class, // Then create products (which might depend on users)
//             // Add any other seeders you create:
//             // CategorySeeder::class,
//             // OrderSeeder::class,
//         ]);
