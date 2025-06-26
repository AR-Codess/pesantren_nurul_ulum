<?php

use App\Http\Controllers\SantriController;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\PembayaranController;
use Illuminate\Support\Facades\Route;

// Public routes - welcome page with pesantren information
Route::view('/', 'welcome')->name('welcome');

// Routes that require authentication
Route::middleware(['auth'])->group(function () {
    // Dashboard route - accessible to all authenticated users
    Route::get('dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    
    // Profile route
    Route::view('profile', 'profile')->name('profile');

    // Admin specific routes
    Route::middleware(['role:admin'])->group(function () {
        // Santri management
        Route::resource('santri', SantriController::class);
        
        // Guru management
        Route::resource('guru', GuruController::class);
        
        // Payment confirmation for admin
        Route::post('/pembayaran/{id}/konfirmasi', [PembayaranController::class, 'konfirmasi'])
            ->name('pembayaran.konfirmasi');
    });
    
    // Guru specific routes
    Route::middleware(['role:guru'])->group(function () {
        // Absensi management for guru
        Route::get('/absensi', [AbsensiController::class, 'index'])->name('absensi.index');
        Route::get('/absensi/create', [AbsensiController::class, 'create'])->name('absensi.create');
        Route::post('/absensi', [AbsensiController::class, 'store'])->name('absensi.store');
    });
    
    // Santri specific routes
    Route::middleware(['role:santri'])->group(function () {
        // Pembayaran routes
        Route::get('/pembayaran', [PembayaranController::class, 'index'])->name('pembayaran.index');
        Route::get('/pembayaran/create', [PembayaranController::class, 'create'])->name('pembayaran.create');
        Route::post('/pembayaran', [PembayaranController::class, 'store'])->name('pembayaran.store');
        
        // Absensi check for santri (read-only)
        Route::get('/absensi/check', [AbsensiController::class, 'checkAbsensi'])->name('absensi.check');
    });

    // API routes for AJAX
    Route::get('/api/santri-by-kelas', [AbsensiController::class, 'getSantriByKelas']);
});

// Authentication routes
require __DIR__.'/auth.php';
