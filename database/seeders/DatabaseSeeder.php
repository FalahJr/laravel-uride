<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\UserRoleSeeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed admin and operator users
        $this->call(UserRoleSeeder::class);

        // Seed sample drivers (users + user_driver)
        $this->call(DriverSeeder::class);
    }
}
