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
                            <select id="user_id" name="user_id" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" onchange="updateSppInfo(this.value)">
                                <option value="">-- Pilih Santri --</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" data-spp="{{ $user->spp_bulanan }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }} ({{ $user->nis }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div id="spp-info" class="mb-4 p-4 bg-blue-50 border border-blue-200 rounded-md hidden">
                            <h3 class="text-sm font-medium text-blue-800 mb-2">Informasi SPP</h3>
                            <p class="text-sm text-blue-700">Total SPP Bulanan: <span id="total-spp">Rp 0</span></p>
                            <p class="text-sm text-blue-700 mt-1">Status Pembayaran Bulan Ini: <span id="payment-status">-</span></p>
                            <p class="text-sm text-blue-700 mt-1">Sudah Dibayar: <span id="paid-amount">Rp 0</span></p>
                            <p class="text-sm text-blue-700 mt-1">Sisa Pembayaran: <span id="remaining-amount">Rp 0</span></p>
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
                            @if(Auth::guard('admin')->check())
                                <input type="text" id="metode_pembayaran" name="metode_pembayaran" value="Tunai" class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md bg-gray-100" readonly>
                                <p class="mt-1 text-xs text-gray-500">Admin hanya dapat menggunakan metode pembayaran Tunai</p>
                            @else
                                <select id="metode_pembayaran" name="metode_pembayaran" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    <option value="Tunai" {{ old('metode_pembayaran') == 'Tunai' ? 'selected' : '' }}>Tunai</option>
                                    <option value="Transfer Bank" {{ old('metode_pembayaran') == 'Transfer Bank' ? 'selected' : '' }}>Transfer Bank</option>
                                    <option value="QRIS" {{ old('metode_pembayaran') == 'QRIS' ? 'selected' : '' }}>QRIS</option>
                                    <option value="Lainnya" {{ old('metode_pembayaran') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                                </select>
                            @endif
                        </div>
                        
                        <div class="mb-4">
                            <label for="tanggal" class="block text-sm font-medium text-gray-700">Tanggal Pembayaran</label>
                            <input type="date" id="tanggal" name="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                        </div>
                        
                        <!-- Status akan ditentukan secara otomatis -->
                        <input type="hidden" name="status" value="belum_bayar">
                        
                        <div class="mb-4 flex items-center">
                            <input type="checkbox" id="is_cicilan" name="is_cicilan" value="1" {{ old('is_cicilan') ? 'checked' : '' }} class="mr-2 h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                            <label for="is_cicilan" class="text-sm font-medium text-gray-700">Pembayaran Cicilan</label>
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

    <script>
        function updateSppInfo(userId) {
            const sppInfoDiv = document.getElementById('spp-info');
            const totalSppSpan = document.getElementById('total-spp');
            const paymentStatusSpan = document.getElementById('payment-status');
            const paidAmountSpan = document.getElementById('paid-amount');
            const remainingAmountSpan = document.getElementById('remaining-amount');
            const jumlahInput = document.getElementById('jumlah');
            
            if (!userId) {
                sppInfoDiv.classList.add('hidden');
                return;
            }
            
            // Fetch user data from the selected option
            const selectedOption = document.querySelector(`#user_id option[value="${userId}"]`);
            const sppBulanan = selectedOption.getAttribute('data-spp');
            
            totalSppSpan.innerText = `Rp ${parseInt(sppBulanan).toLocaleString()}`;
            
            // Set default values
            jumlahInput.value = sppBulanan;
            
            // Fetch payment data for the selected student and month
            const bulan = document.getElementById('bulan').value;
            fetch(`/api/check-payment-status/${userId}/${bulan}`)
                .then(response => response.json())
                .then(data => {
                    if (data.exists) {
                        paymentStatusSpan.innerHTML = `<span class="px-2 py-1 text-xs text-white ${data.payment.status === 'lunas' ? 'bg-green-600' : 'bg-yellow-600'} rounded">${data.payment.status === 'lunas' ? 'Sudah Lunas' : 'Cicilan'}</span>`;
                        
                        if (data.payment.is_cicilan) {
                            paidAmountSpan.innerText = `Rp ${parseInt(data.total_paid).toLocaleString()}`;
                            remainingAmountSpan.innerText = `Rp ${parseInt(data.remaining).toLocaleString()}`;
                            
                            // Set jumlah input to remaining amount
                            jumlahInput.value = data.remaining;
                            
                            // Check cicilan checkbox
                            document.getElementById('is_cicilan').checked = true;
                        } else {
                            paidAmountSpan.innerText = `Rp ${parseInt(data.payment.jumlah).toLocaleString()}`;
                            remainingAmountSpan.innerText = `Rp 0`;
                        }
                    } else {
                        paymentStatusSpan.innerHTML = '<span class="px-2 py-1 text-xs text-white bg-red-600 rounded">Belum Dibayar</span>';
                        paidAmountSpan.innerText = 'Rp 0';
                        remainingAmountSpan.innerText = `Rp ${parseInt(sppBulanan).toLocaleString()}`;
                    }
                })
                .catch(error => {
                    console.error('Error fetching payment data:', error);
                    paymentStatusSpan.innerText = 'Belum Dibayar';
                    paidAmountSpan.innerText = 'Rp 0';
                    remainingAmountSpan.innerText = `Rp ${parseInt(sppBulanan).toLocaleString()}`;
                });
            
            sppInfoDiv.classList.remove('hidden');
        }

        // Update when month changes too
        document.getElementById('bulan').addEventListener('change', function() {
            const userId = document.getElementById('user_id').value;
            if (userId) {
                updateSppInfo(userId);
            }
        });

        // Initialize on page load if user is already selected
        document.addEventListener('DOMContentLoaded', function() {
            const userId = document.getElementById('user_id').value;
            if (userId) {
                updateSppInfo(userId);
            }
        });
    </script>
</x-app-layout>