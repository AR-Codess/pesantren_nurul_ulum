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
                <h3 class="font-bold text-lg text-yellow-800">Pembayaran Pending</h3>
                <p class="text-3xl font-bold">{{ $pendingPayments }}</p>
            </div>
            <div class="bg-purple-100 p-4 rounded-lg shadow">
                <h3 class="font-bold text-lg text-purple-800">Pembayaran Dikonfirmasi</h3>
                <p class="text-3xl font-bold">{{ $confirmedPayments }}</p>
            </div>
        </div>
</div>
