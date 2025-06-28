<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detail Guru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="mb-4">
                        <a href="{{ route('guru.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-700">
                            &laquo; Kembali
                        </a>
                    </div>
                    
                    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                        <div class="px-4 py-5 sm:px-6">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">Informasi Guru</h3>
                            <p class="mt-1 max-w-2xl text-sm text-gray-500">Detail data personal dan akademik.</p>
                        </div>
                        <div class="border-t border-gray-200">
                            <dl>
                                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                    <dt class="text-sm font-medium text-gray-500">Nama Lengkap</dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $user->name }}</dd>
                                </div>
                                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                    <dt class="text-sm font-medium text-gray-500">Email</dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $user->email }}</dd>
                                </div>
                                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                    <dt class="text-sm font-medium text-gray-500">Nomor Induk Pengajar (NIP)</dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $user->nis }}</dd>
                                </div>
                                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                    <dt class="text-sm font-medium text-gray-500">Alamat</dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $user->alamat ?? '-' }}</dd>
                                </div>
                                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                    <dt class="text-sm font-medium text-gray-500">Nomor HP</dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $user->no_hp ?? '-' }}</dd>
                                </div>
                                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                    <dt class="text-sm font-medium text-gray-500">Bidang/Mata Pelajaran</dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                        {{ App\Models\Guru::where('user_id', $user->id)->first()->bidang ?? '-' }}
                                    </dd>
                                </div>
                                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                    <dt class="text-sm font-medium text-gray-500">Tanggal Bergabung</dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $user->created_at->format('d F Y') }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>
                    
                    <div class="mt-6 flex space-x-4">
                        <a href="{{ route('guru.edit', $user->id) }}" class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-700">
                            Edit Data
                        </a>
                        <form action="{{ route('guru.destroy', $user->id) }}" method="POST" 
                              onsubmit="return confirm('Apakah Anda yakin ingin menghapus data guru ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-700">
                                Hapus Data
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>