<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Livewire\Volt\Component;

new class extends Component
{
    public string $name = '';
    public string $email = '';

    /**
     * Mount the component with the correct user data.
     */
    public function mount(): void
    {
        $user = Auth::user();

        // Logika untuk mengambil nama yang benar berdasarkan guard/role
        if (Auth::guard('admin')->check()) {
            $this->name = $user->name ?? '';
        } elseif (Auth::guard('guru')->check()) {
            $this->name = $user->nama_pendidik ?? '';
        } elseif (Auth::guard('web')->check()) {
            $this->name = $user->nama_santri ?? ($user->name ?? '');
        }

        $this->email = $user->email ?? '';
    }

    /**
     * Update the profile information for the currently authenticated user.
     */
    public function updateProfileInformation(): void
    {
        $user = Auth::user();

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            // Email dibuat opsional dan unik jika diisi
            'email' => ['nullable', 'string', 'lowercase', 'email', 'max:255', Rule::unique(get_class($user))->ignore($user->id)],
        ]);

        // Logika untuk menyimpan nama ke kolom yang benar
        if ($user instanceof \App\Models\Admin) { // Ganti dengan namespace model Admin Anda
            $user->name = $validated['name'];
        } elseif ($user instanceof \App\Models\Guru) { // Ganti dengan namespace model Guru Anda
            $user->nama_pendidik = $validated['name'];
        } elseif ($user instanceof User && property_exists($user, 'nama_santri')) {
            $user->nama_santri = $validated['name'];
        } else {
            $user->name = $validated['name'];
        }

        // Update email jika diisi dan berubah
        if (isset($validated['email']) && $user->email !== $validated['email']) {
            $user->email = $validated['email'];
            $user->email_verified_at = null;
        }

        $user->save();
        
        Session::flash('status', 'Profil berhasil diperbarui.');
        $this->dispatch('profile-updated', name: $validated['name']);
    }

    /**
     * Send an email verification notification.
     */
    public function sendVerification(): void
    {
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            return;
        }

        $user->sendEmailVerificationNotification();

        Session::flash('status', 'verification-link-sent');
    }
}; ?>

<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form wire:submit="updateProfileInformation" class="mt-6 space-y-6">
        <div>
            <x-input-label for="name" :value="__('Name')" />
            {{-- PERBAIKAN: Menghapus atribut 'disabled' dan 'bg-gray-100' --}}
            <x-text-input wire:model="name" id="name" name="name" type="text" class="mt-1 block w-full" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
            {{-- PERBAIKAN: Menghapus teks yang tidak relevan --}}
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            {{-- Dibuat readonly untuk mencegah perubahan email yang bisa jadi username --}}
            <x-text-input wire:model="email" id="email" name="email" type="email" class="mt-1 block w-full bg-gray-100" readonly autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if (auth()->user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! auth()->user()->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800">
                        {{ __('Your email address is unverified.') }}

                        <button wire:click.prevent="sendVerification" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            <x-action-message class="me-3" on="profile-updated">
                {{ __('Profil berhasi diubah') }}
            </x-action-message>
        </div>
    </form>
</section>
