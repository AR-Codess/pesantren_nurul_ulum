<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use App\Models\User; // <-- TAMBAHKAN INI
use App\Observers\UserObserver; // <-- TAMBAHKAN INI

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register the DomPDF facade
        $this->app->bind('pdf', function() {
            return new \Barryvdh\DomPDF\PDF;
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);
        User::observe(UserObserver::class);
    }
}
