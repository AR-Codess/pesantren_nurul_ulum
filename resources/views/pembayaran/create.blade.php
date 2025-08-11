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
                            <select id="user_id" name="user_id" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value="">-- Pilih Santri --</option>
                                @foreach($users as $user)
                                <option value="{{ $user->id }}"
                                    data-spp="{{ $user->spp_bulanan ?? 0 }}"
                                    data-spp-beasiswa="0"
                                    data-is-beasiswa="0"
                                    data-name="{{ $user->nama_santri }}"
                                    data-level="{{ $user->classLevel->level ?? 'Tanpa Kelas' }}"
                                    {{ old('user_id', $selectedData['user_id'] ?? null) == $user->id ? 'selected' : '' }}
                                    @if(!$user->classLevel) disabled @endif>
                                    {{ $user->nama_santri }} ({{ $user->nis }})
                                    @if(!$user->classLevel)
                                    <span class="text-red-600 font-semibold">- (Atur kelas terlebih dahulu)</span>
                                    @elseif($user->is_beasiswa)
                                    <span class="text-green-600">- Beasiswa</span>
                                    @endif
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- spp-info dihilangkan sesuai permintaan, tidak perlu render UI ini lagi -->

                        <div class="mb-4">
                            <label for="periode_tagihan" class="block text-sm font-medium text-gray-700">Periode Tagihan</label>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-1">
                                <div>
                                    <select id="periode_bulan" name="periode_bulan" class="block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm" required>
                                        <option value="">-- Pilih Bulan --</option>
                                        {{-- MODIFIKASI: Gunakan $selectedData untuk memilih bulan secara otomatis --}}
                                        @for ($i = 1; $i <= 12; $i++)
                                            <option value="{{ $i }}" {{ old('periode_bulan', $selectedData['periode_bulan'] ?? null) == $i ? 'selected' : '' }}>
                                            {{ \Carbon\Carbon::create()->month($i)->isoFormat('MMMM') }}
                                            </option>
                                            @endfor
                                    </select>
                                </div>
                                <div>
                                    <select id="periode_tahun" name="periode_tahun" class="block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm" required>
                                        <option value="">-- Pilih Tahun --</option>
                                        @php
                                        $currentYear = date('Y');
                                        $startYear = $currentYear - 5;
                                        $endYear = $currentYear + 1;
                                        @endphp
                                        {{-- MODIFIKASI: Gunakan $selectedData untuk memilih tahun secara otomatis --}}
                                        @for($year = $endYear; $year >= $startYear; $year--)
                                        <option value="{{ $year }}" {{ old('periode_tahun', $selectedData['periode_tahun'] ?? null) == $year ? 'selected' : '' }}>{{ $year }}</option>
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
                            <input type="number" id="jumlah" name="jumlah_dibayar" value="{{ old('jumlah_dibayar') }}" placeholder="0" required class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" min="1">
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
                            <button type="submit" id="submit-payment-btn" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-700 opacity-50 cursor-not-allowed" disabled>
                                Simpan Pembayaran
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function validateJumlahPembayaran() {
            const jumlahInput = document.getElementById('jumlah');
            const totalTagihanInput = document.getElementById('total_tagihan');
            const isCicilan = document.getElementById('is_cicilan').checked;
            const submitBtn = document.getElementById('submit-payment-btn');
            const jumlah = parseInt(jumlahInput.value);
            const totalTagihan = parseInt(totalTagihanInput.value);

            // Set max attribute sesuai total tagihan jika bukan cicilan
            if (!isCicilan && totalTagihan > 0) {
                jumlahInput.setAttribute('max', totalTagihan);
            } else {
                jumlahInput.removeAttribute('max');
            }

            // Validasi tombol
            if (!jumlah || jumlah <= 0) {
                submitBtn.disabled = true;
                submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
                return;
            }
            if (isCicilan) {
                // Cicilan: boleh kurang dari total tagihan, tapi harus > 0
                if (jumlah > 0 && jumlah < totalTagihan) {
                    submitBtn.disabled = false;
                    submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                } else {
                    submitBtn.disabled = true;
                    submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
                }
            } else {
                // Bukan cicilan: harus sama persis dengan total tagihan
                if (jumlah === totalTagihan && jumlah > 0) {
                    submitBtn.disabled = false;
                    submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                } else {
                    submitBtn.disabled = true;
                    submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
                }
            }
        }

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
                submitBtn.disabled = true; // Disable submit button
                submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
                return;
            }

            // Fetch user data from the selected option
            const selectedOption = document.querySelector(`#user_id option[value="${userId}"]`);
            const sppBulanan = selectedOption.getAttribute('data-spp');
            const sppBeasiswa = selectedOption.getAttribute('data-spp-beasiswa');
            const isBeasiswa = selectedOption.getAttribute('data-is-beasiswa') === '1';
            const santriName = selectedOption.getAttribute('data-name');
            const santriLevel = selectedOption.getAttribute('data-level');

            // Check if the student has a class
            if (santriLevel === 'Tanpa Kelas') {
                sppInfoDiv.classList.add('hidden');
                totalTagihanInput.value = '';
                totalTagihanDisplay.value = 'Rp 0';
                submitBtn.disabled = true; // Disable submit button
                submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
                alert('Silahkan atur kelas santri terlebih dahulu');
                return;
            }


            // Ambil spp_bulanan langsung dari data-spp
            let actualSpp = sppBulanan;
            totalTagihanInput.value = actualSpp;
            totalTagihanDisplay.value = `Rp ${parseInt(actualSpp).toLocaleString()}`;

            // Display information (jika ada elemen info lain)
            if (typeof totalSppSpan !== 'undefined' && totalSppSpan) {
                totalSppSpan.innerText = `Rp ${parseInt(actualSpp).toLocaleString()}`;
            }
            if (typeof santriNameSpan !== 'undefined' && santriNameSpan) {
                santriNameSpan.innerText = santriName;
            }
            if (typeof santriLevelSpan !== 'undefined' && santriLevelSpan) {
                santriLevelSpan.innerText = santriLevel;
            }

            checkPaymentStatus(userId);

            sppInfoDiv.classList.remove('hidden');
            submitBtn.disabled = false; // Enable submit button
            submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
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
                    // console.error('Error fetching payment data:', error);
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
                validateJumlahPembayaran();
            });

            document.getElementById('periode_bulan').addEventListener('change', function() {
                if (document.getElementById('user_id').value) {
                    checkPaymentStatus(document.getElementById('user_id').value);
                }
                validateJumlahPembayaran();
            });

            document.getElementById('periode_tahun').addEventListener('change', function() {
                if (document.getElementById('user_id').value) {
                    checkPaymentStatus(document.getElementById('user_id').value);
                }
                validateJumlahPembayaran();
            });

            document.getElementById('jumlah').addEventListener('input', validateJumlahPembayaran);
            document.getElementById('is_cicilan').addEventListener('change', validateJumlahPembayaran);
        });
    </script>
</x-app-layout>