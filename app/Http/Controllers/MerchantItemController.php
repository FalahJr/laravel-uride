<?php

namespace App\Http\Controllers;

use App\Models\Merchant;
use App\Models\MerchantItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MerchantItemController extends Controller
{
    public function index(Merchant $merchant)
    {
        $items = $merchant->items()->paginate(10);
        return view('merchant.items.index', compact('merchant', 'items'));
    }

    public function create(Merchant $merchant)
    {
        return view('merchant.items.form', compact('merchant'));
    }

    public function store(Request $request, Merchant $merchant)
    {
        $data = $request->validate([
            'nama_barang' => 'required|string|max:100',
            'harga' => 'required|numeric',
            'stock' => 'nullable|integer',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
            'is_available' => 'nullable|boolean',
        ]);

        // handle image upload
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $path = $request->file('image')->store('merchant_items', 'public');
            $data['image_url'] = Storage::url($path);
        }

        $data['is_available'] = $request->has('is_available') ? (bool) $request->is_available : true;

        $merchant->items()->create($data);

        return redirect()->route('merchant.items.index', $merchant->id)->with('status', 'Item dibuat');
    }

    public function edit(Merchant $merchant, MerchantItem $item)
    {
        return view('merchant.items.form', compact('merchant', 'item'));
    }

    public function update(Request $request, Merchant $merchant, MerchantItem $item)
    {
        $data = $request->validate([
            'nama_barang' => 'required|string|max:100',
            'harga' => 'required|numeric',
            'stock' => 'nullable|integer',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
            'is_available' => 'nullable|boolean',
        ]);

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            // delete previous image file if exists and stored in storage
            if (!empty($item->image_url)) {
                // try to remove '/storage/' prefix to get path in storage
                $previous = preg_replace('#^/storage/#', '', parse_url($item->image_url, PHP_URL_PATH));
                if ($previous && Storage::disk('public')->exists($previous)) {
                    Storage::disk('public')->delete($previous);
                }
            }
            $path = $request->file('image')->store('merchant_items', 'public');
            $data['image_url'] = Storage::url($path);
        }

        $data['is_available'] = $request->has('is_available') ? (bool) $request->is_available : true;

        $item->update($data);

        return redirect()->route('merchant.items.index', $merchant->id)->with('status', 'Item diperbarui');
    }

    public function destroy(Merchant $merchant, MerchantItem $item)
    {
        $item->delete();
        return back()->with('status', 'Item dihapus');
    }
}
