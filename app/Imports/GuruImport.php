<?php

namespace App\Imports;

use App\Models\Guru;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class GuruImport implements ToModel, WithHeadingRow, WithValidation, SkipsEmptyRows
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // Logika untuk menangani jenis kelamin
        $jenisKelamin = null;
        if (isset($row['jenis_kelamin'])) {
            $jk = Str::lower($row['jenis_kelamin']);
            if ($jk === 'laki-laki') {
                $jenisKelamin = 1;
            } elseif ($jk === 'perempuan') {
                $jenisKelamin = 0;
            }
        }

        // Logika penanganan tanggal
        $tanggalLahir = null;
        if (!empty($row['tanggal_lahir'])) {
            if (is_numeric($row['tanggal_lahir'])) {
                $tanggalLahir = Date::excelToDateTimeObject($row['tanggal_lahir']);
            } else {
                try {
                    $tanggalLahir = \Carbon\Carbon::parse($row['tanggal_lahir']);
                } catch (\Exception $e) {
                    $tanggalLahir = null;
                }
            }
        }

        $guru = Guru::create([
            'nik'                           => $row['nik'],
            'nama_pendidik'                 => $row['nama_pendidik'],
            'email'                         => $row['email'] ?? null,
            'password'                      => Hash::make('ppnurulum123'), // Password default
            'jenis_kelamin'                 => $jenisKelamin,
            'tempat_lahir'                  => $row['tempat_lahir'] ?? null,
            'tanggal_lahir'                 => $tanggalLahir,
            'pendidikan_terakhir'           => $row['pendidikan_terakhir'] ?? null,
            'riwayat_pendidikan_keagamaan'  => $row['riwayat_pendidikan_keagamaan_islam'] ?? null,
            'no_telepon'                    => $row['no_telepon'] ?? null,
            'provinsi'                      => $row['provinsi'] ?? null,
            'kabupaten'                     => $row['kabupaten'] ?? null,
            'alamat'                        => $row['alamat'] ?? null,
        ]);

        // Tetapkan role 'guru' setelah guru dibuat
        $guru->assignRole('guru');

        return $guru;
    }

    /**
     * Tentukan aturan validasi untuk setiap baris di Excel.
     * Hanya 'nik' dan 'nama_pendidik' yang wajib.
     */
    public function rules(): array
    {
        return [
            'nik'           => 'required|string|size:16|unique:guru,nik',
            'nama_pendidik' => 'required|string|max:255',

            // Kolom opsional
            'email'         => 'nullable|email|unique:guru,email',
            'jenis_kelamin' => 'nullable|in:Laki-laki,Perempuan,laki-laki,perempuan',
            'tanggal_lahir' => 'nullable',
        ];
    }

    /**
     * Menyesuaikan nama atribut untuk pesan error.
     */
    public function customValidationAttributes()
    {
        return [
            'nik' => 'NIK',
            'nama_pendidik' => 'Nama Pendidik',
        ];
    }
}
