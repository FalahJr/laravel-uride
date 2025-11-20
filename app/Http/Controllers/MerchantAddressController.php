<?php

namespace App\Http\Controllers;

use App\Models\Merchant;
use App\Models\MerchantAddress;
use Illuminate\Http\Request;

class MerchantAddressController extends Controller
{
    public function index(Merchant $merchant)
    {
        $addresses = $merchant->addresses()->paginate(15);
        return view('merchant.address.index', compact('merchant', 'addresses'));
    }

    public function create(Merchant $merchant)
    {
        return view('merchant.address.form', compact('merchant'));
    }

    public function store(Request $request, Merchant $merchant)
    {
        $data = $request->validate([
            'alamat' => 'required|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);
        $merchant->addresses()->create($data);
        return redirect()->route('merchant.address.index', $merchant->id)->with('status', 'Alamat ditambahkan');
    }

    public function edit(Merchant $merchant, MerchantAddress $merchantAddress)
    {
        return view('merchant.address.form', ['merchant' => $merchant, 'address' => $merchantAddress]);
    }

    public function update(Request $request, Merchant $merchant, MerchantAddress $merchantAddress)
    {
        $data = $request->validate([
            'alamat' => 'required|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);
        $merchantAddress->update($data);
        return redirect()->route('merchant.address.index', $merchant->id)->with('status', 'Alamat diperbarui');
    }

    public function destroy(Merchant $merchant, MerchantAddress $merchantAddress)
    {
        $merchantAddress->delete();
        return back()->with('status', 'Alamat dihapus');
    }
}
