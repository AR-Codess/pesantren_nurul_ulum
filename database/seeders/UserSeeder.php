<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Hapus data lama agar spp_bulanan terisi benar
        DB::table('users')->delete();
        // Sample data untuk santri (users)
        $santriData = [
            [
                'nama_santri' => 'Ahmad Fahri',
                'email' => 'ahmad.fahri@pondok.test',
                'password' => '12345678',
                'nis' => '2025001',
                'class_level_id' => 1,
                'jenis_kelamin' => true,
                'tempat_lahir' => 'Surabaya',
                'tanggal_lahir' => '2010-05-15',
                'provinsi' => 'Jawa Timur',
                'kabupaten' => 'Surabaya',
                'alamat' => 'Jl. Imam Bonjol No. 45, Surabaya',
                'no_hp' => '081234567890',
                'is_beasiswa' => false,
            ],
            [
                'nama_santri' => 'Fatimah Azzahra',
                'email' => 'fatimah@pondok.test',
                'password' => '12345678',
                'nis' => '202500228573',
                'class_level_id' => 1,
                'jenis_kelamin' => false,
                'tempat_lahir' => 'Sidoarjo',
                'tanggal_lahir' => '2011-03-20',
                'provinsi' => 'Jawa Timur',
                'kabupaten' => 'Sidoarjo',
                'alamat' => 'Jl. Pahlawan No. 12, Sidoarjo',
                'no_hp' => '082345678901',
                'is_beasiswa' => false,
            ],
            // 20 data tambahan
            ['nama_santri' => 'Muhammad Iqbal', 'email' => 'iqbal@pondok.test', 'password' => '12345678', 'nis' => '2025002', 'class_level_id' => 2, 'jenis_kelamin' => true, 'tempat_lahir' => 'Malang', 'tanggal_lahir' => '2010-07-10', 'provinsi' => 'Jawa Timur', 'kabupaten' => 'Malang', 'alamat' => 'Jl. Mawar No. 10, Malang', 'no_hp' => '081234567891', 'is_beasiswa' => false],
            ['nama_santri' => 'Aisyah Putri', 'email' => 'aisyah@pondok.test', 'password' => '12345678', 'nis' => '2025003', 'class_level_id' => 2, 'jenis_kelamin' => false, 'tempat_lahir' => 'Gresik', 'tanggal_lahir' => '2011-01-22', 'provinsi' => 'Jawa Timur', 'kabupaten' => 'Gresik', 'alamat' => 'Jl. Melati No. 5, Gresik', 'no_hp' => '081234567892', 'is_beasiswa' => true],
            ['nama_santri' => 'Rizky Ramadhan', 'email' => 'rizky@pondok.test', 'password' => '12345678', 'nis' => '2025004', 'class_level_id' => 3, 'jenis_kelamin' => true, 'tempat_lahir' => 'Lamongan', 'tanggal_lahir' => '2010-09-18', 'provinsi' => 'Jawa Timur', 'kabupaten' => 'Lamongan', 'alamat' => 'Jl. Kenanga No. 8, Lamongan', 'no_hp' => '081234567893', 'is_beasiswa' => false],
            ['nama_santri' => 'Siti Nurhaliza', 'email' => 'nurhaliza@pondok.test', 'password' => '12345678', 'nis' => '2025005', 'class_level_id' => 3, 'jenis_kelamin' => false, 'tempat_lahir' => 'Bojonegoro', 'tanggal_lahir' => '2011-04-30', 'provinsi' => 'Jawa Timur', 'kabupaten' => 'Bojonegoro', 'alamat' => 'Jl. Anggrek No. 3, Bojonegoro', 'no_hp' => '081234567894', 'is_beasiswa' => true],
            ['nama_santri' => 'Ali Akbar', 'email' => 'ali.akbar@pondok.test', 'password' => '12345678', 'nis' => '2025006', 'class_level_id' => 4, 'jenis_kelamin' => true, 'tempat_lahir' => 'Jombang', 'tanggal_lahir' => '2010-12-05', 'provinsi' => 'Jawa Timur', 'kabupaten' => 'Jombang', 'alamat' => 'Jl. Dahlia No. 7, Jombang', 'no_hp' => '081234567895', 'is_beasiswa' => false],
            ['nama_santri' => 'Nabila Zahra', 'email' => 'nabila@pondok.test', 'password' => '12345678', 'nis' => '2025007', 'class_level_id' => 4, 'jenis_kelamin' => false, 'tempat_lahir' => 'Mojokerto', 'tanggal_lahir' => '2011-06-14', 'provinsi' => 'Jawa Timur', 'kabupaten' => 'Mojokerto', 'alamat' => 'Jl. Flamboyan No. 2, Mojokerto', 'no_hp' => '081234567896', 'is_beasiswa' => false],
            ['nama_santri' => 'Fajar Sidik', 'email' => 'fajar@pondok.test', 'password' => '12345678', 'nis' => '2025008', 'class_level_id' => 5, 'jenis_kelamin' => true, 'tempat_lahir' => 'Tuban', 'tanggal_lahir' => '2010-08-21', 'provinsi' => 'Jawa Timur', 'kabupaten' => 'Tuban', 'alamat' => 'Jl. Sawo No. 6, Tuban', 'no_hp' => '081234567897', 'is_beasiswa' => true],
            ['nama_santri' => 'Dewi Sartika', 'email' => 'dewi@pondok.test', 'password' => '12345678', 'nis' => '2025009', 'class_level_id' => 5, 'jenis_kelamin' => false, 'tempat_lahir' => 'Pasuruan', 'tanggal_lahir' => '2011-02-11', 'provinsi' => 'Jawa Timur', 'kabupaten' => 'Pasuruan', 'alamat' => 'Jl. Mangga No. 4, Pasuruan', 'no_hp' => '081234567898', 'is_beasiswa' => false],
            ['nama_santri' => 'Hafiz Maulana', 'email' => 'hafiz@pondok.test', 'password' => '12345678', 'nis' => '2025010', 'class_level_id' => 6, 'jenis_kelamin' => true, 'tempat_lahir' => 'Probolinggo', 'tanggal_lahir' => '2010-11-29', 'provinsi' => 'Jawa Timur', 'kabupaten' => 'Probolinggo', 'alamat' => 'Jl. Pinang No. 9, Probolinggo', 'no_hp' => '081234567899', 'is_beasiswa' => false],
            ['nama_santri' => 'Laila Sari', 'email' => 'laila@pondok.test', 'password' => '12345678', 'nis' => '2025011', 'class_level_id' => 6, 'jenis_kelamin' => false, 'tempat_lahir' => 'Blitar', 'tanggal_lahir' => '2011-07-17', 'provinsi' => 'Jawa Timur', 'kabupaten' => 'Blitar', 'alamat' => 'Jl. Cemara No. 11, Blitar', 'no_hp' => '081234567800', 'is_beasiswa' => true],
            ['nama_santri' => 'Rama Pratama', 'email' => 'rama@pondok.test', 'password' => '12345678', 'nis' => '2025012', 'class_level_id' => 1, 'jenis_kelamin' => true, 'tempat_lahir' => 'Kediri', 'tanggal_lahir' => '2010-03-03', 'provinsi' => 'Jawa Timur', 'kabupaten' => 'Kediri', 'alamat' => 'Jl. Teratai No. 13, Kediri', 'no_hp' => '081234567801', 'is_beasiswa' => false],
            ['nama_santri' => 'Salsa Bilqis', 'email' => 'salsa@pondok.test', 'password' => '12345678', 'nis' => '2025013', 'class_level_id' => 2, 'jenis_kelamin' => false, 'tempat_lahir' => 'Tulungagung', 'tanggal_lahir' => '2011-09-09', 'provinsi' => 'Jawa Timur', 'kabupaten' => 'Tulungagung', 'alamat' => 'Jl. Wijaya No. 15, Tulungagung', 'no_hp' => '081234567802', 'is_beasiswa' => true],
            ['nama_santri' => 'Bagas Saputra', 'email' => 'bagas@pondok.test', 'password' => '12345678', 'nis' => '2025014', 'class_level_id' => 3, 'jenis_kelamin' => true, 'tempat_lahir' => 'Magetan', 'tanggal_lahir' => '2010-06-06', 'provinsi' => 'Jawa Timur', 'kabupaten' => 'Magetan', 'alamat' => 'Jl. Kamboja No. 17, Magetan', 'no_hp' => '081234567803', 'is_beasiswa' => false],
            ['nama_santri' => 'Nina Agustina', 'email' => 'nina@pondok.test', 'password' => '12345678', 'nis' => '2025015', 'class_level_id' => 4, 'jenis_kelamin' => false, 'tempat_lahir' => 'Ngawi', 'tanggal_lahir' => '2011-08-08', 'provinsi' => 'Jawa Timur', 'kabupaten' => 'Ngawi', 'alamat' => 'Jl. Mawar No. 19, Ngawi', 'no_hp' => '081234567804', 'is_beasiswa' => true],
            ['nama_santri' => 'Dani Firmansyah', 'email' => 'dani@pondok.test', 'password' => '12345678', 'nis' => '2025016', 'class_level_id' => 5, 'jenis_kelamin' => true, 'tempat_lahir' => 'Pacitan', 'tanggal_lahir' => '2010-10-10', 'provinsi' => 'Jawa Timur', 'kabupaten' => 'Pacitan', 'alamat' => 'Jl. Melati No. 21, Pacitan', 'no_hp' => '081234567805', 'is_beasiswa' => false],
            ['nama_santri' => 'Rina Oktaviani', 'email' => 'rina@pondok.test', 'password' => '12345678', 'nis' => '2025017', 'class_level_id' => 6, 'jenis_kelamin' => false, 'tempat_lahir' => 'Madiun', 'tanggal_lahir' => '2011-12-12', 'provinsi' => 'Jawa Timur', 'kabupaten' => 'Madiun', 'alamat' => 'Jl. Anggrek No. 23, Madiun', 'no_hp' => '081234567806', 'is_beasiswa' => true],
            ['nama_santri' => 'Yoga Prasetya', 'email' => 'yoga@pondok.test', 'password' => '12345678', 'nis' => '2025018', 'class_level_id' => 1, 'jenis_kelamin' => true, 'tempat_lahir' => 'Batu', 'tanggal_lahir' => '2010-01-01', 'provinsi' => 'Jawa Timur', 'kabupaten' => 'Batu', 'alamat' => 'Jl. Srikandi No. 25, Batu', 'no_hp' => '081234567807', 'is_beasiswa' => false],
            ['nama_santri' => 'Maya Sari', 'email' => 'maya@pondok.test', 'password' => '12345678', 'nis' => '2025019', 'class_level_id' => 2, 'jenis_kelamin' => false, 'tempat_lahir' => 'Bondowoso', 'tanggal_lahir' => '2011-11-11', 'provinsi' => 'Jawa Timur', 'kabupaten' => 'Bondowoso', 'alamat' => 'Jl. Kenanga No. 27, Bondowoso', 'no_hp' => '081234567808', 'is_beasiswa' => true],
            ['nama_santri' => 'Fikri Hidayat', 'email' => 'fikri@pondok.test', 'password' => '12345678', 'nis' => '2025020', 'class_level_id' => 3, 'jenis_kelamin' => true, 'tempat_lahir' => 'Situbondo', 'tanggal_lahir' => '2010-05-05', 'provinsi' => 'Jawa Timur', 'kabupaten' => 'Situbondo', 'alamat' => 'Jl. Mawar No. 29, Situbondo', 'no_hp' => '081234567809', 'is_beasiswa' => false],
        ];

        // Ensure class_level table has entries before adding users
        if (DB::table('class_level')->count() == 0) {
            $this->call(ClassLevelSeeder::class);
        }

        // Create users and assign 'user' role
        foreach ($santriData as $data) {
            // Check if user with this nis already exists
            $existingUser = User::where('nis', $data['nis'])->first();
            if ($existingUser) {
                continue;
            }

            // Ambil spp dari class_level
            $classLevel = DB::table('class_level')->where('id', $data['class_level_id'])->first();
            $data['spp_bulanan'] = $classLevel ? $classLevel->spp : 0;

            $password = $data['password'];
            $data['password'] = Hash::make($password);

            $user = User::create($data);
            $user->assignRole('user');
        }
    }
}
