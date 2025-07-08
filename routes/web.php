<?php

//use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Admin\GalleryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\Admin\KelasController;
use App\Http\Controllers\BeritaController;
use App\Http\Controllers\SppController;
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
    Route::resource('/admin/kelas', KelasController::class)->names('admin.kelas');
    // Hapus route index manual jika sudah pakai resource
    // Route::get('/admin/kelas', [KelasController::class, 'index'])->name('admin.kelas.index');
    // Tambahkan route admin.berita.index
    Route::get('/admin/berita', [\App\Http\Controllers\Admin\BeritaController::class, 'index'])->name('admin.berita.index');
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

// Midtrans payment route
Route::get('/bayar', [PaymentController::class, 'createTransaction']);

// Route for Berita
Route::get('/berita', [BeritaController::class, 'index'])->name('berita.index');

// Route for Kelola SPP (gunakan controller yang benar)
Route::get('/kelola-spp', [\App\Http\Controllers\KelolaSppController::class, 'index'])
     ->middleware('auth:admin')
     ->name('kelola-spp');
Route::post('/kelola-spp', [\App\Http\Controllers\KelolaSppController::class, 'store'])
     ->middleware('auth:admin')
     ->name('kelola-spp.store');
Route::put('/kelola-spp/{id}', [\App\Http\Controllers\KelolaSppController::class, 'update'])
     ->middleware('auth:admin')
     ->name('kelola-spp.update');
Route::delete('/kelola-spp/{id}', [\App\Http\Controllers\KelolaSppController::class, 'destroy'])
     ->middleware('auth:admin')
     ->name('kelola-spp.destroy');

// Daftar tagihan user
Route::get('/tagihan', [PaymentController::class, 'index'])->middleware('auth')->name('tagihan.index');
// Bayar tagihan (dengan parameter periode)
Route::get('/tagihan/{id}/bayar', [PaymentController::class, 'createTransaction'])->middleware('auth')->name('tagihan.bayar');
// Webhook Midtrans
Route::post('/midtrans/webhook', [PaymentController::class, 'notificationHandler']);

// Tambahkan endpoint untuk data chart pembayaran admin dashboard
Route::get('/admin/payment-chart-data', function () {
    $component = app(\App\Livewire\AdminDashboard::class);
    $component->loadData();
    return response()->json($component->paymentChartData);
})->middleware(['auth:admin', 'role:admin|admin']);

require __DIR__ . '/auth.php';
