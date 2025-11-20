<?php

namespace App\Http\Controllers;

use App\Models\MerchantKategori;
use Illuminate\Http\Request;

class MerchantKategoriController extends Controller
{
    public function index()
    {
        $kategoris = MerchantKategori::paginate(15);
        return view('merchant.kategori.index', compact('kategoris'));
    }

    public function create()
    {
        return view('merchant.kategori.form');
    }

    public function store(Request $request)
    {
        $data = $request->validate(['name' => 'required|string|max:50']);
        MerchantKategori::create($data);
        return redirect()->route('merchant.kategori.index')->with('status', 'Kategori dibuat');
    }

    public function edit(MerchantKategori $merchantKategori)
    {
        return view('merchant.kategori.form', ['kategori' => $merchantKategori]);
    }

    public function update(Request $request, MerchantKategori $merchantKategori)
    {
        $data = $request->validate(['name' => 'required|string|max:50']);
        $merchantKategori->update($data);
        return redirect()->route('merchant.kategori.index')->with('status', 'Kategori diperbarui');
    }

    public function destroy(MerchantKategori $merchantKategori)
    {
        $merchantKategori->delete();
        return back()->with('status', 'Kategori dihapus');
    }
}
