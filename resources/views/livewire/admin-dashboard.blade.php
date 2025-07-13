<div>
    <div class="mt-6">
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
            <div class="bg-blue-100 p-4 rounded-lg shadow">
                <h3 class="font-bold text-lg text-blue-800">Total Santri</h3>
                <p class="text-3xl font-bold">{{ $totalUsers }}</p>
            </div>
            <div class="bg-green-100 p-4 rounded-lg shadow">
                <h3 class="font-bold text-lg text-green-800">Total Guru</h3>
                <p class="text-3xl font-bold">{{ $totalGuru }}</p>
            </div>
            <div class="bg-yellow-100 p-4 rounded-lg shadow">
                <h3 class="font-bold text-lg text-yellow-800">Pembayaran Belum Lunas</h3>
                <p class="text-3xl font-bold">{{ $pendingPayments }}</p>
                <div class="mt-2 text-right">
                    <a href="{{ route('pembayaran.index', ['status' => 'belum_lunas']) }}" class="text-yellow-800 hover:underline text-sm">Lihat semua →</a>
                </div>
            </div>
            <div class="bg-purple-100 p-4 rounded-lg shadow">
                <h3 class="font-bold text-lg text-purple-800">Pembayaran Lunas</h3>
                <p class="text-3xl font-bold">{{ $confirmedPayments }}</p>
                <div class="mt-2 text-right">
                    <a href="{{ route('pembayaran.index', ['status' => 'lunas']) }}" class="text-purple-800 hover:underline text-sm">Lihat semua →</a>
                </div>
            </div>
            <div class="bg-red-100 p-4 rounded-lg shadow">
                <h3 class="font-bold text-lg text-red-800">Total Belum Bayar</h3>
                <p class="text-3xl font-bold">{{ $totalUnpaid }}</p>
                <div class="mt-2 text-right">
                    <a href="{{ route('pembayaran.index', ['status' => 'belum_bayar']) }}" class="text-red-800 hover:underline text-sm">Lihat semua →</a>
                </div>
            </div>
            <div class="bg-indigo-100 p-4 rounded-lg shadow">
                <h3 class="font-bold text-lg text-indigo-800">Total Kelas</h3>
                <p class="text-3xl font-bold">{{ $totalClasses }}</p>
                <div class="mt-2 text-right">
                    <a href="{{ route('admin.kelas.index') }}" class="text-indigo-800 hover:underline text-sm">Lihat semua →</a>
                </div>
            </div>
            <div class="bg-pink-100 p-4 rounded-lg shadow">
                <h3 class="font-bold text-lg text-pink-800">Total Berita</h3>
                <p class="text-3xl font-bold">{{ $totalNews }}</p>
                <div class="mt-2 text-right">
                    <a href="{{ route('berita.index') }}" class="text-pink-800 hover:underline text-sm">Lihat semua →</a>
                </div>
            </div>
        </div>

        <!-- Chart.js Charts -->
        <div class="grid grid-cols-1 gap-6 mt-6" wire:ignore>
            <!-- Lunas Payment Chart -->
            <div class="bg-white p-6 rounded-lg shadow-lg border border-blue-100">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="font-semibold text-xl text-blue-800">Grafik Pembayaran Lunas Berdasarkan Periode</h3>
                    <div>
                        <select id="lunasYearFilter" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 mr-5 cursor-pointer">
                            @foreach($availableYears as $year)
                                <option value="{{ $year }}" {{ $year == $currentYear ? 'selected' : '' }} class="cursor-pointer">
                                    {{ $year }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="h-80">
                    <canvas id="lunasPaymentChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Reinitialize charts when Livewire component updates
        document.addEventListener('livewire:initialized', () => {
            @this.on('refreshCharts', () => {
                if (window.initDashboardCharts) {
                    window.initDashboardCharts();
                }
            });
        });
    </script>
    @endpush
</div>
