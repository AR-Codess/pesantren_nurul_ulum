@extends('layouts.app')

@section('content')
    <div class="container mx-auto max-w-xl py-8">
        <div class="bg-white shadow rounded-lg p-6">
            <h2 class="text-lg font-medium text-gray-900 mb-1">Update Email</h2>
            <p class="mb-6 text-sm text-gray-600">Pastikan akun Anda menggunakan alamat email yang valid untuk notifikasi dan
                pemulihan akun.</p>
            @if (session('success'))
                <div class="alert alert-success mb-4">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger mb-4">{{ session('error') }}</div>
            @endif
            <form action="{{ route('admin.profile.update') }}" method="POST" autocomplete="off" class="space-y-5">
                @csrf
                <div>
                    <label for="current_email" class="block text-sm font-medium text-gray-700 mb-1">Email Saat Ini</label>
                    <input type="email" id="current_email" class="form-control w-full" value="{{ $admin->email }}"
                        disabled readonly>
                </div>
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Baru</label>
                    <input type="email" name="email" id="email"
                        class="form-control w-full @error('email') is-invalid @enderror" value="{{ old('email') }}"
                        placeholder="Masukkan email baru Anda" required>
                    @error('email')
                        <div class="text-danger mt-1 small">{{ $message }}</div>
                    @enderror
                </div>
                <div>
                    <label for="email_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Email
                        Baru</label>
                    <input type="email" name="email_confirmation" id="email_confirmation" class="form-control w-full"
                        placeholder="Ketik ulang email baru Anda" required>
                </div>
                <div>
                    <label for="current_password" class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi dengan
                        Password</label>
                    <input type="password" name="current_password" id="current_password"
                        class="form-control w-full @error('current_password') is-invalid @enderror"
                        placeholder="Masukkan password Anda saat ini" required>
                    @error('current_password')
                        <div class="text-danger mt-1 small">{{ $message }}</div>
                    @enderror
                    <div class="form-text">Untuk keamanan, masukkan password Anda untuk menyimpan perubahan.</div>
                </div>
                <div class="flex items-center gap-4 mt-4">
                    <a href="{{ url()->previous() }}" class="btn btn-danger">Batal</a>
                    <button type="submit" class="btn btn-success">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
@endsection
