<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    /**
     * List active customers
     */
    public function index(Request $request)
    {
        $q = $request->query('q');

        $query = DB::table('users')
            ->leftJoin('user_address', 'users.id', '=', 'user_address.user_id')
            ->leftJoin('customers', 'users.id', '=', 'customers.user_id')
            ->select('users.*', 'customers.foto_profil', 'customers.status as customer_status', 'customers.reputasi')
            ->where('users.role', 'customer')
            ->where('users.is_verified', true);

        if ($q) {
            $query->where(function ($r) use ($q) {
                $r->where('users.username', 'like', "%$q%")
                    ->orWhere('users.nama_lengkap', 'like', "%$q%")
                    ->orWhere('users.email', 'like', "%$q%");
            });
        }

        $customers = $query->orderBy('users.created_at', 'desc')->paginate(10)->withQueryString();

        return view('customers.index', compact('customers', 'q'));
    }

    /**
     * Show pending customer applications
     */
    public function pengajuan(Request $request)
    {
        $q = $request->query('q');

        $query = DB::table('users')
            ->leftJoin('user_address', 'users.id', '=', 'user_address.user_id')
            ->leftJoin('customers', 'users.id', '=', 'customers.user_id')
            ->select('users.*', 'customers.foto_profil', 'customers.status as customer_status', 'customers.reputasi')
            ->where('users.role', 'customer')
            ->where('users.is_verified', false);

        if ($q) {
            $query->where(function ($r) use ($q) {
                $r->where('users.username', 'like', "%$q%")
                    ->orWhere('users.nama_lengkap', 'like', "%$q%")
                    ->orWhere('users.email', 'like', "%$q%");
            });
        }

        $customers = $query->orderBy('users.created_at', 'desc')->paginate(10)->withQueryString();

        return view('customers.pengajuan', compact('customers', 'q'));
    }

    /**
     * Show detail for a customer
     */
    public function show($id)
    {
        $customer = DB::table('users')
            ->leftJoin('user_address', 'users.id', '=', 'user_address.user_id')
            ->leftJoin('customers', 'users.id', '=', 'customers.user_id')
            ->select('users.*', 'customers.foto_profil', 'customers.status as customer_status', 'customers.reputasi')
            ->where('users.id', $id)
            ->first();

        if (! $customer) {
            abort(404);
        }

        // load addresses separately, primary first
        $addresses = DB::table('user_address')
            ->where('user_id', $id)
            ->orderByDesc('is_primary')
            ->orderByDesc('id')
            ->get();

        // load ewallet balance for this user (if exists)
        $ewallet = DB::table('ewallet')->where('user_id', $id)->first();

        return view('customers.show', compact('customer', 'addresses', 'ewallet'));
    }

    /**
     * Verify (approve/reject) customer
     */
    public function verify(Request $request, $id)
    {
        $action = $request->input('action'); // 'approve' or 'reject'
        $reason = $request->input('alasan_penolakan');

        $user = User::findOrFail($id);

        if ($action === 'approve') {
            $user->is_verified = true;
            $user->save();
        } elseif ($action === 'reject') {
            $user->is_verified = false;
            $user->save();
        }

        return redirect()->route('customers.pengajuan')->with('status', 'Perubahan status berhasil.');
    }
}
