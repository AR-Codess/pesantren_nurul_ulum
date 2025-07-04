<?php

//use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Admin\BeritaController;
use App\Http\Controllers\Admin\KelasController;
use App\Http\Controllers\CKEditorController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KelolaSppController;

// Replace static welcome page with dynamic content from HomeController
Route::get('/', [HomeController::class, 'index'])->name('welcome');

// Public berita (news) routes
Route::get('/berita', [App\Http\Controllers\BeritaController::class, 'index'])->name('berita.index');
Route::get('/berita/{id}/{slug?}', [App\Http\Controllers\BeritaController::class, 'show'])->name('berita.show');

// Admin routes
Route::middleware(['auth:admin', 'role:admin|admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/financial-report', [AdminController::class, 'financialReport'])->name('admin.financial-report');
    Route::put('/admin/payment/{id}/update-status', [AdminController::class, 'updatePaymentStatus'])->name('admin.update-payment-status');
    // CRUD Kelola SPP
    Route::get('/kelola-spp', [KelolaSppController::class, 'index'])->name('kelola-spp');
    Route::post('/kelola-spp', [KelolaSppController::class, 'store'])->name('kelola-spp.store');
    Route::post('/kelola-spp/{id}/update', [KelolaSppController::class, 'update'])->name('kelola-spp.update');
    Route::delete('/kelola-spp/{id}', [KelolaSppController::class, 'destroy'])->name('kelola-spp.destroy');

    // berita management routes
    Route::resource('/admin/berita', BeritaController::class)->names('admin.berita');
    Route::post('/admin/berita/update-order', [BeritaController::class, 'updateOrder'])->name('admin.berita.update-order');
    
    // Kelas (Class) management routes
    Route::resource('/admin/kelas', KelasController::class)->names('admin.kelas');
    
    // CKEditor image upload dan browse routes
    Route::post('/ckeditor/upload', [CKEditorController::class, 'upload'])->name('ckeditor.upload');
    Route::get('/ckeditor/browse', [CKEditorController::class, 'browse'])->name('ckeditor.browse');
    
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
