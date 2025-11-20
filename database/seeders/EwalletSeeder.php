<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class EwalletSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = ['customer', 'merchant', 'driver'];

        $users = DB::table('users')->whereIn('role', $roles)->get();

        foreach ($users as $user) {
            DB::table('ewallet')->updateOrInsert([
                'user_id' => $user->id,
            ], [
                'user_id' => $user->id,
                'saldo' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
