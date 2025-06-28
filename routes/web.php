<?php

//use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

// Admin routes
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/financial-report', [AdminController::class, 'financialReport'])->name('admin.financial-report');
    Route::put('/admin/payment/{id}/update-status', [AdminController::class, 'updatePaymentStatus'])->name('admin.update-payment-status');
    
    Route::resource('/users', UserController::class);
    Route::resource('/guru', GuruController::class);
    Route::resource('/pembayaran', PembayaranController::class);
});

// Guru routes
Route::middleware(['auth', 'role:guru'])->group(function () {
    Route::resource('/absensi', AbsensiController::class);
});

// User routes
Route::middleware(['auth', 'role:user'])->group(function () {
    Route::get('/absensi/check', [AbsensiController::class, 'check'])->name('absensi.check');
    Route::get('/pembayaran/user', [PembayaranController::class, 'userIndex'])->name('pembayaran.user');
});

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';
