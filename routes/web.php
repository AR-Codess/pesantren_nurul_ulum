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
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KelolaSppController;

// Replace static welcome page with dynamic content from HomeController
Route::get('/', [HomeController::class, 'index'])->name('welcome');

// Public berita (news) routes
Route::get('/berita', [App\Http\Controllers\BeritaController::class, 'index'])->name('berita.index');
Route::get('/berita/{hashed_id}/{slug?}', [App\Http\Controllers\BeritaController::class, 'show'])->name('berita.show');


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
    Route::get('/admin/get-santri-by-class/{class_level_id}', [KelasController::class, 'getSantriByClassLevel'])->name('admin.getSantriByClass');
    
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

// API endpoints for dashboard charts
Route::prefix('api/dashboard')->group(function () {
    Route::get('/combined-growth', [DashboardController::class, 'getCombinedGrowth']);
    Route::get('/payment-summary-by-month', [DashboardController::class, 'getPaymentSummaryByMonth']);
    Route::get('/lunas-payments', [DashboardController::class, 'getLunasPayments']);
});

// Invoice generation route
Route::get('/invoice/{pembayaran}/download', [App\Http\Controllers\InvoiceController::class, 'generatePDF'])
    ->name('invoice.download')
    ->middleware(['auth:web,admin,guru']);

// Route untuk user memulai pembayaran online (INI YANG PALING PENTING UNTUK TOMBOL ANDA)
Route::get('/pembayaran/{id}/bayar-online', [PembayaranController::class, 'payWithMidtrans'])
    ->middleware('auth')
    ->name('pembayaran.pay_midtrans');

// Route untuk membuat tagihan otomatis lalu bayar
Route::get('/pembayaran/create-and-pay/{year}/{month}', [PembayaranController::class, 'createAndPay'])
    ->middleware('auth')
    ->name('pembayaran.create_and_pay');

// Admin dashboard route
Route::get('/admin-dashboard', [DashboardController::class, 'adminDashboard'])->name('admin.dashboard');

// User import route
Route::post('/user/import', [UserController::class, 'importExcel'])->name('users.import');
Route::post('/guru/import', [App\Http\Controllers\GuruController::class, 'importExcel'])->name('guru.import');

require __DIR__ . '/auth.php';
