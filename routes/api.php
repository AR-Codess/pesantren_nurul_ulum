<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PembayaranController;

Route::post('/midtrans/webhook', [PembayaranController::class, 'midtransNotificationHandler'])
    ->name('midtrans.webhook');
