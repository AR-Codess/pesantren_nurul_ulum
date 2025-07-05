<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Informasi Pembayaran') }}
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
                        
                        // Calculate payment details
                        if($pembayaran->is_cicilan) {
                            $totalPaid = $pembayaran->detailPembayaran->sum('jumlah_dibayar');
                        } else {
                            $totalPaid = $pembayaran->detailPembayaran->sum('jumlah_dibayar');
                        }
                        $remaining = max(0, $pembayaran->total_tagihan - $totalPaid);
                        $isLunas = $pembayaran->status == 'lunas' || $remaining <= 0;
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
                                    <span class="font-medium">Rp {{ number_format($totalPaid, 0, ',', '.') }}</span>
                                </p>
                                <p class="mb-2 flex justify-between text-sm">
                                    <span>Sisa Tagihan:</span>
                                    <span class="font-medium {{ $remaining <= 0 ? 'text-green-500' : 'text-red-500' }}">
                                        Rp {{ number_format($remaining, 0, ',', '.') }}
                                    </span>
                                </p>
                                <p class="flex justify-between text-sm">
                                    <span>Status:</span>
                                    <span>
                                        @if($isLunas)
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
                    
                    <!-- Payment History Section - Show for both regular and installment payments -->
                    @if($pembayaran->detailPembayaran->isNotEmpty())
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
                                        @foreach($pembayaran->detailPembayaran as $detail)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ \Carbon\Carbon::parse($detail->tanggal_bayar)->format('d M Y') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Rp {{ number_format($detail->jumlah_dibayar, 0, ',', '.') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $detail->metode_pembayaran }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                               
                                            <!-- Action buttons section -->
                                            <div class="flex flex-wrap">
                                                <!-- Download Invoice Button -->
                                                <a href="{{ route('invoice.download', $pembayaran->id) }}" 
                                                class="inline-flex justify-center items-center px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600 transition duration-150">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0015.414 6L12 2.586A2 2 0 0010.586 2H6zm5 6a1 1 0 10-2 0v3.586l-1.293-1.293a1 1 0 10-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 11.586V8z" clip-rule="evenodd" />
                                                    </svg>
                                                    Download Invoice
                                                </a>
                                            </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $detail->adminPencatat->name ?? 'Admin Pesantren' }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif
                    
                    <!-- Action buttons - Hidden when payment is fully paid -->
                    @if(!$isLunas && $pembayaran->is_cicilan)
                    <div class="flex flex-wrap gap-2">
                        <a href="{{ route('pembayaran.installment.show', $pembayaran->id) }}" 
                           class="inline-flex justify-center items-center px-4 py-2 bg-indigo-500 text-white rounded hover:bg-indigo-600 transition duration-150">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            Kelola Pembayaran Cicilan
                        </a>
                        
                        <form action="{{ route('pembayaran.destroy', $pembayaran->id) }}" method="POST" 
                              onsubmit="return confirm('Apakah Anda yakin ingin menghapus data pembayaran ini?');" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="inline-flex justify-center items-center px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600 transition duration-150">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                                Hapus Data
                            </button>
                        </form>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>