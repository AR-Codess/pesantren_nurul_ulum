<?php

namespace App\Livewire\Forms;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Validate;
use Livewire\Form;

class LoginForm extends Form
{
    #[Validate('required|string')]
    public string $login = '';

    #[Validate('required|string')]
    public string $password = '';

    #[Validate('boolean')]
    public bool $remember = false;

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        $login = $this->login;
        $password = $this->password;
        $remember = $this->remember;

        // Deteksi tipe login
        if (filter_var($login, FILTER_VALIDATE_EMAIL)) {
            // Login sebagai admin
            $guard = 'admin';
            $credentials = ['email' => $login, 'password' => $password];
        } elseif (preg_match('/^\d{16}$/', $login)) {
            // Login sebagai guru (NIK 16 digit)
            $guard = 'guru';
            $credentials = ['nik' => $login, 'password' => $password];
        } elseif (preg_match('/^\d+$/', $login)) {
            // Login sebagai user/santri (NIS)
            $guard = 'web';
            $credentials = ['nis' => $login, 'password' => $password];
        } else {
            throw ValidationException::withMessages([
                'form.login' => 'Format login tidak valid. Masukkan email, NIS, atau NIK.'
            ]);
        }

        if (!Auth::guard($guard)->attempt($credentials, $remember)) {
            RateLimiter::hit($this->throttleKey());
            throw ValidationException::withMessages([
                'form.login' => trans('auth.failed'),
            ]);
        }

        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Ensure the authentication request is not rate limited.
     */
    protected function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout(request()));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'form.login' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the authentication rate limiting throttle key.
     */
    protected function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->login).'|'.request()->ip());
    }
}
