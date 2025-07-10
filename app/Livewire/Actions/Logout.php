<?php

namespace App\Livewire\Actions;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class Logout
{
    /**
     * Log the current user out of the application.
     */
    public function __invoke(): void
    {
        // Logout from all possible guards
        Auth::guard('web')->logout();
        Auth::guard('admin')->logout();
        Auth::guard('guru')->logout();

        Session::invalidate();
        Session::regenerateToken();
    }
}
