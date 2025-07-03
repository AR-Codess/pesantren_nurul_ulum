<?php

use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;
use Illuminate\Support\Facades\Auth;

new class extends Component
{
    /**
     * Log the current user out of the application.
     */
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect(route('login'), navigate: true);
    }

    /**
     * Check if the user is authenticated in any guard
     */
    public function isLoggedIn(): bool
    {
        return Auth::guard('web')->check() || Auth::guard('admin')->check() || Auth::guard('guru')->check();
    }

    /**
     * Get the current authenticated user's name across all guards
     */
    public function getUserName(): string
    {
        if (Auth::guard('admin')->check()) {
            return Auth::guard('admin')->user()->name;
        } elseif (Auth::guard('guru')->check()) {
            return Auth::guard('guru')->user()->nama_pendidik;
        } elseif (Auth::guard('web')->check()) {
            return Auth::guard('web')->user()->nama_santri ?? Auth::guard('web')->user()->name;
        }

        return '';
    }

    /**
     * Get the current authenticated user's email across all guards
     */
    public function getUserEmail(): string
    {
        if (Auth::guard('admin')->check()) {
            return Auth::guard('admin')->user()->email;
        } elseif (Auth::guard('guru')->check()) {
            return Auth::guard('guru')->user()->email;
        } elseif (Auth::guard('web')->check()) {
            return Auth::guard('web')->user()->email;
        }

        return '';
    }
}; ?>

<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ url('/') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" wire:navigate>
                        {{ __('Dashboard') }}
                    </x-nav-link>

                    @if(Auth::guard('admin')->check() || (Auth::guard('web')->check() && Auth::guard('web')->user()->hasRole('admin')))
                    <x-nav-link :href="route('users.index')" :active="request()->routeIs('users.*')">
                        {{ __('Kelola Santri') }}
                    </x-nav-link>
                    <x-nav-link :href="route('guru.index')" :active="request()->routeIs('guru.*')">
                        {{ __('Kelola Guru') }}
                    </x-nav-link>
                    <x-nav-link :href="route('pembayaran.index')" :active="request()->routeIs('pembayaran.*')">
                        {{ __('Pembayaran') }}
                    </x-nav-link>
                    <x-nav-link :href="route('admin.berita.index')" :active="request()->routeIs('admin.berita.*')">
                        {{ __('Berita') }}
                    </x-nav-link>
                    <x-nav-link :href="route('kelola-spp')" :active="request()->routeIs('kelola-spp')">
                        {{ __('Kelola SPP') }}
                    </x-nav-link>
                    @endif
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                @if ($this->isLoggedIn())
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <div x-data="{{ json_encode(['name' => $this->getUserName()]) }}" x-text="name" x-on:profile-updated.window="name = $event.detail.name"></div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile')" wire:navigate>
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <button wire:click="logout" class="w-full text-start">
                            <x-dropdown-link>
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </button>
                    </x-slot>
                </x-dropdown>
                @else
                <div class="flex space-x-4">
                    <a href="{{ route('login') }}" class="text-sm font-medium text-gray-500 hover:text-gray-700">{{ __('Log in') }}</a>
                    {{-- Register link is not being used in this application --}}
                    @if (Route::has('register'))
                    {{-- <a href="{{ route('register') }}" class="text-sm font-medium text-gray-500 hover:text-gray-700">{{ __('Register') }}</a> --}}
                    @endif
                </div>
                @endif
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" wire:navigate>
                {{ __('Dashboard') }}
            </x-responsive-nav-link>

            @if(Auth::guard('admin')->check() || (Auth::guard('web')->check() && Auth::guard('web')->user()->hasRole('admin')))
            <x-responsive-nav-link :href="route('users.index')" :active="request()->routeIs('users.*')">
                {{ __('Kelola Santri') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('guru.index')" :active="request()->routeIs('guru.*')">
                {{ __('Kelola Guru') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('pembayaran.index')" :active="request()->routeIs('pembayaran.*')">
                {{ __('Pembayaran') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('admin.berita.index')" :active="request()->routeIs('admin.berita.*')">
                {{ __('Berita') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('kelola-spp')" :active="request()->routeIs('kelola-spp')">
                {{ __('Kelola SPP') }}
            </x-responsive-nav-link>
            @endif
        </div>

        <!-- Responsive Settings Options -->
        @if ($this->isLoggedIn())
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800" x-data="{{ json_encode(['name' => $this->getUserName()]) }}" x-text="name" x-on:profile-updated.window="name = $event.detail.name"></div>
                <div class="font-medium text-sm text-gray-500">{{ $this->getUserEmail() }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile')" wire:navigate>
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <button wire:click="logout" class="w-full text-start">
                    <x-responsive-nav-link>
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </button>
            </div>
        </div>
        @else
        <div class="py-3 border-t border-gray-200">
            <div class="space-y-1 px-4">
                <x-responsive-nav-link :href="route('login')" wire:navigate>
                    {{ __('Log in') }}
                </x-responsive-nav-link>
                {{-- Register link is not being used in this application --}}
                @if (Route::has('register'))
                {{-- <x-responsive-nav-link :href="route('register')" wire:navigate>
                    {{ __('Register') }}
                </x-responsive-nav-link> --}}
                @endif
            </div>
        </div>
        @endif
    </div>
</nav>