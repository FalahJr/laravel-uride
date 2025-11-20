<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EwalletTransactionSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ewallets = DB::table('ewallet')->get();

        foreach ($ewallets as $ewallet) {
            // create 5 topup and 5 withdraw transactions, status pending
            for ($i = 0; $i < 5; $i++) {
                DB::table('ewallet_transactions')->insert([
                    'ewallet_id' => $ewallet->id,
                    'type' => 'topup',
                    'amount' => rand(10000, 100000),
                    'status' => 'pending',
                    'proof_file' => null,
                    'created_at' => now()->subMinutes(rand(1, 1000)),
                    'updated_at' => now(),
                ]);
            }

            for ($i = 0; $i < 5; $i++) {
                DB::table('ewallet_transactions')->insert([
                    'ewallet_id' => $ewallet->id,
                    'type' => 'withdraw',
                    'amount' => rand(5000, 50000),
                    'status' => 'pending',
                    'proof_file' => null,
                    'created_at' => now()->subMinutes(rand(1, 1000)),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
