<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Pembayaran Cicilan') }}
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
                    
                    @if(session('success'))
                        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif
                    
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Detail Tagihan</h3>
                    <div class="bg-gray-50 p-4 mb-6 rounded-md border">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p><span class="font-medium">Nama Santri:</span> {{ $pembayaran->user->name }}</p>
                                <p><span class="font-medium">NIS:</span> {{ $pembayaran->user->nis }}</p>
                                <p><span class="font-medium">Periode:</span> 
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
                                    {{ $bulanName }}
                                </p>
                            </div>
                            <div>
                                <p><span class="font-medium">Total Tagihan:</span> Rp {{ number_format($pembayaran->total_tagihan, 0, ',', '.') }}</p>
                                <p><span class="font-medium">Total Dibayar:</span> Rp {{ number_format($totalDibayar, 0, ',', '.') }}</p>
                                <p><span class="font-medium">Sisa Tagihan:</span> <span class="font-bold {{ $sisa_tagihan <= 0 ? 'text-green-600' : 'text-red-600' }}">Rp {{ number_format($sisa_tagihan, 0, ',', '.') }}</span></p>
                                <p><span class="font-medium">Status:</span> 
                                    @if($pembayaran->status == 'lunas')
                                        <span class="px-2 py-1 text-xs text-white bg-green-500 rounded">Lunas</span>
                                    @elseif($pembayaran->status == 'belum_lunas')
                                        <span class="px-2 py-1 text-xs text-white bg-yellow-500 rounded">Belum Lunas</span>
                                    @elseif($pembayaran->status == 'belum_bayar')
                                        <span class="px-2 py-1 text-xs text-white bg-red-500 rounded">Belum Bayar</span>
                                    @else
                                        <span class="px-2 py-1 text-xs text-white bg-gray-500 rounded">{{ $pembayaran->status }}</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Riwayat Pembayaran Cicilan -->
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Riwayat Cicilan</h3>
                    <div class="overflow-x-auto mb-8">
                        <table class="w-full text-sm text-left text-gray-500">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-100">
                                <tr>
                                    <th scope="col" class="px-6 py-3">Tanggal</th>
                                    <th scope="col" class="px-6 py-3">Jumlah</th>
                                    <th scope="col" class="px-6 py-3">Metode</th>
                                    <th scope="col" class="px-6 py-3">Bukti</th>
                                    <th scope="col" class="px-6 py-3">Pencatat</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($detailPembayaran as $detail)
                                    <tr class="bg-white border-b">
                                        <td class="px-6 py-4">{{ \Carbon\Carbon::parse($detail->tanggal_bayar)->format('d M Y H:i') }}</td>
                                        <td class="px-6 py-4">Rp {{ number_format($detail->jumlah_dibayar, 0, ',', '.') }}</td>
                                        <td class="px-6 py-4">{{ $detail->metode_pembayaran }}</td>
                                        <td class="px-6 py-4">
                                            @if($detail->bukti_pembayaran)
                                                <a href="{{ asset('storage/' . $detail->bukti_pembayaran) }}" target="_blank" class="text-blue-600 hover:underline">Lihat Bukti</a>
                                            @else
                                                <span class="text-gray-400">-</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">{{ $detail->adminPencatat->name ?? 'Sistem' }}</td>
                                    </tr>
                                @empty
                                    <tr class="bg-white border-b">
                                        <td colspan="5" class="px-6 py-4 text-center">Belum ada pembayaran cicilan</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($sisa_tagihan > 0)
                    <!-- Form Tambah Cicilan -->
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Tambah Pembayaran Cicilan</h3>
                    
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

                    <form method="POST" action="{{ route('pembayaran.installment.store') }}" class="bg-gray-50 p-6 rounded-md border">
                        @csrf
                        <input type="hidden" name="pembayaran_id" value="{{ $pembayaran->id }}">
                        
                        <div class="mb-4">
                            <label for="jumlah_dibayar" class="block text-sm font-medium text-gray-700">Jumlah Pembayaran (Rp)</label>
                            <input type="number" id="jumlah_dibayar" name="jumlah_dibayar" value="{{ old('jumlah_dibayar', $sisa_tagihan) }}" max="{{ $sisa_tagihan }}" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            <p class="text-sm text-gray-500 mt-1">Maksimal pembayaran: Rp {{ number_format($sisa_tagihan, 0, ',', '.') }}</p>
                        </div>
                        
                        <div class="mb-4">
                            <label for="metode_pembayaran" class="block text-sm font-medium text-gray-700">Metode Pembayaran</label>
                            <input type="text" id="metode_pembayaran" name="metode_pembayaran" value="Tunai" class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md bg-gray-100" readonly>
                            <p class="mt-1 text-xs text-gray-500">Pembayaran hanya dapat menggunakan metode Tunai</p>
                        </div>
                        
                        <div class="mt-6">
                            <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-700">
                                Simpan Pembayaran Cicilan
                            </button>
                        </div>
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>