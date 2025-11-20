<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class CustomerSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customers = [
            ['username' => 'customer1', 'email' => 'customer1@example.com', 'nama_lengkap' => 'Rina Putri'],
            ['username' => 'customer2', 'email' => 'customer2@example.com', 'nama_lengkap' => 'Joko Susilo'],
            ['username' => 'customer3', 'email' => 'customer3@example.com', 'nama_lengkap' => 'Siti Aminah'],
            ['username' => 'customer4', 'email' => 'customer4@example.com', 'nama_lengkap' => 'Agus Santoso'],
            ['username' => 'customer5', 'email' => 'customer5@example.com', 'nama_lengkap' => 'Dewi Lestari'],
        ];

        foreach ($customers as $i => $data) {
            $user = User::updateOrCreate(
                ['email' => $data['email']],
                [
                    'username' => $data['username'],
                    'email' => $data['email'],
                    'nama_lengkap' => $data['nama_lengkap'],
                    'password' => Hash::make('CustomerPass1!'),
                    'role' => 'customer',
                    'nomor_telepon' => '0812000000' . ($i + 1),
                    'is_verified' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );

            // Create one primary address for each customer
            DB::table('user_address')->updateOrInsert([
                'user_id' => $user->id,
                'label' => 'Rumah'
            ], [
                'user_id' => $user->id,
                'label' => 'Rumah',
                'alamat' => 'Jl. Contoh No ' . ($i + 1) . ', Kota Contoh',
                'latitude' => -6.2 + ($i * 0.001),
                'longitude' => 106.8 + ($i * 0.001),
                'is_primary' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Ensure a corresponding customers row exists
            DB::table('customers')->updateOrInsert([
                'user_id' => $user->id,
            ], [
                'user_id' => $user->id,
                'foto_profil' => null,
                'status' => 'active',
                'reputasi' => rand(0, 100),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
