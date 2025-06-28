<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Pembayaran Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="mb-4">
                        <a href="{{ route('pembayaran.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-700">
                            &laquo; Kembali
                        </a>
                    </div>
                    
                    @if ($errors->any())
                        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
                            <p class="font-bold">Terjadi kesalahan:</p>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('pembayaran.store') }}">
                        @csrf
                        
                        <div class="mb-4">
                            <label for="user_id" class="block text-sm font-medium text-gray-700">Santri</label>
                            <select id="user_id" name="user_id" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value="">-- Pilih Santri --</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }} ({{ $user->nis }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="mb-4">
                            <label for="bulan" class="block text-sm font-medium text-gray-700">Bulan Pembayaran</label>
                            <select id="bulan" name="bulan" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value="Januari" {{ old('bulan') == 'Januari' ? 'selected' : '' }}>Januari</option>
                                <option value="Februari" {{ old('bulan') == 'Februari' ? 'selected' : '' }}>Februari</option>
                                <option value="Maret" {{ old('bulan') == 'Maret' ? 'selected' : '' }}>Maret</option>
                                <option value="April" {{ old('bulan') == 'April' ? 'selected' : '' }}>April</option>
                                <option value="Mei" {{ old('bulan') == 'Mei' ? 'selected' : '' }}>Mei</option>
                                <option value="Juni" {{ old('bulan') == 'Juni' ? 'selected' : '' }}>Juni</option>
                                <option value="Juli" {{ old('bulan') == 'Juli' ? 'selected' : '' }}>Juli</option>
                                <option value="Agustus" {{ old('bulan') == 'Agustus' ? 'selected' : '' }}>Agustus</option>
                                <option value="September" {{ old('bulan') == 'September' ? 'selected' : '' }}>September</option>
                                <option value="Oktober" {{ old('bulan') == 'Oktober' ? 'selected' : '' }}>Oktober</option>
                                <option value="November" {{ old('bulan') == 'November' ? 'selected' : '' }}>November</option>
                                <option value="Desember" {{ old('bulan') == 'Desember' ? 'selected' : '' }}>Desember</option>
                            </select>
                        </div>
                        
                        <div class="mb-4">
                            <label for="jumlah" class="block text-sm font-medium text-gray-700">Jumlah Pembayaran (Rp)</label>
                            <input type="number" id="jumlah" name="jumlah" value="{{ old('jumlah') }}" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                        </div>
                        
                        <div class="mb-4">
                            <label for="metode_pembayaran" class="block text-sm font-medium text-gray-700">Metode Pembayaran</label>
                            <select id="metode_pembayaran" name="metode_pembayaran" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value="Tunai" {{ old('metode_pembayaran') == 'Tunai' ? 'selected' : '' }}>Tunai</option>
                                <option value="Transfer Bank" {{ old('metode_pembayaran') == 'Transfer Bank' ? 'selected' : '' }}>Transfer Bank</option>
                                <option value="QRIS" {{ old('metode_pembayaran') == 'QRIS' ? 'selected' : '' }}>QRIS</option>
                                <option value="Lainnya" {{ old('metode_pembayaran') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                            </select>
                        </div>
                        
                        <div class="mb-4">
                            <label for="tanggal" class="block text-sm font-medium text-gray-700">Tanggal Pembayaran</label>
                            <input type="date" id="tanggal" name="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                        </div>
                        
                        <div class="mb-4">
                            <label for="status" class="block text-sm font-medium text-gray-700">Status Pembayaran</label>
                            <select id="status" name="status" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Menunggu</option>
                                <option value="confirmed" {{ old('status') == 'confirmed' ? 'selected' : '' }}>Dikonfirmasi</option>
                                <option value="rejected" {{ old('status') == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                            </select>
                        </div>
                        
                        <div class="mt-6">
                            <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-700">
                                Simpan Pembayaran
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>