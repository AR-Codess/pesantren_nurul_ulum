<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Laporan Keuangan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="mb-6">
                        <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-700">
                            &laquo; Kembali ke Dashboard
                        </a>
                    </div>

                    <h1 class="text-2xl font-bold mb-6">Laporan Keuangan Pesantren Nurul Ulum</h1>

                    <!-- Period selector -->
                    <div class="mb-6 bg-gray-50 p-4 rounded border">
                        <form action="{{ route('admin.financial-report') }}" method="GET" class="flex flex-col md:flex-row md:items-end space-y-2 md:space-y-0 md:space-x-4">
                            <div>
                                <label for="period" class="block text-sm font-medium text-gray-700 mb-1">Jenis Laporan</label>
                                <select name="period" id="period" class="rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" onchange="toggleMonthField(this.value)">
                                    <option value="monthly" {{ $period == 'monthly' ? 'selected' : '' }}>Bulanan</option>
                                    <option value="yearly" {{ $period == 'yearly' ? 'selected' : '' }}>Tahunan</option>
                                </select>
                            </div>
                            
                            <div>
                                <label for="year" class="block text-sm font-medium text-gray-700 mb-1">Tahun</label>
                                <select name="year" id="year" class="rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                                    @for($y = date('Y'); $y >= date('Y') - 5; $y--)
                                        <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                                    @endfor
                                </select>
                            </div>
                            
                            <div id="month-selector" style="{{ $period == 'yearly' ? 'display: none;' : '' }}">
                                <label for="month" class="block text-sm font-medium text-gray-700 mb-1">Bulan</label>
                                <select name="month" id="month" class="rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                                    @for($m = 1; $m <= 12; $m++)
                                        <option value="{{ $m }}" {{ isset($month) && $month == $m ? 'selected' : '' }}>{{ date('F', mktime(0, 0, 0, $m, 1)) }}</option>
                                    @endfor
                                </select>
                            </div>
                            
                            <div>
                                <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-700">Tampilkan</button>
                            </div>
                        </form>
                    </div>

                    <!-- Financial report content -->
                    <div class="mt-8">
                        @if($period == 'monthly')
                            <div class="flex justify-between items-center mb-4">
                                <h2 class="text-xl font-bold">Laporan Bulanan: {{ date('F Y', mktime(0, 0, 0, $month, 1, $year)) }}</h2>
                                <div class="text-right">
                                    <div class="text-sm text-gray-600">Total Pembayaran Dikonfirmasi:</div>
                                    <div class="text-2xl font-bold text-green-600">Rp {{ number_format($totalAmount, 0, ',', '.') }}</div>
                                </div>
                            </div>
                            
                            <div class="overflow-x-auto">
                                <table class="w-full text-sm text-left text-gray-500">
                                    <thead class="text-xs text-gray-700 uppercase bg-gray-100">
                                        <tr>
                                            <th scope="col" class="px-6 py-3">ID</th>
                                            <th scope="col" class="px-6 py-3">Nama</th>
                                            <th scope="col" class="px-6 py-3">Tanggal</th>
                                            <th scope="col" class="px-6 py-3">Bulan</th>
                                            <th scope="col" class="px-6 py-3">Jumlah</th>
                                            <th scope="col" class="px-6 py-3">Status</th>
                                            <th scope="col" class="px-6 py-3">Catatan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($payments as $payment)
                                            <tr class="bg-white border-b hover:bg-gray-50">
                                                <td class="px-6 py-4">{{ $payment->id }}</td>
                                                <td class="px-6 py-4">{{ $payment->user->name ?? 'User tidak ditemukan' }}</td>
                                                <td class="px-6 py-4">{{ \Carbon\Carbon::parse($payment->tanggal)->format('d M Y') }}</td>
                                                <td class="px-6 py-4">{{ $payment->bulan }}</td>
                                                <td class="px-6 py-4">Rp {{ number_format($payment->jumlah, 0, ',', '.') }}</td>
                                                <td class="px-6 py-4">
                                                    @if($payment->status == 'confirmed')
                                                        <span class="px-2 py-1 text-xs text-white bg-green-500 rounded-full">Dikonfirmasi</span>
                                                    @elseif($payment->status == 'pending')
                                                        <span class="px-2 py-1 text-xs text-white bg-yellow-500 rounded-full">Pending</span>
                                                    @else
                                                        <span class="px-2 py-1 text-xs text-white bg-red-500 rounded-full">Ditolak</span>
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4">{{ $payment->catatan ?? '-' }}</td>
                                            </tr>
                                        @empty
                                            <tr class="bg-white border-b">
                                                <td colspan="7" class="px-6 py-4 text-center">Tidak ada data pembayaran untuk bulan ini</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            
                            <div class="mt-6 bg-gray-50 p-4 rounded border">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <h3 class="font-bold text-lg mb-2">Ringkasan Pembayaran</h3>
                                        <div class="flex justify-between border-b py-2">
                                            <span>Total Pembayaran Dikonfirmasi:</span>
                                            <span class="font-bold">Rp {{ number_format($totalAmount, 0, ',', '.') }}</span>
                                        </div>
                                        <div class="flex justify-between border-b py-2">
                                            <span>Total Pembayaran Pending:</span>
                                            <span class="font-bold">Rp {{ number_format($pendingAmount, 0, ',', '.') }}</span>
                                        </div>
                                        <div class="flex justify-between py-2">
                                            <span>Jumlah Transaksi:</span>
                                            <span class="font-bold">{{ $payments->count() }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                        @else
                            <div class="flex justify-between items-center mb-4">
                                <h2 class="text-xl font-bold">Laporan Tahunan: {{ $year }}</h2>
                                <div class="text-right">
                                    <div class="text-sm text-gray-600">Total Pembayaran Dikonfirmasi:</div>
                                    <div class="text-2xl font-bold text-green-600">Rp {{ number_format($totalYearlyAmount, 0, ',', '.') }}</div>
                                </div>
                            </div>
                            
                            <div class="bg-white p-4 border rounded-lg shadow-sm mb-6">
                                <h3 class="font-bold text-lg mb-4">Pembayaran Bulanan ({{ $year }})</h3>
                                <div class="h-64">
                                    <canvas id="monthlyChart"></canvas>
                                </div>
                            </div>
                            
                            <div class="overflow-x-auto">
                                <table class="w-full text-sm text-left text-gray-500">
                                    <thead class="text-xs text-gray-700 uppercase bg-gray-100">
                                        <tr>
                                            <th scope="col" class="px-6 py-3">Bulan</th>
                                            <th scope="col" class="px-6 py-3">Total Pembayaran</th>
                                            <th scope="col" class="px-6 py-3">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @for($m = 1; $m <= 12; $m++)
                                            @php 
                                                $monthData = $monthlyData->where('month', $m)->first();
                                                $amount = $monthData ? $monthData->confirmed_amount : 0;
                                            @endphp
                                            <tr class="bg-white border-b hover:bg-gray-50">
                                                <td class="px-6 py-4">{{ date('F', mktime(0, 0, 0, $m, 1)) }}</td>
                                                <td class="px-6 py-4">Rp {{ number_format($amount, 0, ',', '.') }}</td>
                                                <td class="px-6 py-4">
                                                    <a href="{{ route('admin.financial-report', ['period' => 'monthly', 'year' => $year, 'month' => $m]) }}" class="text-blue-600 hover:underline">Lihat Detail</a>
                                                </td>
                                            </tr>
                                        @endfor
                                    </tbody>
                                    <tfoot class="bg-gray-50 font-medium">
                                        <tr>
                                            <td class="px-6 py-4">Total Keseluruhan</td>
                                            <td class="px-6 py-4">Rp {{ number_format($totalYearlyAmount, 0, ',', '.') }}</td>
                                            <td class="px-6 py-4"></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleMonthField(period) {
            const monthSelector = document.getElementById('month-selector');
            if (period === 'yearly') {
                monthSelector.style.display = 'none';
            } else {
                monthSelector.style.display = 'block';
            }
        }
        
        @if($period == 'yearly')
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('monthlyChart').getContext('2d');
            
            // Prepare data for chart
            const months = [
                'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
            ];
            
            const data = Array(12).fill(0);
            
            @foreach($monthlyData as $item)
                data[{{ $item->month - 1 }}] = {{ $item->confirmed_amount }};
            @endforeach
            
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: months,
                    datasets: [{
                        label: 'Pembayaran Dikonfirmasi (Rp)',
                        data: data,
                        backgroundColor: 'rgba(59, 130, 246, 0.5)',
                        borderColor: 'rgb(59, 130, 246)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return 'Rp ' + value.toLocaleString('id-ID');
                                }
                            }
                        }
                    }
                }
            });
        });
        @endif
    </script>

    <!-- Include Chart.js if yearly report is shown -->
    @if($period == 'yearly')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @endif
</x-app-layout>