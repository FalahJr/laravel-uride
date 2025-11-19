<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('welcome');
});

// Auth routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// Home route after login
Route::middleware(['auth'])->group(function () {
    Route::get('/home', function () {
        return view('home');
    })->name('home');

    // Driver management
    Route::get('/drivers', [App\Http\Controllers\DriverController::class, 'index'])->name('drivers.index');
    Route::get('/drivers/pengajuan', [App\Http\Controllers\DriverController::class, 'pengajuan'])->name('drivers.pengajuan');
    Route::get('/drivers/{id}', [App\Http\Controllers\DriverController::class, 'show'])->name('drivers.show');
    Route::post('/drivers/{id}/verify', [App\Http\Controllers\DriverController::class, 'verify'])->name('drivers.verify');
});
