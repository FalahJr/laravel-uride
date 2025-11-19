<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin user
        User::create([
            'username' => 'admin',
            'password' => Hash::make('AdminPass123!'),
            'email' => 'admin@example.com',
            'role' => 'admin',
            'nama_lengkap' => 'Administrator',
            'nim' => null,
            'nomor_telepon' => null,
            'jenis_kelamin' => null,
            'tanggal_lahir' => null,
            'is_verified' => true,
            'refresh_token' => null,
            'refresh_token_expires_at' => null,
            'last_login_at' => now(),
            'device_info' => null,
        ]);

        // Operator user
        User::create([
            'username' => 'operator',
            'password' => Hash::make('OperatorPass123!'),
            'email' => 'operator@example.com',
            'role' => 'operator',
            'nama_lengkap' => 'Operator User',
            'nim' => null,
            'nomor_telepon' => null,
            'jenis_kelamin' => null,
            'tanggal_lahir' => null,
            'is_verified' => true,
            'refresh_token' => null,
            'refresh_token_expires_at' => null,
            'last_login_at' => now(),
            'device_info' => null,
        ]);
    }
}
