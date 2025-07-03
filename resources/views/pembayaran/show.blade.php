<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detail Pembayaran') }}
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
                    
                    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                        <div class="px-4 py-5 sm:px-6">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">Informasi Pembayaran</h3>
                            <p class="mt-1 max-w-2xl text-sm text-gray-500">Detail transaksi pembayaran.</p>
                        </div>
                        <div class="border-t border-gray-200">
                            <dl>
                                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                    <dt class="text-sm font-medium text-gray-500">Nama Santri</dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $pembayaran->user->nama_santri }}</dd>
                                </div>
                                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                    <dt class="text-sm font-medium text-gray-500">NIS</dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $pembayaran->user->nis }}</dd>
                                </div>
                                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                    <dt class="text-sm font-medium text-gray-500">Kelas</dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $pembayaran->user->classLevel->level }}</dd>
                                </div>
                                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                    <dt class="text-sm font-medium text-gray-500">Bulan Pembayaran</dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
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
                                    </dd>
                                </div>
                                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                    <dt class="text-sm font-medium text-gray-500">Total SPP</dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">Rp {{ number_format($pembayaran->total_tagihan, 0, ',', '.') }}</dd>
                                </div>
                                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                    <dt class="text-sm font-medium text-gray-500">Total Dibayar</dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                        @if($pembayaran->is_cicilan)
                                            @php
                                                $totalPaid = $pembayaran->detailPembayaran->sum('jumlah_dibayar');
                                            @endphp
                                            Rp {{ number_format($totalPaid, 0, ',', '.') }}
                                        @else
                                            @php
                                                $firstPayment = $pembayaran->detailPembayaran->first();
                                                $totalPaid = $firstPayment ? $firstPayment->jumlah_dibayar : 0;
                                            @endphp
                                            Rp {{ number_format($totalPaid, 0, ',', '.') }}
                                        @endif
                                    </dd>
                                </div>
                                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                    <dt class="text-sm font-medium text-gray-500">Sisa Pembayaran</dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                        @if($pembayaran->is_cicilan)
                                            @php
                                                $totalPaid = $pembayaran->detailPembayaran->sum('jumlah_dibayar');
                                                $remaining = max(0, $pembayaran->total_tagihan - $totalPaid);
                                            @endphp
                                            <span class="{{ $remaining > 0 ? 'text-red-600' : 'text-green-600' }} font-medium">
                                                Rp {{ number_format($remaining, 0, ',', '.') }}
                                            </span>
                                        @else
                                            @php
                                                $firstPayment = $pembayaran->detailPembayaran->first();
                                                $totalPaid = $firstPayment ? $firstPayment->jumlah_dibayar : 0;
                                                $remaining = max(0, $pembayaran->total_tagihan - $totalPaid);
                                            @endphp
                                            <span class="{{ $remaining > 0 ? 'text-red-600' : 'text-green-600' }} font-medium">
                                                Rp {{ number_format($remaining, 0, ',', '.') }}
                                            </span>
                                        @endif
                                    </dd>
                                </div>
                                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                    <dt class="text-sm font-medium text-gray-500">Metode Pembayaran</dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                        @if($pembayaran->detailPembayaran->isNotEmpty())
                                            {{ $pembayaran->detailPembayaran->first()->metode_pembayaran }}
                                        @else
                                            -
                                        @endif
                                    </dd>
                                </div>
                                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                    <dt class="text-sm font-medium text-gray-500">Tanggal Pembayaran</dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                        @if($pembayaran->detailPembayaran->isNotEmpty())
                                            {{ \Carbon\Carbon::parse($pembayaran->detailPembayaran->first()->tanggal_bayar)->format('d F Y') }}
                                        @else
                                            -
                                        @endif
                                    </dd>
                                </div>
                                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                    <dt class="text-sm font-medium text-gray-500">Status</dt>
                                    <dd class="mt-1 text-sm sm:mt-0 sm:col-span-2">
                                        @if($pembayaran->status == 'confirmed')
                                            <span class="px-2 py-1 text-xs text-white bg-green-500 rounded">Dikonfirmasi</span>
                                        @elseif($pembayaran->status == 'pending')
                                            <span class="px-2 py-1 text-xs text-white bg-yellow-500 rounded">Menunggu</span>
                                        @elseif($pembayaran->status == 'lunas')
                                            <span class="px-2 py-1 text-xs text-white bg-green-600 rounded">Lunas</span>
                                        @elseif($pembayaran->status == 'belum_lunas')
                                            <span class="px-2 py-1 text-xs text-white bg-yellow-600 rounded">Belum Lunas</span>
                                        @elseif($pembayaran->status == 'belum_bayar')
                                            <span class="px-2 py-1 text-xs text-white bg-red-600 rounded">Belum Bayar</span>
                                        @else
                                            <span class="px-2 py-1 text-xs text-white bg-red-500 rounded">Ditolak</span>
                                        @endif
                                        @if($pembayaran->is_cicilan)
                                            <span class="px-2 py-1 text-xs bg-blue-100 text-blue-800 rounded ml-1">Cicilan</span>
                                        @endif
                                    </dd>
                                </div>
                                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                    <dt class="text-sm font-medium text-gray-500">Waktu Dibuat</dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $pembayaran->created_at->format('d F Y H:i') }}</dd>
                                </div>
                                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                    <dt class="text-sm font-medium text-gray-500">Terakhir Diupdate</dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $pembayaran->updated_at->format('d F Y H:i') }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>
                    
                    <!-- Tambahkan link ke halaman cicilan -->
                    <div class="mt-6 mb-6">
                        <a href="{{ route('pembayaran.installment.show', $pembayaran->id) }}" class="px-4 py-2 bg-purple-500 text-white rounded hover:bg-purple-700">
                            Kelola Pembayaran Cicilan
                        </a>
                    </div>
                    
                    <div class="mt-6 flex space-x-4">
                        <a href="{{ route('pembayaran.edit', $pembayaran->id) }}" class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-700">
                            Edit Data
                        </a>
                        <form action="{{ route('pembayaran.destroy', $pembayaran->id) }}" method="POST" 
                              onsubmit="return confirm('Apakah Anda yakin ingin menghapus data pembayaran ini?');">
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