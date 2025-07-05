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
        </div>
    </div>