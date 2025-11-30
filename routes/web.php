<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController; 
use App\Http\Controllers\MasterController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\SupplierController; 
use App\Http\Controllers\PosController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ModifierController; 
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    
    // --- Route Profil ---
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // --- Route Master Data ---
    Route::get('/master-data', [MasterController::class, 'index'])->name('master.index');

    // CRUD Kategori
    Route::post('/categories', [MasterController::class, 'storeCategory'])->name('categories.store');
    Route::put('/categories/{id}', [MasterController::class, 'updateCategory'])->name('categories.update');
    Route::delete('/categories/{id}', [MasterController::class, 'destroyCategory'])->name('categories.destroy');

    // CRUD Satuan
    Route::post('/units', [MasterController::class, 'storeUnit'])->name('units.store');
    Route::put('/units/{id}', [MasterController::class, 'updateUnit'])->name('units.update');
    Route::delete('/units/{id}', [MasterController::class, 'destroyUnit'])->name('units.destroy');

    // Route Produk
    Route::resource('products', ProductController::class);

    // Route Inventory
    Route::get('/inventory', [InventoryController::class, 'index'])->name('inventory.index');
    Route::get('/inventory/create', [InventoryController::class, 'create'])->name('inventory.create');
    Route::post('/inventory', [InventoryController::class, 'store'])->name('inventory.store');

    // Route Suppliers
    Route::resource('suppliers', SupplierController::class);

    // Route POS (Kasir)
    Route::get('/pos', [PosController::class, 'index'])->name('pos.index');
    Route::post('/pos/process', [PosController::class, 'store'])->name('pos.store');

    // Route Riwayat & Cetak Transaksi
    Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
    Route::get('/transactions/{id}/print', [TransactionController::class, 'print'])->name('transactions.print');    

    // Route Pengeluaran
    Route::resource('expenses', ExpenseController::class)->only(['index', 'store', 'destroy', 'update']);

    // Route Laporan Laba Rugi
    Route::get('/reports/profit-loss', [ReportController::class, 'profitLoss'])->name('reports.profit_loss');
    
    // Route Manajemen User
    Route::resource('users', UserController::class)->except(['create', 'show', 'edit']);

    // --- Route Modifiers ---
    Route::resource('modifiers', ModifierController::class);

    // --- Route Settings ---
    Route::middleware(['role:owner'])->group(function () {
        Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
        Route::get('/settings/create', [SettingController::class, 'create'])->name('settings.create');
        Route::post('/settings', [SettingController::class, 'store'])->name('settings.store');
        Route::get('/settings/edit', [SettingController::class, 'edit'])->name('settings.edit');
        Route::put('/settings', [SettingController::class, 'update'])->name('settings.update');
    });

      });

require __DIR__.'/auth.php';