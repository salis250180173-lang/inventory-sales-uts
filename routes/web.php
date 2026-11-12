<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\SaleController;

// Halaman utama diarahkan ke halaman login
Route::get('/', function () {
    return redirect()->route('login');
});

// Autentikasi Login & Logout
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.process');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Route yang membutuhkan autentikasi login
Route::middleware(['auth'])->group(function () {
    // Dashboard & Modul Utama
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Master Data: Kategori, Produk, Supplier
    Route::resource('categories', CategoryController::class);
    Route::resource('products', ProductController::class);
    Route::resource('suppliers', SupplierController::class);

    // Transaksi Pembelian / Purchase
    Route::resource('purchases', PurchaseController::class)->except(['edit', 'update', 'destroy']);
    // Fitur Tambahan: Pembatalan Pembelian Khusus Owner
    Route::match(['get', 'patch', 'put'], '/purchases/{id}/cancel', [PurchaseController::class, 'cancel'])->name('purchases.cancel');

    // Transaksi Penjualan / Sales
    Route::get('/sales', [SaleController::class, 'index'])->name('sales.index');
    Route::get('/sales/create', [SaleController::class, 'create'])->name('sales.create');
    Route::post('/sales', [SaleController::class, 'store'])->name('sales.store');
    Route::get('/sales/{sale}', [SaleController::class, 'show'])->name('sales.show');

    // FITUR TAMBAHAN: Pembatalan Transaksi Penjualan Khusus Owner
    Route::match(['get', 'put', 'patch'], '/sales/{id}/cancel', [SaleController::class, 'cancel'])->name('sales.cancel');
});
