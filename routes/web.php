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

    // Merchant management
    Route::get('/merchant', [App\Http\Controllers\MerchantController::class, 'index'])->name('merchant.index');
    Route::get('/merchant/create', [App\Http\Controllers\MerchantController::class, 'create'])->name('merchant.create');
    Route::post('/merchant', [App\Http\Controllers\MerchantController::class, 'store'])->name('merchant.store');
    Route::get('/merchant/pengajuan', [App\Http\Controllers\MerchantController::class, 'pengajuan'])->name('merchant.pengajuan');
    // Merchant kategori (resource)
    Route::resource('merchant/kategori', App\Http\Controllers\MerchantKategoriController::class)->names('merchant.kategori');

    // Customer management (only verified customers list)
    Route::get('/customers', [App\Http\Controllers\CustomerController::class, 'index'])->name('customers.index');
    Route::get('/customers/{id}', [App\Http\Controllers\CustomerController::class, 'show'])->name('customers.show');
    // Customer addresses
    Route::post('/customers/{id}/address', [App\Http\Controllers\CustomerAddressController::class, 'store'])->name('customers.address.store');
    Route::post('/customers/{id}/address/{address}/primary', [App\Http\Controllers\CustomerAddressController::class, 'setPrimary'])->name('customers.address.setPrimary');
    Route::put('/customers/{id}/address/{address}', [App\Http\Controllers\CustomerAddressController::class, 'update'])->name('customers.address.update');

    // Ewallet transactions management
    Route::get('/ewallet/transactions', [App\Http\Controllers\EwalletTransactionController::class, 'index'])->name('ewallet.transactions.index');
    Route::post('/ewallet/transactions/{id}/approve', [App\Http\Controllers\EwalletTransactionController::class, 'approve'])->name('ewallet.transactions.approve');
    Route::post('/ewallet/transactions/{id}/reject', [App\Http\Controllers\EwalletTransactionController::class, 'reject'])->name('ewallet.transactions.reject');
    // Get transactions for a specific user (AJAX)
    Route::get('/ewallet/transactions/user/{id}', [App\Http\Controllers\EwalletTransactionController::class, 'userTransactions'])->name('ewallet.transactions.user');

    // Merchant items (nested)
    Route::get('/merchant/{merchant}/items', [App\Http\Controllers\MerchantItemController::class, 'index'])->name('merchant.items.index');
    Route::get('/merchant/{merchant}/items/create', [App\Http\Controllers\MerchantItemController::class, 'create'])->name('merchant.items.create');
    Route::post('/merchant/{merchant}/items', [App\Http\Controllers\MerchantItemController::class, 'store'])->name('merchant.items.store');
    Route::get('/merchant/{merchant}/items/{item}/edit', [App\Http\Controllers\MerchantItemController::class, 'edit'])->name('merchant.items.edit');
    Route::put('/merchant/{merchant}/items/{item}', [App\Http\Controllers\MerchantItemController::class, 'update'])->name('merchant.items.update');
    Route::delete('/merchant/{merchant}/items/{item}', [App\Http\Controllers\MerchantItemController::class, 'destroy'])->name('merchant.items.destroy');

    // Merchant addresses (nested)
    Route::get('/merchant/{merchant}/address', [App\Http\Controllers\MerchantAddressController::class, 'index'])->name('merchant.address.index');
    Route::get('/merchant/{merchant}/address/create', [App\Http\Controllers\MerchantAddressController::class, 'create'])->name('merchant.address.create');
    Route::post('/merchant/{merchant}/address', [App\Http\Controllers\MerchantAddressController::class, 'store'])->name('merchant.address.store');
    Route::get('/merchant/{merchant}/address/{address}/edit', [App\Http\Controllers\MerchantAddressController::class, 'edit'])->name('merchant.address.edit');
    Route::put('/merchant/{merchant}/address/{address}', [App\Http\Controllers\MerchantAddressController::class, 'update'])->name('merchant.address.update');
    Route::delete('/merchant/{merchant}/address/{address}', [App\Http\Controllers\MerchantAddressController::class, 'destroy'])->name('merchant.address.destroy');

    // show & verify (keep these last to avoid catching static routes)
    Route::get('/merchant/{merchant}', [App\Http\Controllers\MerchantController::class, 'show'])->name('merchant.show');
    Route::post('/merchant/{merchant}/verify', [App\Http\Controllers\MerchantController::class, 'verify'])->name('merchant.verify');

    // Commission settings
    Route::resource('commission-settings', App\Http\Controllers\CommissionSettingController::class)
        ->names('commission.settings');
});
