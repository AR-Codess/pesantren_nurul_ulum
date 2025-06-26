<?php

//use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SantriController;
use App\Http\Controllers\GuruController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::resource('/santri', SantriController::class);
});

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';
