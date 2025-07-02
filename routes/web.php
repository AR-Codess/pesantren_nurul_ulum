<?php

//use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Admin\GalleryController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

// Replace static welcome page with dynamic content from HomeController
Route::get('/', [HomeController::class, 'index'])->name('welcome');

// Admin routes
Route::middleware(['auth:admin', 'role:admin|admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/financial-report', [AdminController::class, 'financialReport'])->name('admin.financial-report');
    Route::put('/admin/payment/{id}/update-status', [AdminController::class, 'updatePaymentStatus'])->name('admin.update-payment-status');
    
    // Gallery management routes
    Route::resource('/admin/gallery', GalleryController::class)->names('admin.gallery');
    Route::post('/admin/gallery/update-order', [GalleryController::class, 'updateOrder'])->name('admin.gallery.update-order');
    
    Route::resource('/users', UserController::class);
    Route::resource('/guru', GuruController::class);
    Route::resource('/pembayaran', PembayaranController::class);
});

// Guru routes
Route::middleware(['auth:guru', 'role:guru|guru'])->group(function () {
    Route::resource('/absensi', AbsensiController::class);
});

// User routes
Route::middleware(['auth:web', 'role:user|web'])->group(function () {
    Route::get('/absensi/check', [AbsensiController::class, 'check'])->name('absensi.check');
    Route::get('/pembayaran/user', [PembayaranController::class, 'userIndex'])->name('pembayaran.user');
});

// Export Absensi (PDF/Excel) - akses untuk admin dan guru
Route::middleware(['auth:admin,guru', 'role:admin|admin,guru|guru'])->group(function () {
    Route::get('/absensi/export/{format}', [AbsensiController::class, 'export'])->name('absensi.export');
});

// Dashboard route accessible by any authenticated user
Route::view('dashboard', 'dashboard')
    ->middleware(['auth:web,admin,guru', 'verified'])
    ->name('dashboard');

// Profile route accessible by any authenticated user
Route::view('profile', 'profile')
    ->middleware(['auth:web,admin,guru'])
    ->name('profile');

// Installment payment routes
Route::get('/pembayaran/{id}/cicilan', [PembayaranController::class, 'showInstallmentPage'])
    ->name('pembayaran.installment.show');
Route::post('/pembayaran/cicilan/store', [PembayaranController::class, 'storeInstallment'])
    ->name('pembayaran.installment.store');
Route::get('/pembayaran/{id}/cicilan/history', [PembayaranController::class, 'getInstallmentHistory'])
    ->name('pembayaran.installment.history');

// API endpoints for payment status checking
Route::get('/api/check-payment-status/{userId}/{month}', [PembayaranController::class, 'checkPaymentStatus']);

require __DIR__ . '/auth.php';
