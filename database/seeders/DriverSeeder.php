<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class DriverSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $drivers = [
            [
                'username' => 'driver1',
                'email' => 'driver1@example.com',
                'nama_lengkap' => 'Andi Saputra',
                'password' => Hash::make('DriverPass1!'),
                'role' => 'driver',
                'nim' => null,
                'nomor_telepon' => '081100000001',
                'jenis_kelamin' => 'L',
                'tanggal_lahir' => '1990-01-10',
                'is_verified' => false,
            ],
            [
                'username' => 'driver2',
                'email' => 'driver2@example.com',
                'nama_lengkap' => 'Budi Hartono',
                'password' => Hash::make('DriverPass2!'),
                'role' => 'driver',
                'nim' => null,
                'nomor_telepon' => '081100000002',
                'jenis_kelamin' => 'L',
                'tanggal_lahir' => '1988-05-22',
                'is_verified' => false,
            ],
            [
                'username' => 'driver3',
                'email' => 'driver3@example.com',
                'nama_lengkap' => 'Citra Dewi',
                'password' => Hash::make('DriverPass3!'),
                'role' => 'driver',
                'nim' => null,
                'nomor_telepon' => '081100000003',
                'jenis_kelamin' => 'P',
                'tanggal_lahir' => '1992-08-15',
                'is_verified' => false,
            ],
            [
                'username' => 'driver4',
                'email' => 'driver4@example.com',
                'nama_lengkap' => 'Dedi Pratama',
                'password' => Hash::make('DriverPass4!'),
                'role' => 'driver',
                'nim' => null,
                'nomor_telepon' => '081100000004',
                'jenis_kelamin' => 'L',
                'tanggal_lahir' => '1991-03-30',
                'is_verified' => false,
            ],
            [
                'username' => 'driver5',
                'email' => 'driver5@example.com',
                'nama_lengkap' => 'Eka Mariana',
                'password' => Hash::make('DriverPass5!'),
                'role' => 'driver',
                'nim' => null,
                'nomor_telepon' => '081100000005',
                'jenis_kelamin' => 'P',
                'tanggal_lahir' => '1993-11-02',
                'is_verified' => false,
            ],
        ];

        foreach ($drivers as $index => $data) {
            // Use updateOrCreate so running the seeder multiple times won't fail on unique constraints
            $user = User::updateOrCreate(
                ['email' => $data['email']],
                $data
            );

            // Prepare driver data
            $driverData = [
                'user_id' => $user->id,
                'nomor_plat' => 'B' . (1000 + $index) . 'XYZ',
                'nomor_sim' => 'SIM' . rand(100000, 999999),
                'nomor_stnk' => 'STNK' . rand(100000, 999999),
                'nomor_ktp' => 'KTP' . (7000000000000000 + $index),
                'alamat' => "Jl. Contoh No. " . ($index + 1) . ", Kota Contoh",
                'reputasi' => 0,
                'status' => 'pending',
                'created_at' => now(),
                'updated_at' => now(),
            ];

            DB::table('user_driver')->updateOrInsert([
                'user_id' => $user->id
            ], $driverData);
        }
    }
}
