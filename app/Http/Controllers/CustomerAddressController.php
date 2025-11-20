<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerAddressController extends Controller
{
    /**
     * Store a new address for the customer.
     */
    public function store(Request $request, $id)
    {
        $data = $request->validate([
            'label' => 'nullable|string|max:100',
            'alamat' => 'required|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'is_primary' => 'nullable|boolean',
        ]);

        DB::transaction(function () use ($data, $id) {
            // If is_primary is truthy, mark other addresses as not primary
            if (!empty($data['is_primary'])) {
                DB::table('user_address')->where('user_id', $id)->update(['is_primary' => false]);
            }

            DB::table('user_address')->insert([
                'user_id' => $id,
                'label' => $data['label'] ?? null,
                'alamat' => $data['alamat'],
                'latitude' => $data['latitude'] ?? null,
                'longitude' => $data['longitude'] ?? null,
                'is_primary' => !empty($data['is_primary']) ? true : false,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        });

        return redirect()->route('customers.show', $id)->with('status', 'Alamat berhasil ditambahkan.');
    }

    /**
     * Set an address as primary for the customer (only one primary allowed).
     */
    public function setPrimary(Request $request, $id, $address)
    {
        DB::transaction(function () use ($id, $address) {
            DB::table('user_address')->where('user_id', $id)->update(['is_primary' => false]);
            DB::table('user_address')->where('id', $address)->where('user_id', $id)->update(['is_primary' => true, 'updated_at' => now()]);
        });

        return redirect()->route('customers.show', $id)->with('status', 'Alamat utama berhasil diperbarui.');
    }

    /**
     * Update an existing address.
     */
    public function update(Request $request, $id, $address)
    {
        $data = $request->validate([
            'label' => 'nullable|string|max:100',
            'alamat' => 'required|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'is_primary' => 'nullable|boolean',
        ]);

        DB::transaction(function () use ($data, $id, $address) {
            if (!empty($data['is_primary'])) {
                DB::table('user_address')->where('user_id', $id)->update(['is_primary' => false]);
            }

            DB::table('user_address')->where('id', $address)->where('user_id', $id)->update([
                'label' => $data['label'] ?? null,
                'alamat' => $data['alamat'],
                'latitude' => $data['latitude'] ?? null,
                'longitude' => $data['longitude'] ?? null,
                'is_primary' => !empty($data['is_primary']) ? true : false,
                'updated_at' => now(),
            ]);
        });

        return redirect()->route('customers.show', $id)->with('status', 'Alamat berhasil diperbarui.');
    }
}
