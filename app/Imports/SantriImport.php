<?php

namespace App\Imports;

use App\Models\User;
use App\Models\ClassLevel;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows; // 1. Import concern untuk melewati baris kosong
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

// 2. Implementasikan SkipsEmptyRows
class SantriImport implements ToModel, WithHeadingRow, WithValidation, SkipsEmptyRows
{
    private $classLevel;

    public function __construct(?int $classLevelId)
    {
        $this->classLevel = ClassLevel::find($classLevelId);
    }

    public function model(array $row)
    {
        // Logika untuk menangani 'Laki-laki' dan 'Perempuan'
        $jenisKelamin = null;
        if (isset($row['jenis_kelamin'])) {
            $jk = Str::lower($row['jenis_kelamin']);
            if ($jk === 'laki-laki') {
                $jenisKelamin = 1;
            } elseif ($jk === 'perempuan') {
                $jenisKelamin = 0;
            }
        }

        // Logika penanganan tanggal yang lebih andal
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

        $user = User::create([
            'nama_santri'   => $row['nama_santri'],
            'nis'           => $row['nis'],
            'email'         => $row['email'] ?? null,
            'password'      => Hash::make('password'),
            'class_level_id'=> $this->classLevel ? $this->classLevel->id : null,
            'spp_bulanan'   => $this->classLevel ? $this->classLevel->spp : 0,
            'jenis_kelamin' => $jenisKelamin,
            'tempat_lahir'  => $row['tempat_lahir'] ?? null,
            'tanggal_lahir' => $tanggalLahir,
            'alamat'        => $row['alamat'] ?? null,
            'is_beasiswa'   => 0,
        ]);

        $user->assignRole('user');

        return $user;
    }

    public function rules(): array
    {
        return [
            'nama_santri'  => 'required|string|max:255',
            'nis'           => 'required|string|unique:users,nis',
            'email'         => 'nullable|email|unique:users,email',
            'tempat_lahir'  => 'nullable|string',
            'tanggal_lahir' => 'nullable',
            'jenis_kelamin' => 'nullable|in:Laki-laki,Perempuan,laki-laki,perempuan',
            'provinsi'      => 'nullable|string',
            'kabupaten'     => 'nullable|string',
            'alamat'        => 'nullable|string',
        ];
    }
    
    public function customValidationAttributes()
    {
        return ['nama_santri' => 'Nama Santri'];
    }
}
