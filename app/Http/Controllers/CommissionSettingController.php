<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class CommissionSettingController extends Controller
{
    public function index()
    {
        $items = DB::table('commission_settings')->orderBy('id', 'asc')->get();
        return view('commission_settings.index', compact('items'));
    }

    public function create()
    {
        return view('commission_settings.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'service_type' => 'required|in:ride,food,delivery',
            'fixed_fee' => 'required|numeric|min:0',
        ], [
            'service_type.required' => 'Tipe layanan wajib diisi.',
            'service_type.in' => 'Tipe layanan tidak valid.',
            'fixed_fee.required' => 'Biaya tetap wajib diisi.',
            'fixed_fee.numeric' => 'Biaya tetap harus berupa angka.',
            'fixed_fee.min' => 'Biaya tetap minimal 0.',
        ]);

        DB::table('commission_settings')->insert([
            'service_type' => $data['service_type'],
            'fixed_fee' => $data['fixed_fee'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return Redirect::route('commission.settings.index')->with('success', 'Pengaturan komisi berhasil dibuat.');
    }

    public function edit($id)
    {
        $item = DB::table('commission_settings')->where('id', $id)->first();
        if (! $item) {
            return Redirect::route('commission.settings.index')->with('error', 'Pengaturan komisi tidak ditemukan.');
        }

        return view('commission_settings.edit', compact('item'));
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'service_type' => 'required|in:ride,food,delivery',
            'fixed_fee' => 'required|numeric|min:0',
        ], [
            'service_type.required' => 'Tipe layanan wajib diisi.',
            'service_type.in' => 'Tipe layanan tidak valid.',
            'fixed_fee.required' => 'Biaya tetap wajib diisi.',
            'fixed_fee.numeric' => 'Biaya tetap harus berupa angka.',
            'fixed_fee.min' => 'Biaya tetap minimal 0.',
        ]);

        DB::table('commission_settings')->where('id', $id)->update([
            'service_type' => $data['service_type'],
            'fixed_fee' => $data['fixed_fee'],
            'updated_at' => now(),
        ]);

        return Redirect::route('commission.settings.index')->with('success', 'Pengaturan komisi berhasil diperbarui.');
    }

    public function destroy($id)
    {
        DB::table('commission_settings')->where('id', $id)->delete();
        return Redirect::route('commission.settings.index')->with('success', 'Pengaturan komisi berhasil dihapus.');
    }
}
