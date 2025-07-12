<?php

namespace App\Imports;

use App\Models\User;
use App\Models\ClassLevel;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class SantriImport implements ToModel, WithHeadingRow, WithValidation, SkipsEmptyRows
{
    public function model(array $row)
    {
        // Mencari ClassLevel berdasarkan nama kelas dari Excel
        $classLevel = null;
        if (!empty($row['kelas'])) {
            $classLevel = ClassLevel::where('level', $row['kelas'])->first();
        }

        // Logika untuk menangani 'jenis_kelamin'
        $jenisKelamin = null;
        if (isset($row['jenis_kelamin'])) {
            $jk = Str::lower($row['jenis_kelamin']);
            if ($jk === 'l') {
                $jenisKelamin = 1;
            } elseif ($jk === 'p') {
                $jenisKelamin = 0;
            }
        }

        // Logika untuk menangani 'is_beasiswa'
        $isBeasiswa = 0;
        if (!empty($row['beasiswa'])) {
            if (Str::lower($row['beasiswa']) === 'ya') {
                $isBeasiswa = 1;
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

        // Format nama santri menjadi Title Case (Kapital di Setiap Awal Kata)
        $namaSantri = Str::title(strtolower($row['nama_santri']));

        // Membuat data user dengan field yang sudah lengkap
        $user = User::create([
            'nama_santri'    => $namaSantri, // Gunakan nama yang sudah diformat
            'nis'            => $row['nis'],
            'email'          => $row['email'] ?? null,
            'password'       => Hash::make('ppnurulum123'),
            'class_level_id' => $classLevel ? $classLevel->id : null,
            'spp_bulanan'    => $classLevel ? $classLevel->spp : 0,
            'is_beasiswa'    => $isBeasiswa,
            'jenis_kelamin'  => $jenisKelamin,
            'tempat_lahir'   => $row['tempat_lahir'] ?? null,
            'tanggal_lahir'  => $tanggalLahir,
            'provinsi'       => $row['provinsi'] ?? null,
            'kabupaten'      => $row['kabupaten'] ?? null,
            'alamat'         => $row['alamat'] ?? null,
            'no_hp'          => $row['no_hp'] ?? null,
        ]);

        $user->assignRole('user');

        return $user;
    }

    public function rules(): array
    {
        return [
            'nama_santri'   => 'required|string|max:255',
            'nis'           => 'required|string|unique:users,nis',
            'email'         => 'nullable|email|unique:users,email',
            'jenis_kelamin' => 'nullable|in:L,P,l,p',
            'tempat_lahir'  => 'nullable|string',
            'tanggal_lahir' => 'nullable',
            'provinsi'      => 'nullable|string',
            'kabupaten'     => 'nullable|string',
            'alamat'        => 'nullable|string',
            'kelas'         => 'nullable|string|exists:class_level,level',
            'beasiswa'      => 'nullable|string|in:Ya,Tidak,ya,tidak',
            'no_hp'         => 'nullable|string|max:20',
        ];
    }
    
    public function customValidationAttributes()
    {
        return [
            'nama_santri'  => 'Nama Santri',
            'nis'          => 'NIS',
            'kelas.exists' => 'Kelas yang dimasukkan tidak ditemukan di database.',
            'no_hp'        => 'No HP',
        ];
    }
}
