<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EwalletTransactionController extends Controller
{
    /**
     * List ewallet transactions (admin)
     */
    public function index(Request $request)
    {
        $q = $request->query('q');

        $query = DB::table('ewallet_transactions')
            ->leftJoin('ewallet', 'ewallet_transactions.ewallet_id', '=', 'ewallet.id')
            ->leftJoin('users', 'ewallet.user_id', '=', 'users.id')
            ->select('ewallet_transactions.*', 'ewallet.user_id as owner_id', 'users.nama_lengkap', 'users.email')
            // order: pending first, then by created_at oldest first
            ->orderByRaw("(CASE WHEN ewallet_transactions.status = 'pending' THEN 0 ELSE 1 END)")
            ->orderBy('ewallet_transactions.created_at', 'asc');

        if ($q) {
            $query->where(function ($r) use ($q) {
                $r->where('users.nama_lengkap', 'like', "%$q%")
                    ->orWhere('users.email', 'like', "%$q%");
            });
        }

        $transactions = $query->paginate(15)->withQueryString();

        return view('ewallet.transactions.index', compact('transactions', 'q'));
    }

    /**
     * Return JSON transactions for a specific user (by user id)
     */
    public function userTransactions(Request $request, $userId)
    {
        // find ewallet for the user
        $ewallet = DB::table('ewallet')->where('user_id', $userId)->first();
        if (! $ewallet) {
            return response()->json(['data' => [], 'message' => 'E-Wallet tidak ditemukan.'], 404);
        }

        $transactions = DB::table('ewallet_transactions')
            ->where('ewallet_id', $ewallet->id)
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json(['data' => $transactions]);
    }

    /**
     * Approve a transaction — mark approved and update ewallet saldo.
     */
    public function approve(Request $request, $id)
    {
        $tx = DB::table('ewallet_transactions')->where('id', $id)->first();
        if (! $tx) {
            return redirect()->back()->with('error', 'Transaksi tidak ditemukan.');
        }
        if ($tx->status !== 'pending') {
            return redirect()->back()->with('error', 'Transaksi sudah diproses.');
        }

        try {
            DB::transaction(function () use ($tx) {
                // lock wallet row for update
                $ewallet = DB::table('ewallet')->where('id', $tx->ewallet_id)->lockForUpdate()->first();
                if (! $ewallet) {
                    throw new \Exception('E-Wallet untuk transaksi ini tidak ditemukan.');
                }

                // validate amounts
                $amount = (float) $tx->amount;
                if ($amount <= 0) {
                    throw new \Exception('Jumlah transaksi tidak valid.');
                }

                // handle by type
                if ($tx->type === 'withdraw') {
                    // cannot withdraw if balance is zero or insufficient
                    if ((float) $ewallet->saldo <= 0 || (float) $ewallet->saldo < $amount) {
                        throw new \Exception('Saldo tidak mencukupi untuk penarikan.');
                    }

                    // deduct
                    DB::table('ewallet')->where('id', $ewallet->id)->decrement('saldo', $amount);
                } elseif ($tx->type === 'topup') {
                    // add
                    DB::table('ewallet')->where('id', $ewallet->id)->increment('saldo', $amount);
                } else {
                    // other types: do not modify balance by default
                }

                // finally mark transaction approved
                DB::table('ewallet_transactions')->where('id', $tx->id)->update(['status' => 'approved', 'updated_at' => now()]);
            });
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }

        return redirect()->route('ewallet.transactions.index')->with('status', 'Transaksi disetujui dan saldo diperbarui.');
    }

    /**
     * Reject a transaction — mark rejected.
     */
    public function reject(Request $request, $id)
    {
        $tx = DB::table('ewallet_transactions')->where('id', $id)->first();
        if (! $tx) {
            return redirect()->back()->with('error', 'Transaksi tidak ditemukan.');
        }
        if ($tx->status !== 'pending') {
            return redirect()->back()->with('error', 'Transaksi sudah diproses.');
        }

        DB::table('ewallet_transactions')->where('id', $id)->update(['status' => 'rejected', 'updated_at' => now()]);

        return redirect()->route('ewallet.transactions.index')->with('status', 'Transaksi ditolak.');
    }
}
