<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public LoginForm $form;
    public string $guard_used = 'web'; // Track which guard was used

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        // Pass a closure to get back the guard used for authentication
        $this->form->authenticate();
        
        // Determine which guard was used for authentication
        if (Auth::guard('admin')->check()) {
            $this->guard_used = 'admin';
        } elseif (Auth::guard('guru')->check()) {
            $this->guard_used = 'guru';
        } else {
            $this->guard_used = 'web';
        }

        Session::regenerate();

        // Log information for debugging
        \Illuminate\Support\Facades\Log::info('Login successful with guard: ' . $this->guard_used);
        \Illuminate\Support\Facades\Log::info('Admin guard check: ' . (Auth::guard('admin')->check() ? 'true' : 'false'));
        \Illuminate\Support\Facades\Log::info('Guru guard check: ' . (Auth::guard('guru')->check() ? 'true' : 'false'));
        \Illuminate\Support\Facades\Log::info('Web guard check: ' . (Auth::guard('web')->check() ? 'true' : 'false'));

        // Redirect based on the determined guard
        if ($this->guard_used === 'admin') {
            $this->redirect(route('dashboard', absolute: false), navigate: true);
        } elseif ($this->guard_used === 'guru') {
            $this->redirect(route('dashboard', absolute: false), navigate: true);
        } else {
            // Default for regular users
            $this->redirect(route('dashboard', absolute: false), navigate: true);
        }
    }
}; ?>

<div>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form wire:submit="login">
        <!-- Login Field (Email/NIS/NIK) -->
        <div>
            <x-input-label for="login" :value="('Email / NIS / NIK')" />
            <x-text-input wire:model="form.login" id="login" class="block mt-1 w-full" type="text" name="login" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('form.login')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="('Password')" />

            <x-text-input wire:model="form.password" id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('form.password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember" class="inline-flex items-center">
                <input wire:model="form.remember" id="remember" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-4">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}" wire:navigate>
                    {{ __('Forgot your password?') }}
                </a>
            @endif

            <x-primary-button class="ms-3">
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form>
</div>