<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Riwayat Pembayaran') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h2 class="text-xl font-bold mb-4">Riwayat Pembayaran Anda</h2>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-500">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-100">
                                <tr>
                                    <th scope="col" class="px-6 py-3">Bulan</th>
                                    <th scope="col" class="px-6 py-3">Total SPP</th>
                                    <th scope="col" class="px-6 py-3">Dibayar</th>
                                    <th scope="col" class="px-6 py-3">Sisa</th>
                                    <th scope="col" class="px-6 py-3">Metode</th>
                                    <th scope="col" class="px-6 py-3">Tanggal</th>
                                    <th scope="col" class="px-6 py-3">Status</th>
                                    <th scope="col" class="px-6 py-3">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pembayarans as $pembayaran)
                                    <tr class="bg-white border-b">
                                        <td class="px-6 py-4">{{ $pembayaran->bulan }}</td>
                                        <td class="px-6 py-4">Rp {{ number_format($pembayaran->user->spp_bulanan, 0, ',', '.') }}</td>
                                        <td class="px-6 py-4">
                                            @if($pembayaran->is_cicilan)
                                                @php
                                                    $totalPaid = $pembayaran->detailPembayaran->sum('jumlah_dibayar');
                                                @endphp
                                                Rp {{ number_format($totalPaid, 0, ',', '.') }}
                                            @else
                                                Rp {{ number_format($pembayaran->jumlah, 0, ',', '.') }}
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            @if($pembayaran->is_cicilan)
                                                @php
                                                    $totalPaid = $pembayaran->detailPembayaran->sum('jumlah_dibayar');
                                                    $remaining = max(0, $pembayaran->user->spp_bulanan - $totalPaid);
                                                @endphp
                                                <span class="{{ $remaining > 0 ? 'text-red-600' : 'text-green-600' }} font-medium">
                                                    Rp {{ number_format($remaining, 0, ',', '.') }}
                                                </span>
                                            @else
                                                @php
                                                    $remaining = max(0, $pembayaran->user->spp_bulanan - $pembayaran->jumlah);
                                                @endphp
                                                <span class="{{ $remaining > 0 ? 'text-red-600' : 'text-green-600' }} font-medium">
                                                    Rp {{ number_format($remaining, 0, ',', '.') }}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">{{ $pembayaran->metode_pembayaran }}</td>
                                        <td class="px-6 py-4">{{ \Carbon\Carbon::parse($pembayaran->tanggal)->format('d M Y') }}</td>
                                        <td class="px-6 py-4">
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
                                        </td>
                                        <td class="px-6 py-4">
                                            <a href="{{ route('pembayaran.installment.show', $pembayaran->id) }}" class="font-medium text-purple-600 hover:underline">Bayar Cicilan</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr class="bg-white border-b">
                                        <td colspan="8" class="px-6 py-4 text-center">Tidak ada riwayat pembayaran</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-6">
                        <div class="bg-gray-50 p-4 rounded-lg border">
                            <h3 class="font-bold mb-2">Informasi Pembayaran:</h3>
                            <p>Pembayaran bulanan dapat dilakukan melalui:</p>
                            <ul class="list-disc ml-5 mt-2">
                                <li>Transfer Bank: 1234567890 (Bank BCA) a.n. Pesantren Nurul Ulum</li>
                                <li>QRIS: Tersedia di kantor administrasi</li>
                                <li>Tunai: Silahkan datang ke kantor administrasi pada jam kerja (08.00 - 15.00 WIB)</li>
                            </ul>
                            <p class="mt-2">Harap konfirmasi pembayaran dengan mengirim bukti transfer ke nomor WhatsApp admin: 081234567890</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>