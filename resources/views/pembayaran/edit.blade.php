<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Pembayaran') }}
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

                    <form method="POST" action="{{ route('pembayaran.update', $pembayaran->id) }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-4">
                            <label for="user_id" class="block text-sm font-medium text-gray-700">Santri</label>
                            <select id="user_id" name="user_id" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" onchange="updateStudentInfo(this.value)">
                                <option value="">-- Pilih Santri --</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" 
                                            data-spp="{{ $user->classLevel->spp }}"
                                            data-name="{{ $user->nama_santri }}" 
                                            data-level="{{ $user->classLevel->level }}"
                                            {{ old('user_id', $pembayaran->user_id) == $user->id ? 'selected' : '' }}>
                                        {{ $user->nama_santri }} ({{ $user->nis }}) - Kelas {{ $user->classLevel->level }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div id="student-info" class="mb-4 p-4 bg-blue-50 border border-blue-200 rounded-md {{ $pembayaran->user ? '' : 'hidden' }}">
                            <h3 class="text-sm font-medium text-blue-800 mb-2">Informasi Santri</h3>
                            <p class="text-sm text-blue-700">Nama: <span id="santri-name">{{ $pembayaran->user->nama_santri ?? '-' }}</span></p>
                            <p class="text-sm text-blue-700">Kelas: <span id="santri-level">{{ $pembayaran->user->classLevel->level ?? '-' }}</span></p>
                            <p class="text-sm text-blue-700">Total SPP Bulanan: <span id="total-spp">Rp {{ number_format($pembayaran->user->classLevel->spp ?? 0, 0, ',', '.') }}</span></p>
                        </div>
                        
                        <!-- Hidden field for bulan, will be set via JavaScript -->
                        <input type="hidden" id="bulan" name="bulan" value="{{ old('bulan', $pembayaran->bulan) }}">
                        
                        <div class="mb-4">
                            <label for="jumlah" class="block text-sm font-medium text-gray-700">Jumlah Pembayaran (Rp)</label>
                            @if($pembayaran->is_cicilan && $pembayaran->detailPembayaran->isNotEmpty())
                                @php
                                    $jumlahDibayar = $pembayaran->detailPembayaran->first()->jumlah_dibayar;
                                @endphp
                                <input type="number" id="jumlah" name="jumlah_dibayar" value="{{ old('jumlah_dibayar', $jumlahDibayar) }}" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            @else
                                <input type="number" id="jumlah" name="jumlah_dibayar" value="{{ old('jumlah_dibayar', $pembayaran->total_tagihan) }}" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            @endif
                            <input type="hidden" name="total_tagihan" value="{{ $pembayaran->total_tagihan }}">
                        </div>
                        
                        <div class="mb-4">
                            <label for="metode_pembayaran" class="block text-sm font-medium text-gray-700">Metode Pembayaran</label>
                            @if(Auth::guard('admin')->check())
                                <input type="text" id="metode_pembayaran" name="metode_pembayaran" value="Tunai" class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md bg-gray-100" readonly>
                                <p class="mt-1 text-xs text-gray-500">Admin hanya dapat menggunakan metode pembayaran Tunai</p>
                            @else
                                <select id="metode_pembayaran" name="metode_pembayaran" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    @php
                                        $metodePembayaran = $pembayaran->detailPembayaran->isNotEmpty() ? $pembayaran->detailPembayaran->first()->metode_pembayaran : 'Tunai';
                                    @endphp
                                    <option value="Tunai" {{ old('metode_pembayaran', $metodePembayaran) == 'Tunai' ? 'selected' : '' }}>Tunai</option>
                                    <option value="Transfer Bank" {{ old('metode_pembayaran', $metodePembayaran) == 'Transfer Bank' ? 'selected' : '' }}>Transfer Bank</option>
                                    <option value="QRIS" {{ old('metode_pembayaran', $metodePembayaran) == 'QRIS' ? 'selected' : '' }}>QRIS</option>
                                    <option value="Lainnya" {{ old('metode_pembayaran', $metodePembayaran) == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                                </select>
                            @endif
                        </div>
                        
                        <div class="mb-4">
                            <label for="tanggal" class="block text-sm font-medium text-gray-700">Tanggal Pembayaran</label>
                            @php
                                $tanggalBayar = $pembayaran->detailPembayaran->isNotEmpty() 
                                    ? \Carbon\Carbon::parse($pembayaran->detailPembayaran->first()->tanggal_bayar)->format('Y-m-d') 
                                    : date('Y-m-d');
                            @endphp
                            <input type="date" id="tanggal" name="tanggal_bayar" value="{{ old('tanggal_bayar', $tanggalBayar) }}" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            <!-- Hidden input for periode_pembayaran, will be updated via JavaScript -->
                            <input type="hidden" id="periode_pembayaran" name="periode_pembayaran" value="{{ $pembayaran->periode_pembayaran->format('Y-m-d') }}">
                        </div>
                        
                        <!-- Status pembayaran -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Status Pembayaran</label>
                            <div class="mt-1">
                                @if($pembayaran->status == 'lunas')
                                    <span class="px-2 py-1 text-xs text-white bg-green-600 rounded">Lunas</span>
                                @elseif($pembayaran->status == 'belum_lunas')
                                    <span class="px-2 py-1 text-xs text-white bg-yellow-600 rounded">Belum Lunas</span>
                                @elseif($pembayaran->status == 'menunggu_pembayaran')
                                    <span class="px-2 py-1 text-xs text-white bg-blue-600 rounded">Menunggu Pembayaran</span>
                                @else
                                    <span class="px-2 py-1 text-xs text-white bg-red-600 rounded">Belum Bayar</span>
                                @endif
                            </div>
                            <p class="mt-1 text-xs text-gray-500">Status akan diperbarui secara otomatis setelah pembayaran diproses</p>
                            <input type="hidden" name="status" value="{{ $pembayaran->status }}">
                        </div>
                        
                        <div class="mb-4 flex items-center">
                            <input type="checkbox" id="is_cicilan" name="is_cicilan" value="1" {{ old('is_cicilan', $pembayaran->is_cicilan) ? 'checked' : '' }} class="mr-2 h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                            <label for="is_cicilan" class="text-sm font-medium text-gray-700">Pembayaran Cicilan</label>
                        </div>
                        
                        <div class="mt-6">
                            <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-700">
                                Update Pembayaran
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Get month name from month number (0-11)
        function getMonthName(monthNumber) {
            const monthNames = [
                'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
            ];
            return monthNames[monthNumber];
        }

        // Function to update periode_pembayaran field based on payment date
        function updatePeriodePembayaran() {
            const tanggalBayar = document.getElementById('tanggal').value;
            const paymentDate = new Date(tanggalBayar);
            const month = paymentDate.getMonth() + 1; // JavaScript months are 0-11
            const year = paymentDate.getFullYear();
            const monthFormatted = month < 10 ? `0${month}` : `${month}`;
            
            // Set the periode_pembayaran to the first day of the month from the payment date
            document.getElementById('periode_pembayaran').value = `${year}-${monthFormatted}-01`;
            
            // Update the month hidden field
            const monthName = getMonthName(paymentDate.getMonth());
            document.getElementById('bulan').value = monthName;
        }

        // Update when payment date changes
        document.getElementById('tanggal').addEventListener('change', updatePeriodePembayaran);

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            // If the user changes the selected student, update the SPP amount
            const userIdSelect = document.getElementById('user_id');
            if (userIdSelect) {
                userIdSelect.addEventListener('change', function() {
                    // Get selected option data
                    const userId = this.value;
                    if (!userId) return;
                    
                    const selectedOption = this.options[this.selectedIndex];
                    const studentName = selectedOption.textContent.split(' (')[0];
                    const studentClass = selectedOption.textContent.match(/Kelas (\w+)/)[1];
                    
                    // Find and update user info elements
                    document.getElementById('santri-name').textContent = studentName;
                    document.getElementById('santri-level').textContent = studentClass;
                    
                    // Show student info section
                    document.getElementById('student-info').classList.remove('hidden');
                    
                    // Update payment date
                    updatePeriodePembayaran();
                });
            }
            
            // Initialize the periode_pembayaran on page load
            updatePeriodePembayaran();
        });
    </script>
</x-app-layout>