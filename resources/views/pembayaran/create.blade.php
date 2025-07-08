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
                                <option value="{{ $user->id }}"
                                    data-spp="{{ $user->classLevel->spp }}"
                                    data-spp-beasiswa="{{ $user->classLevel->spp_beasiswa }}"
                                    data-is-beasiswa="{{ $user->is_beasiswa ? 1 : 0 }}"
                                    data-name="{{ $user->nama_santri }}"
                                    data-level="{{ $user->classLevel->level }}"
                                    {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                    {{ $user->nama_santri }} ({{ $user->nis }}) 
                                    @if($user->is_beasiswa) <span class="text-green-600">- Beasiswa</span> @endif
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div id="spp-info" class="mb-4 p-4 bg-blue-50 border border-blue-200 rounded-md hidden">
                            <h3 class="text-sm font-medium text-blue-800 mb-2">Informasi SPP</h3>
                            <p class="text-sm text-blue-700">Nama Santri: <span id="santri-name">-</span></p>
                            <p class="text-sm text-blue-700">Kelas: <span id="santri-level">-</span></p>
                            <p class="text-sm text-blue-700">Total SPP Bulanan: <span id="total-spp">Rp 0</span></p>
                            <p class="text-sm text-blue-700 mt-1">Status Pembayaran Bulan Ini: <span id="payment-status">-</span></p>
                            <p class="text-sm text-blue-700 mt-1">Sudah Dibayar: <span id="paid-amount">Rp 0</span></p>
                            <p class="text-sm text-blue-700 mt-1">Sisa Pembayaran: <span id="remaining-amount">Rp 0</span></p>
                        </div>

                        <div class="mb-4">
                            <label for="periode_tagihan" class="block text-sm font-medium text-gray-700">Periode Tagihan</label>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-1">
                                <div>
                                    <select id="periode_bulan" name="periode_bulan" class="block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                                        <option value="">-- Pilih Bulan --</option>
                                        <option value="1" {{ old('periode_bulan') == '1' ? 'selected' : '' }}>Januari</option>
                                        <option value="2" {{ old('periode_bulan') == '2' ? 'selected' : '' }}>Februari</option>
                                        <option value="3" {{ old('periode_bulan') == '3' ? 'selected' : '' }}>Maret</option>
                                        <option value="4" {{ old('periode_bulan') == '4' ? 'selected' : '' }}>April</option>
                                        <option value="5" {{ old('periode_bulan') == '5' ? 'selected' : '' }}>Mei</option>
                                        <option value="6" {{ old('periode_bulan') == '6' ? 'selected' : '' }}>Juni</option>
                                        <option value="7" {{ old('periode_bulan') == '7' ? 'selected' : '' }}>Juli</option>
                                        <option value="8" {{ old('periode_bulan') == '8' ? 'selected' : '' }}>Agustus</option>
                                        <option value="9" {{ old('periode_bulan') == '9' ? 'selected' : '' }}>September</option>
                                        <option value="10" {{ old('periode_bulan') == '10' ? 'selected' : '' }}>Oktober</option>
                                        <option value="11" {{ old('periode_bulan') == '11' ? 'selected' : '' }}>November</option>
                                        <option value="12" {{ old('periode_bulan') == '12' ? 'selected' : '' }}>Desember</option>
                                    </select>
                                </div>
                                <div>
                                    <select id="periode_tahun" name="periode_tahun" class="block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                                        <option value="">-- Pilih Tahun --</option>
                                        @php
                                            $currentYear = date('Y');
                                            $startYear = $currentYear - 5;
                                            $endYear = $currentYear + 1;
                                        @endphp
                                        @for($year = $startYear; $year <= $endYear; $year++)
                                            <option value="{{ $year }}" {{ (old('periode_tahun') == $year || (!old('periode_tahun') && $year == $currentYear)) ? 'selected' : '' }}>{{ $year }}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                            <div id="periode-lunas-warning" class="mt-2 text-red-600 text-sm hidden">
                                <span class="font-semibold">Periode ini sudah lunas!</span> Pembayaran untuk bulan ini sudah dilakukan.
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="total_tagihan_display" class="block text-sm font-semibold text-blue-700 mb-1">Total Tagihan SPP</label>
                            <input type="text" id="total_tagihan_display" class="mt-1 block w-full bg-gray-100 border border-blue-200 rounded-md px-4 py-2 text-blue-800 font-bold" value="Rp 0" readonly tabindex="-1">
                            <input type="hidden" name="total_tagihan" id="total_tagihan">
                        </div>
                        <div class="mb-4">
                            <label for="jumlah" class="block text-sm font-medium text-gray-700">Jumlah Pembayaran (Rp)</label>
                            <input type="number" id="jumlah" name="jumlah_dibayar" value="{{ old('jumlah_dibayar') }}" placeholder="0" required class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
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

                        <!-- Status akan ditentukan secara otomatis -->
                        <input type="hidden" name="status" value="belum_bayar">

                        <div class="mb-4 flex items-center">
                            <input type="checkbox" id="is_cicilan" name="is_cicilan" value="1" {{ old('is_cicilan') ? 'checked' : '' }} class="mr-2 h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                            <label for="is_cicilan" class="text-sm font-medium text-gray-700">Pembayaran Cicilan</label>
                        </div>

                        <div class="mt-6">
                            <button type="submit" id="submit-payment-btn" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-700">
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
            const totalTagihanInput = document.getElementById('total_tagihan');
            const totalTagihanDisplay = document.getElementById('total_tagihan_display');
            const santriNameSpan = document.getElementById('santri-name');
            const santriLevelSpan = document.getElementById('santri-level');
            const submitBtn = document.getElementById('submit-payment-btn');
            const periodeLunasWarning = document.getElementById('periode-lunas-warning');

            if (!userId) {
                sppInfoDiv.classList.add('hidden');
                totalTagihanInput.value = '';
                totalTagihanDisplay.value = 'Rp 0';
                return;
            }

            // Fetch user data from the selected option
            const selectedOption = document.querySelector(`#user_id option[value="${userId}"]`);
            const sppBulanan = selectedOption.getAttribute('data-spp');
            const sppBeasiswa = selectedOption.getAttribute('data-spp-beasiswa');
            const isBeasiswa = selectedOption.getAttribute('data-is-beasiswa') === '1';
            const santriName = selectedOption.getAttribute('data-name');
            const santriLevel = selectedOption.getAttribute('data-level');
            
            // Determine the appropriate SPP amount based on scholarship status
            let actualSpp = sppBulanan;
            if (isBeasiswa) {
                // Use spp_beasiswa if it exists and is not zero or null
                if (sppBeasiswa && parseInt(sppBeasiswa) > 0) {
                    actualSpp = sppBeasiswa;
                }
                // If spp_beasiswa is null, zero, or not set, use a default value (you can adjust this as needed)
                else {
                    actualSpp = sppBulanan; // Fallback to regular SPP
                    // console.log("Scholarship SPP amount is not set, using regular SPP");
                }
            }

            // Display information with scholarship indicator if applicable
            totalSppSpan.innerText = `Rp ${parseInt(actualSpp).toLocaleString()}${isBeasiswa ? ' (Beasiswa)' : ''}`;
            santriNameSpan.innerText = santriName;
            santriLevelSpan.innerText = santriLevel;

            // Set default values for payment
            totalTagihanInput.value = actualSpp;
            totalTagihanDisplay.value = `Rp ${parseInt(actualSpp).toLocaleString()}`;

            checkPaymentStatus(userId);
            
            sppInfoDiv.classList.remove('hidden');
        }

        function checkPaymentStatus(userId) {
            const periodeBulan = document.getElementById('periode_bulan').value;
            const periodeTahun = document.getElementById('periode_tahun').value;
            const submitBtn = document.getElementById('submit-payment-btn');
            const periodeLunasWarning = document.getElementById('periode-lunas-warning');
            const paymentStatusSpan = document.getElementById('payment-status');
            const paidAmountSpan = document.getElementById('paid-amount');
            const remainingAmountSpan = document.getElementById('remaining-amount');
            
            if (!userId || !periodeBulan || !periodeTahun) return;
            
            // Convert month number to month name for API call
            const monthNames = [
                'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
            ];
            const monthName = monthNames[periodeBulan - 1];
            
            // Fetch payment data for the selected student and month
            fetch(`/api/check-payment-status/${userId}/${monthName}`)
                .then(response => response.json())
                .then(data => {
                    const selectedOption = document.querySelector(`#user_id option[value="${userId}"]`);
                    const sppBulanan = selectedOption.getAttribute('data-spp');
                    const sppBeasiswa = selectedOption.getAttribute('data-spp-beasiswa');
                    const isBeasiswa = selectedOption.getAttribute('data-is-beasiswa') === '1';
                    const actualSpp = isBeasiswa && sppBeasiswa && parseInt(sppBeasiswa) > 0 ? sppBeasiswa : sppBulanan;
                    
                    if (data.exists) {
                        // Payment exists for this period
                        if (data.payment.status === 'lunas') {
                            // If payment is already completed, show warning and disable submit button
                            periodeLunasWarning.classList.remove('hidden');
                            submitBtn.disabled = true;
                            submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
                            paymentStatusSpan.innerHTML = `<span class="px-2 py-1 text-xs text-white bg-green-600 rounded">Sudah Lunas</span>`;
                        } else {
                            // If payment exists but not completed
                            periodeLunasWarning.classList.add('hidden');
                            submitBtn.disabled = false;
                            submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                            paymentStatusSpan.innerHTML = `<span class="px-2 py-1 text-xs text-white bg-yellow-600 rounded">Cicilan</span>`;
                        }
                        
                        if (data.payment.is_cicilan) {
                            paidAmountSpan.innerText = `Rp ${parseInt(data.total_paid).toLocaleString()}`;
                            remainingAmountSpan.innerText = `Rp ${parseInt(data.remaining).toLocaleString()}`;
                            document.getElementById('is_cicilan').checked = true;
                        } else {
                            const firstPayment = data.payment.detailPembayaran && data.payment.detailPembayaran[0];
                            const paidAmount = firstPayment ? firstPayment.jumlah_dibayar : 0;
                            paidAmountSpan.innerText = `Rp ${parseInt(paidAmount).toLocaleString()}`;
                            remainingAmountSpan.innerText = `Rp 0`;
                        }
                    } else {
                        // No payment exists for this period
                        periodeLunasWarning.classList.add('hidden');
                        submitBtn.disabled = false;
                        submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                        paymentStatusSpan.innerHTML = '<span class="px-2 py-1 text-xs text-white bg-red-600 rounded">Belum Dibayar</span>';
                        paidAmountSpan.innerText = 'Rp 0';
                        remainingAmountSpan.innerText = `Rp ${parseInt(actualSpp).toLocaleString()}`;
                    }
                })
                .catch(error => {
                    console.error('Error fetching payment data:', error);
                    const selectedOption = document.querySelector(`#user_id option[value="${userId}"]`);
                    const sppBulanan = selectedOption.getAttribute('data-spp');
                    const sppBeasiswa = selectedOption.getAttribute('data-spp-beasiswa');
                    const isBeasiswa = selectedOption.getAttribute('data-is-beasiswa') === '1';
                    const actualSpp = isBeasiswa && sppBeasiswa && parseInt(sppBeasiswa) > 0 ? sppBeasiswa : sppBulanan;
                    
                    paymentStatusSpan.innerText = 'Belum Dibayar';
                    paidAmountSpan.innerText = 'Rp 0';
                    remainingAmountSpan.innerText = `Rp ${parseInt(actualSpp).toLocaleString()}`;
                    periodeLunasWarning.classList.add('hidden');
                    submitBtn.disabled = false;
                    submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                });
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            const userId = document.getElementById('user_id').value;
            if (userId) {
                updateSppInfo(userId);
            }

            // Event listeners
            document.getElementById('user_id').addEventListener('change', function() {
                updateSppInfo(this.value);
            });
            
            document.getElementById('periode_bulan').addEventListener('change', function() {
                if (document.getElementById('user_id').value) {
                    checkPaymentStatus(document.getElementById('user_id').value);
                }
            });
            
            document.getElementById('periode_tahun').addEventListener('change', function() {
                if (document.getElementById('user_id').value) {
                    checkPaymentStatus(document.getElementById('user_id').value);
                }
            });
        });
    </script>
</x-app-layout>