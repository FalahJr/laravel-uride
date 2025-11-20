<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DriverController extends Controller
{
    /**
     * Show verified drivers (is_verified = 1)
     */
    public function index(Request $request)
    {
        $q = $request->query('q');

        $query = DB::table('users')
            ->join('user_driver', 'users.id', '=', 'user_driver.user_id')
            ->select('users.id as user_id', 'users.username', 'users.email', 'users.nama_lengkap', 'users.nomor_telepon', 'user_driver.nomor_plat', 'user_driver.nomor_sim', 'user_driver.status', 'users.is_verified')
            ->where('users.role', 'driver')
            // show drivers whose driver status is not pending — explicitly include rejected so it appears in the index
            ->whereIn('user_driver.status', ['active', 'inactive', 'suspended']);

        if ($q) {
            $query->where(function ($r) use ($q) {
                $r->where('users.username', 'like', "%$q%")
                    ->orWhere('users.nama_lengkap', 'like', "%$q%")
                    ->orWhere('user_driver.nomor_plat', 'like', "%$q%");
            });
        }

        $drivers = $query->orderBy('users.created_at', 'desc')->paginate(10)->withQueryString();

        return view('drivers.index', compact('drivers', 'q'));
    }

    /**
     * Show pending driver applications (is_verified = 0)
     */
    public function pengajuan(Request $request)
    {
        $q = $request->query('q');

        $query = DB::table('users')
            ->join('user_driver', 'users.id', '=', 'user_driver.user_id')
            ->select('users.id as user_id', 'users.username', 'users.email', 'users.nama_lengkap', 'users.nomor_telepon', 'user_driver.nomor_plat', 'user_driver.nomor_sim', 'user_driver.status', 'users.is_verified')
            ->where('users.role', 'driver')
            ->where('users.is_verified', false)
            ->where('user_driver.status', 'pending');


        if ($q) {
            $query->where(function ($r) use ($q) {
                $r->where('users.username', 'like', "%$q%")
                    ->orWhere('users.nama_lengkap', 'like', "%$q%")
                    ->orWhere('user_driver.nomor_plat', 'like', "%$q%");
            });
        }

        $drivers = $query->orderBy('users.created_at', 'desc')->paginate(10)->withQueryString();

        return view('drivers.pengajuan', compact('drivers', 'q'));
    }

    /**
     * Show detail for a specific driver (user + user_driver combined)
     */
    public function show($id)
    {
        $driver = DB::table('users')
            ->join('user_driver', 'users.id', '=', 'user_driver.user_id')
            ->select('users.*', 'user_driver.*')
            ->where('users.id', $id)
            ->first();

        if (! $driver) {
            abort(404);
        }

        // load ewallet balance for this user (if exists)
        $ewallet = DB::table('ewallet')->where('user_id', $id)->first();

        return view('drivers.show', compact('driver', 'ewallet'));
    }

    /**
     * Optional: change verification status (approve/reject)
     */
    public function verify(Request $request, $id)
    {
        $action = $request->input('action'); // 'approve' or 'reject'
        $reason = $request->input('alasan_penolakan');

        $user = User::findOrFail($id);

        // Update user_driver record if exists
        $driverRow = DB::table('user_driver')->where('user_id', $user->id)->first();

        if ($action === 'approve') {
            // Mark user as verified and set driver status to inactive per request
            $user->is_verified = true;
            $user->save();

            if ($driverRow) {
                DB::table('user_driver')->where('user_id', $user->id)->update([
                    'status' => 'inactive',
                    'alasan_penolakan' => null,
                    'updated_at' => now(),
                ]);
            }
        } elseif ($action === 'reject') {
            // Mark user as not verified and record rejection reason
            $user->is_verified = false;
            $user->save();

            if ($driverRow) {
                DB::table('user_driver')->where('user_id', $user->id)->update([
                    'status' => 'rejected',
                    'alasan_penolakan' => $reason,
                    'updated_at' => now(),
                ]);
            }
        }

        return redirect()->route('drivers.pengajuan')->with('status', 'Perubahan status berhasil.');
    }
}
