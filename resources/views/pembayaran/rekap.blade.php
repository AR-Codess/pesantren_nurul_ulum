<x-app-layout>
  <x-slot name="header">
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          {{ __('Rekapitulasi Pembayaran SPP') }}
      </h2>
  </x-slot>

  <div class="py-12">
      <div class="max-w-full mx-auto sm:px-6 lg:px-8">
          <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
              <div class="p-6 bg-white border-b border-gray-200">

                  <div class="mb-4">
                      <a href="{{ route('pembayaran.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-700">
                          &laquo; Kembali
                      </a>
                  </div>
              
                  <div class="mb-6 mt-8">
                      <form action="{{ route('pembayaran.rekap') }}" method="GET" class="flex flex-wrap items-end gap-4">
                          
                          <!-- BARU: Input Pencarian Nama -->
                          <div>
                              <label for="search" class="block text-sm font-medium text-gray-700">Cari Nama Santri</label>
                              <input type="text" name="search" id="search" value="{{ $search ?? '' }}" placeholder="Masukkan nama..." class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                          </div>

                          <!-- Filter Kelas -->
                          <div>
                              <label for="class_level_id" class="block text-sm font-medium text-gray-700">Filter Kelas</label>
                              <select name="class_level_id" id="class_level_id" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 rounded-md">
                                  <option value="">-- Semua Kelas --</option>
                                  @foreach ($classLevels as $level)
                                      <option value="{{ $level->id }}" {{ $selectedClassId == $level->id ? 'selected' : '' }}>
                                          {{ $level->level }}
                                      </option>
                                  @endforeach
                              </select>
                          </div>
                          <!-- Filter Tahun -->
                          <div>
                              <label for="tahun" class="block text-sm font-medium text-gray-700">Filter Tahun</label>
                              <select name="tahun" id="tahun" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 rounded-md">
                                  @foreach ($availableYears as $year)
                                      <option value="{{ $year }}" {{ $selectedYear == $year ? 'selected' : '' }}>
                                          {{ $year }}
                                      </option>
                                  @endforeach
                              </select>
                          </div>
                           <!-- Tombol Aksi -->
                          <div>
                              <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-700">
                                  Filter
                              </button>
                          </div>
                      </form>
                  </div>

                  <!-- Tabel Rekapitulasi -->
                  <div class="overflow-x-auto">
                      <table class="min-w-full divide-y divide-gray-200 border">
                          {{-- ... thead ... --}}
                          <thead class="bg-gray-50">
                              <tr>
                                  <th scope="col" class="sticky left-0 bg-gray-100 px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider z-10">
                                      Nama Santri
                                  </th>
                                  @foreach ($periods as $period)
                                      <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                          {{ $period->isoFormat('MMM YY') }}
                                      </th>
                                  @endforeach
                              </tr>
                          </thead>
                          <tbody class="bg-white divide-y divide-gray-200">
                              @forelse ($students as $student)
                                  <tr>
                                      <td class="sticky left-0 bg-white px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 z-10 border-r">
                                          {{ $student->nama_santri }}
                                      </td>
                                      @foreach ($periods as $period)
                                          <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                                              @php
                                                  $studentRegisteredAt = \Carbon\Carbon::parse($student->created_at);
                                              @endphp

                                              @if ($period->isBefore($studentRegisteredAt->startOfMonth()))
                                                  <span class="text-gray-400 font-bold">-</span>
                                              @else
                                                  @php
                                                      $key = $period->year . '-' . $period->month;
                                                      $pembayaran = $student->pembayaranLookup->get($key);
                                                  @endphp

                                                  @if ($pembayaran)
                                                      @if ($pembayaran->status == 'lunas')
                                                          <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                              Lunas
                                                          </span>
                                                      @elseif ($pembayaran->status == 'belum_lunas')
                                                          <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                              Cicilan
                                                          </span>
                                                      @else
                                                          <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                              Belum Bayar
                                                          </span>
                                                      @endif
                                                  @else
                                                      <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                          Belum Bayar
                                                      </span>
                                                  @endif
                                              @endif
                                          </td>
                                      @endforeach
                                  </tr>
                              @empty
                                  <tr>
                                      <td colspan="{{ count(iterator_to_array($periods)) + 1 }}" class="text-center py-4">
                                          Tidak ada data santri yang cocok dengan filter.
                                      </td>
                                  </tr>
                              @endforelse
                          </tbody>
                      </table>
                  </div>
              </div>
          </div>
      </div>
  </div>
</x-app-layout>