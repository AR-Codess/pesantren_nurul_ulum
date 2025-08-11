@extends('layouts.app')

@section('content')
<div class="flex justify-center py-10">
    <div class="w-full max-w-lg bg-white shadow-lg rounded-xl p-8">
        <h2 class="text-2xl font-bold text-gray-800 mb-2">Update Email</h2>
        <p class="mb-6 text-sm text-gray-500">Pastikan akun Anda menggunakan alamat email yang valid untuk notifikasi dan pemulihan akun.</p>
        @if (session('success'))
        <div class="mb-4 px-4 py-3 rounded bg-green-100 text-green-800 border-l-4 border-green-500">{{ session('success') }}</div>
        @endif
        @if (session('error'))
        <div class="mb-4 px-4 py-3 rounded bg-red-100 text-red-800 border-l-4 border-red-500">{{ session('error') }}</div>
        @endif
        <form action="{{ route('admin.profile.update') }}" method="POST" autocomplete="off" class="space-y-6">
            @csrf
            <div>
                <label for="current_email" class="block text-sm font-semibold text-gray-700 mb-1">Email Saat Ini</label>
                <input type="email" id="current_email" class="w-full bg-gray-100 border border-gray-300 rounded px-4 py-2" value="{{ $admin->email }}" disabled readonly>
            </div>
            <div>
                <label for="email" class="block text-sm font-semibold text-gray-700 mb-1">Email Baru</label>
                <input type="email" name="email" id="email" class="w-full border border-gray-300 rounded px-4 py-2 focus:ring-2 focus:ring-blue-200 focus:border-blue-400 transition @error('email') border-red-500 @enderror" value="{{ old('email') }}" placeholder="Masukkan email baru Anda" required>
                @error('email')
                <div class="text-red-600 mt-1 text-xs">{{ $message }}</div>
                @enderror
            </div>
            <div>
                <label for="email_confirmation" class="block text-sm font-semibold text-gray-700 mb-1">Konfirmasi Email Baru</label>
                <input type="email" name="email_confirmation" id="email_confirmation" class="w-full border border-gray-300 rounded px-4 py-2 focus:ring-2 focus:ring-blue-200 focus:border-blue-400 transition" placeholder="Ketik ulang email baru Anda" required>
            </div>
            <div>
                <label for="current_password" class="block text-sm font-semibold text-gray-700 mb-1">Konfirmasi dengan Password</label>
                <input type="password" name="current_password" id="current_password" class="w-full border border-gray-300 rounded px-4 py-2 focus:ring-2 focus:ring-blue-200 focus:border-blue-400 transition @error('current_password') border-red-500 @enderror" placeholder="Masukkan password Anda saat ini" required>
                @error('current_password')
                <div class="text-red-600 mt-1 text-xs">{{ $message }}</div>
                @enderror
                <div class="text-xs text-gray-500 mt-1">Untuk keamanan, masukkan password Anda untuk menyimpan perubahan.</div>
            </div>
            <div class="flex items-center gap-4 mt-6">
                <a href="{{ url()->previous() }}" class="px-4 py-2 rounded bg-gray-200 text-gray-700 hover:bg-gray-300 font-semibold transition">Batal</a>
                <button type="submit" class="px-4 py-2 rounded bg-blue-600 text-white hover:bg-blue-700 font-semibold transition">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
@endsection