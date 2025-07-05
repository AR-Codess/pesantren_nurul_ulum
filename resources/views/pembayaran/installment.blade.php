<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Pembayaran Cicilan') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <!-- Back button -->
                    <div class="mb-6">
                        <a href="{{ route('pembayaran.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700">
                            « Kembali
                        </a>
                    </div>
                    
                    @if(session('success'))
                        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif
                    
                    <!-- Information heading -->
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Informasi Pembayaran</h3>
                    
                    @php
                        $bulanNames = [
                            '01' => 'Januari',
                            '02' => 'Februari',
                            '03' => 'Maret',
                            '04' => 'April',
                            '05' => 'Mei',
                            '06' => 'Juni',
                            '07' => 'Juli',
                            '08' => 'Agustus',
                            '09' => 'September',
                            '10' => 'Oktober',
                            '11' => 'November',
                            '12' => 'Desember'
                        ];
                        $bulanNum = \Carbon\Carbon::parse($pembayaran->periode_pembayaran)->format('m');
                        $bulanName = $bulanNames[$bulanNum] ?? 'Unknown';
                    @endphp
                    
                    <!-- Payment Summary Card -->
                    <div class="bg-white border rounded-lg shadow-sm mb-8">
                        <div class="grid md:grid-cols-2 gap-0">
                            <!-- Left side - Student info -->
                            <div class="p-4 border-b md:border-b-0 md:border-r">
                                <p class="mb-2 text-sm">
                                    <span class="text-gray-600">Nama Santri:</span>
                                    <span class="font-medium">{{ $pembayaran->user->nama_santri }}</span>
                                </p>
                                <p class="mb-2 text-sm">
                                    <span class="text-gray-600">NIS:</span>
                                    <span class="font-medium">{{ $pembayaran->user->nis }}</span>
                                </p>
                                <p class="mb-2 text-sm">
                                    <span class="text-gray-600">Kelas:</span>
                                    <span class="font-medium">{{ $pembayaran->user->classLevel->level }}</span>
                                </p>
                                <p class="mb-2 text-sm">
                                    <span class="text-gray-600">Bulan:</span>
                                    <span class="font-medium">{{ $bulanName }}</span>
                                </p>
                                <p class="text-sm">
                                    <span class="text-gray-600">Beasiswa:</span>
                                    <span class="font-medium">
                                        @if($pembayaran->user->is_beasiswa)
                                            <span class="px-2 py-1 text-xs text-white bg-green-500 rounded">Ya</span>
                                        @else
                                            <span class="px-2 py-1 text-xs text-white bg-gray-500 rounded">Tidak</span>
                                        @endif
                                    </span>
                                </p>
                            </div>
                            
                            <!-- Right side - Payment info -->
                            <div class="p-4">
                                <p class="mb-2 flex justify-between text-sm">
                                    <span>Total Tagihan:</span>
                                    <span class="font-medium">Rp {{ number_format($pembayaran->total_tagihan, 0, ',', '.') }}</span>
                                </p>
                                <p class="mb-2 flex justify-between text-sm">
                                    <span>Total Dibayar:</span>
                                    <span class="font-medium">Rp {{ number_format($totalDibayar, 0, ',', '.') }}</span>
                                </p>
                                <p class="mb-2 flex justify-between text-sm">
                                    <span>Sisa Tagihan:</span>
                                    <span class="font-medium {{ $sisa_tagihan <= 0 ? 'text-green-500' : 'text-red-500' }}">
                                        Rp {{ number_format($sisa_tagihan, 0, ',', '.') }}
                                    </span>
                                </p>
                                <p class="flex justify-between text-sm">
                                    <span>Status:</span>
                                    <span>
                                        @if($pembayaran->status == 'lunas')
                                            <span class="px-2 py-1 text-xs text-white bg-green-500 rounded">Lunas</span>
                                        @elseif($pembayaran->status == 'belum_lunas')
                                            <span class="px-2 py-1 text-xs text-white bg-yellow-500 rounded">Belum Lunas</span>
                                        @elseif($pembayaran->status == 'belum_bayar')
                                            <span class="px-2 py-1 text-xs text-white bg-red-500 rounded">Belum Bayar</span>
                                        @else
                                            <span class="px-2 py-1 text-xs text-white bg-gray-500 rounded">{{ $pembayaran->status }}</span>
                                        @endif
                                        
                                        @if($pembayaran->is_cicilan)
                                            <span class="px-2 py-1 text-xs bg-blue-100 text-blue-800 rounded ml-1">Cicilan</span>
                                        @endif
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Riwayat Pembayaran Cicilan -->
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Riwayat Pembayaran</h3>
                    <div class="bg-white border rounded-lg shadow-sm overflow-hidden mb-6">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">TANGGAL</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">JUMLAH</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">METODE</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">BUKTI</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">PENCATAT</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse($detailPembayaran as $detail)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ \Carbon\Carbon::parse($detail->tanggal_bayar)->format('d M Y') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Rp {{ number_format($detail->jumlah_dibayar, 0, ',', '.') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $detail->metode_pembayaran }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                @if($detail->bukti_pembayaran)
                                                    <a href="{{ asset('storage/' . $detail->bukti_pembayaran) }}" target="_blank" class="text-blue-600 hover:underline">Lihat Bukti</a>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $detail->adminPencatat->name ?? 'Admin Pesantren' }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">Belum ada pembayaran cicilan</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    @if($sisa_tagihan > 0)
                    <!-- Form Tambah Cicilan -->
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Tambah Pembayaran Cicilan</h3>
                    
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

                    <div class="bg-white border rounded-lg shadow-sm overflow-hidden mb-6">
                        <div class="p-6">
                            <form method="POST" action="{{ route('pembayaran.installment.store') }}">
                                @csrf
                                <input type="hidden" name="pembayaran_id" value="{{ $pembayaran->id }}">
                                
                                <div class="mb-4">
                                    <label for="jumlah_dibayar" class="block text-sm font-medium text-gray-700">Jumlah Pembayaran (Rp)</label>
                                    <input type="number" id="jumlah_dibayar" name="jumlah_dibayar" value="{{ old('jumlah_dibayar', $sisa_tagihan) }}" max="{{ $sisa_tagihan }}" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                    <p class="text-sm text-gray-500 mt-1">Maksimal pembayaran: Rp {{ number_format($sisa_tagihan, 0, ',', '.') }}</p>
                                </div>
                                
                                <div class="mb-4">
                                    <label for="metode_pembayaran" class="block text-sm font-medium text-gray-700">Metode Pembayaran</label>
                                    <select id="metode_pembayaran" name="metode_pembayaran" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                        <option value="Tunai">Tunai</option>
                                        <option value="Transfer">Transfer</option>
                                    </select>
                                </div>
                                
                                <div class="mt-6">
                                    <button type="submit" class="inline-flex justify-center items-center px-4 py-2 bg-indigo-500 text-white rounded hover:bg-indigo-600 transition duration-150">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd" />
                                        </svg>
                                        Simpan Pembayaran Cicilan
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>