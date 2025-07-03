@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-gradient-to-br from-green-400 to-blue-500 p-8 rounded-2xl shadow-lg">
            @if(session('success'))
            <div
                x-data="{ show: true }"
                x-init="setTimeout(() => show = false, 2500)"
                x-show="show"
                x-transition:leave="transition ease-in duration-300"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="mb-4 p-3 bg-green-100 text-green-800 rounded shadow font-semibold flex items-center gap-2"
                style="min-height:44px">
                <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                </svg>
                <span>{{ session('success') }}</span>
            </div>
            @endif
            <div>
                <div x-data="{ editId: null, editSpp: null, editSppBeasiswa: null, showForm: false }">
                    <!-- Button tambah kelas dipindah ke header -->
                    <div class="flex flex-col sm:flex-row sm:items-center mb-6 justify-between gap-4">
                        <div class="flex items-center">
                            <svg class="w-10 h-10 text-white mr-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 1.343-3 3s1.343 3 3 3 3-1.343 3-3-1.343-3-3-3zm0 0V4m0 8v8m8-8a8 8 0 11-16 0 8 8 0 0116 0z" />
                            </svg>
                            <h1 class="text-3xl font-bold text-white">Kelola SPP Kelas</h1>
                        </div>
                        <button @click="showForm = !showForm" type="button" class="flex items-center gap-2 px-5 py-2 rounded-xl bg-gradient-to-r from-blue-500 to-green-500 text-white font-semibold shadow hover:from-blue-600 hover:to-green-600 transition focus:outline-none focus:ring-2 focus:ring-blue-300">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                            </svg>
                            Tambah Kelas
                        </button>
                    </div>
                    <form x-show="showForm" x-transition method="POST" action="{{ route('kelola-spp.store') }}" class="mb-8 bg-white rounded-xl shadow p-6 flex flex-col gap-4" @click.away="showForm = false">
                        @csrf
                        <div class="flex flex-col md:flex-row md:items-end gap-4 w-full">
                            <div class="flex flex-col w-full md:w-1/3">
                                <label for="level" class="mb-1 text-sm font-semibold text-blue-700">Level Kelas</label>
                                <input type="text" id="level" name="level" placeholder="Contoh: 7" class="border border-blue-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-200 focus:border-blue-400 transition w-full" required>
                            </div>
                            <div class="flex flex-col w-full md:w-1/3">
                                <label for="spp" class="mb-1 text-sm font-semibold text-green-700">SPP</label>
                                <input type="number" id="spp" name="spp" placeholder="Nominal SPP" class="border border-green-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-200 focus:border-green-400 transition w-full" required min="0">
                            </div>
                            <div class="flex flex-col w-full md:w-1/3">
                                <label for="spp_beasiswa" class="mb-1 text-sm font-semibold text-yellow-700">SPP Beasiswa</label>
                                <input type="number" id="spp_beasiswa" name="spp_beasiswa" placeholder="Nominal SPP Beasiswa" class="border border-yellow-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-yellow-200 focus:border-yellow-400 transition w-full" min="0">
                            </div>
                            <div class="flex flex-col md:w-auto justify-end items-end mt-4 md:mt-0">
                                <button type="submit" class="flex items-center justify-center w-12 h-12 rounded-full bg-gradient-to-r from-blue-500 to-green-500 text-white shadow hover:from-blue-600 hover:to-green-600 transition">
                                    <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </form>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @forelse($levels as $level)
                        <div class="bg-white rounded-xl shadow p-6 flex flex-col items-center border border-blue-100 transition hover:shadow-xl">
                            <div class="flex items-center mb-2 w-full justify-between">
                                <span class="inline-block px-4 py-2 rounded-full bg-blue-100 text-blue-700 font-bold text-lg shadow">Kelas {{ $level->level }}</span>
                                <template x-if="editId !== {{ $level->id }}">
                                    <div class="flex gap-2">
                                        <button type="button" @click="editId = {{ $level->id }}; editSpp = {{ $level->spp }}; editSppBeasiswa = {{ $level->spp_beasiswa ?? 'null' }}"
                                            class="flex items-center justify-center w-10 h-10 rounded-full bg-blue-100 hover:bg-blue-200 transition shadow group"
                                            title="Edit">
                                            <svg class="w-5 h-5 text-blue-600 group-hover:text-blue-800 transition" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536M9 13l6.536-6.536a2 2 0 112.828 2.828L11.828 15.828a2 2 0 01-2.828 0L9 13zm-6 6h6" />
                                            </svg>
                                        </button>
                                        <form method="POST" action="{{ route('kelola-spp.destroy', $level->id) }}" onsubmit="return confirm('Yakin hapus kelas ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="flex items-center justify-center w-10 h-10 rounded-full bg-red-100 hover:bg-red-200 transition shadow group"
                                                title="Hapus">
                                                <svg class="w-5 h-5 text-red-600 group-hover:text-red-800 transition" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </template>
                            </div>
                            <div class="flex flex-col items-center mt-2 w-full">
                                <span class="text-gray-500 text-sm mb-1">Nominal SPP</span>
                                <template x-if="editId !== {{ $level->id }}">
                                    <div class="flex flex-col gap-1 w-full justify-center items-center">
                                        <span class="text-2xl font-bold text-green-600 mb-1">Rp {{ number_format($level->spp, 0, ',', '.') }}</span>
                                        <span class="text-sm text-yellow-700 font-semibold">SPP Beasiswa:
                                            <span class="font-bold text-yellow-600">{{ $level->spp_beasiswa ? 'Rp ' . number_format($level->spp_beasiswa, 0, ',', '.') : '-' }}</span>
                                        </span>
                                    </div>
                                </template>
                                <template x-if="editId === {{ $level->id }}">
                                    <form method="POST" action="{{ route('kelola-spp.update', $level->id) }}" class="w-full flex flex-col items-center mt-2">
                                        @csrf
                                        <div class="flex flex-col w-full gap-2 bg-blue-50 p-4 rounded-lg border border-blue-200 items-center justify-center sm:items-stretch sm:justify-start">
                                            <div class="flex flex-col w-full">
                                                <label for="edit_spp_{{ $level->id }}" class="mb-1 text-xs font-semibold text-green-700">Nominal SPP</label>
                                                <input type="number" id="edit_spp_{{ $level->id }}" name="spp" x-model="editSpp" min="0" class="border border-green-300 rounded px-3 py-2 w-full text-center font-semibold text-green-700 focus:ring focus:ring-green-200 focus:border-green-400 transition" />
                                            </div>
                                            <div class="flex flex-col w-full mt-2">
                                                <label for="edit_spp_beasiswa_{{ $level->id }}" class="mb-1 text-xs font-semibold text-yellow-700">SPP Beasiswa</label>
                                                <input type="number" id="edit_spp_beasiswa_{{ $level->id }}" name="spp_beasiswa" x-model="editSppBeasiswa" min="0" placeholder="SPP Beasiswa" class="border border-yellow-300 rounded px-3 py-2 w-full text-center font-semibold text-yellow-700 focus:ring focus:ring-yellow-200 focus:border-yellow-400 transition" />
                                            </div>
                                            <div class="flex flex-row gap-2 items-center justify-end mt-4 w-full">
                                                <button type="submit" class="flex items-center justify-center w-9 h-9 rounded-full bg-green-100 hover:bg-green-200 transition shadow group" title="Simpan">
                                                    <svg class="w-5 h-5 text-green-600 group-hover:text-green-800 transition" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                </button>
                                                <button type="button" @click="editId = null" class="flex items-center justify-center w-9 h-9 rounded-full bg-gray-200 hover:bg-gray-300 transition shadow group" title="Batal">
                                                    <svg class="w-5 h-5 text-gray-600 group-hover:text-gray-800 transition" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </template>
                            </div>
                        </div>
                        @empty
                        <div class="col-span-1 md:col-span-2 flex flex-col items-center justify-center py-16">
                            <svg class="w-16 h-16 text-blue-300 mb-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            <p class="text-lg font-semibold mb-2 text-white">Belum ada data level kelas</p>
                            <p class="font-medium text-white">Silakan tambahkan level kelas baru untuk mulai mengelola SPP.</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endsection