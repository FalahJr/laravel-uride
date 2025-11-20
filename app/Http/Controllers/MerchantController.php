<?php

namespace App\Http\Controllers;

use App\Models\Merchant;
use App\Models\MerchantKategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class MerchantController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->get('q');
        $query = Merchant::query()->with('kategori');
        if ($q) {
            $query->where('name', 'like', "%{$q}%");
        }
        $merchants = $query->paginate(15)->withQueryString();
        return view('merchant.index', compact('merchants', 'q'));
    }

    public function pengajuan(Request $request)
    {
        // Pengajuan: merchants with status != active (example)
        $q = $request->get('q');
        $query = Merchant::query()->where('status', '!=', 'active');
        if ($q) {
            $query->where('name', 'like', "%{$q}%");
        }
        $merchants = $query->paginate(15)->withQueryString();
        return view('merchant.pengajuan', compact('merchants', 'q'));
    }

    public function show(Merchant $merchant)
    {
        $merchant->load(['items', 'addresses', 'kategori']);
        // load ewallet balance for merchant owner
        $ewallet = DB::table('ewallet')->where('user_id', $merchant->user_id)->first();

        return view('merchant.show', compact('merchant', 'ewallet'));
    }

    public function create()
    {
        $kategoris = MerchantKategori::all();
        $users = User::orderBy('nama_lengkap')->get();
        return view('merchant.create', compact('kategoris', 'users'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'merchant_kategori_id' => 'nullable|exists:merchant_kategori,id',
            'name' => 'required|string|max:100',
        ]);

        // default to pending
        $data['status'] = 'pending';

        Merchant::create($data);

        return redirect()->route('merchant.index')->with('status', 'Pengajuan merchant berhasil dibuat.');
    }

    /**
     * Verify (approve/reject) merchant application
     */
    public function verify(Request $request, Merchant $merchant)
    {
        $action = $request->input('action'); // expected 'approve' or 'reject'
        $reason = $request->input('alasan_penolakan');

        if (!in_array($action, ['approve', 'reject'])) {
            if ($request->wantsJson() || $request->expectsJson()) {
                return response()->json(['status' => 'error', 'message' => 'Invalid action'], 400);
            }
            return redirect()->back()->with('error', 'Aksi tidak dikenali.');
        }

        if ($action === 'approve') {
            $merchant->status = 'active';
            $merchant->save();
            $message = 'Pengajuan merchant telah diterima.';
        } else { // reject
            $merchant->status = 'rejected';
            if (Schema::hasColumn('merchant', 'alasan_penolakan')) {
                $merchant->alasan_penolakan = $reason;
            }
            $merchant->save();
            $message = 'Pengajuan merchant ditolak.' . ($reason ? ' Alasan: ' . $reason : '');
        }

        // Prefer normal web redirect with flash for regular requests
        if (!($request->wantsJson() || $request->expectsJson())) {
            return redirect()->route('merchant.pengajuan')->with('status', $message);
        }

        // If request expects JSON (AJAX), return JSON response
        return response()->json(['status' => 'ok', 'message' => $message]);
    }

    // Additional methods (create/store/edit) could be added if merchants are managed from admin
}
