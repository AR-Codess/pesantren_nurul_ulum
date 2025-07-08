<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ClassLevel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Spatie\Permission\Models\Role;
use App\Imports\SantriImport;
use Maatwebsite\Excel\Facades\Excel;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $bulan = $request->input('bulan');
        $status = $request->input('status');
        $perPage = $request->input('per_page', 4); // Changed from 10 to 4 records per page

        // Get all santri users with the 'user' role
        $users = User::role('user')
            ->when($search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('nama_santri', 'like', '%' . $search . '%')
                      ->orWhere('nis', 'like', '%' . $search . '%')
                      ->orWhere('email', 'like', '%' . $search . '%');
                });
            })
            ->when($bulan, function ($query, $bulan) {
                return $query->whereHas('pembayaran', function ($q) use ($bulan) {
                    $q->where('bulan', $bulan);
                });
            })
            ->when($status, function ($query, $status) {
                return $query->whereHas('pembayaran', function ($q) use ($status) {
                    $q->where('status', $status);
                });
            })
            ->latest()
            ->paginate($perPage)
            ->withQueryString();

        return view('admin.users.index', compact('users', 'search', 'bulan', 'status', 'perPage'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $classLevels = ClassLevel::all();
        return view('admin.users.create', compact('classLevels'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_santri' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'nis' => ['required', 'string', 'max:20', 'unique:users'], // NIS is required and must be unique
            'class_level_id' => ['required', 'exists:class_level,id'],
            'jenis_kelamin' => ['required', 'boolean'],
            'tempat_lahir' => ['nullable', 'string', 'max:100'],
            'tanggal_lahir' => ['nullable', 'date'],
            'provinsi' => ['nullable', 'string', 'max:100'],
            'kabupaten' => ['nullable', 'string', 'max:100'],
            'alamat' => ['nullable', 'string'],
            'no_hp' => ['nullable', 'digits_between:10,15'],
            'is_beasiswa' => ['nullable', 'boolean'],
            'spp_bulanan' => ['nullable', 'integer', 'min:0'],
        ]);

        // Get class level to determine correct SPP amount
        $classLevel = ClassLevel::findOrFail($request->class_level_id);
        
        // Check if the beasiswa checkbox is checked (value = 1)
        $isBeasiswa = $request->input('is_beasiswa') == 1;
        
        // Calculate the correct SPP amount based on beasiswa status
        $sppBulanan = 0;
        if ($isBeasiswa && $classLevel->spp_beasiswa) {
            $sppBulanan = $classLevel->spp_beasiswa;
        } else {
            $sppBulanan = $classLevel->spp;
        }

        $user = User::create([
            'nama_santri' => $request->nama_santri,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'nis' => $request->nis,
            'class_level_id' => $request->class_level_id,
            'jenis_kelamin' => $request->jenis_kelamin,
            'tempat_lahir' => $request->tempat_lahir,
            'tanggal_lahir' => $request->tanggal_lahir,
            'provinsi' => $request->provinsi,
            'kabupaten' => $request->kabupaten,
            'alamat' => $request->alamat,
            'no_hp' => $request->no_hp,
            'spp_bulanan' => $sppBulanan,
            'is_beasiswa' => $isBeasiswa,
        ]);

        // Assign the 'user' role to the new santri
        $user->assignRole('user');

        return redirect()->route('users.index')
            ->with('success', 'Data santri berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::with('classLevel')->findOrFail($id);
        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::findOrFail($id);
        $classLevels = ClassLevel::all();
        return view('admin.users.edit', compact('user', 'classLevels'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);
        
        $rules = [
            'nama_santri' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'string', 'email', 'max:255', 'unique:users,email,'.$id],
            'class_level_id' => ['required', 'exists:class_level,id'],
            'jenis_kelamin' => ['required', 'boolean'],
            'tempat_lahir' => ['nullable', 'string', 'max:100'],
            'tanggal_lahir' => ['nullable', 'date'],
            'provinsi' => ['nullable', 'string', 'max:100'],
            'kabupaten' => ['nullable', 'string', 'max:100'],
            'alamat' => ['nullable', 'string'],
            'no_hp' => ['nullable', 'digits_between:10,15'],
            'is_beasiswa' => ['nullable', 'boolean'],
        ];

        // Only validate password if it's provided
        if ($request->filled('password')) {
            $rules['password'] = ['confirmed', Rules\Password::defaults()];
        }

        $request->validate($rules);

        // Get class level to determine correct SPP amount
        $classLevel = ClassLevel::findOrFail($request->class_level_id);
        
        // Check if the beasiswa checkbox is checked (value = 1)
        $isBeasiswa = $request->input('is_beasiswa') == 1;
        
        // Calculate the correct SPP amount based on beasiswa status
        $sppBulanan = 0;
        if ($isBeasiswa && $classLevel->spp_beasiswa) {
            $sppBulanan = $classLevel->spp_beasiswa;
        } else {
            $sppBulanan = $classLevel->spp;
        }

        // Update user data
        $user->nama_santri = $request->nama_santri;
        $user->email = $request->email;
        // NIS is not updated as it should not be changed
        $user->class_level_id = $request->class_level_id;
        $user->jenis_kelamin = $request->jenis_kelamin;
        $user->tempat_lahir = $request->tempat_lahir;
        $user->tanggal_lahir = $request->tanggal_lahir;
        $user->provinsi = $request->provinsi;
        $user->kabupaten = $request->kabupaten;
        $user->alamat = $request->alamat;
        $user->no_hp = $request->no_hp;
        $user->spp_bulanan = $sppBulanan;
        $user->is_beasiswa = $isBeasiswa;
        
        // Update password if provided
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('users.index')
            ->with('success', 'Data santri berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'Data santri berhasil dihapus.');
    }

    /**
     * Menangani proses import data santri dari file Excel.
     */
    public function importExcel(Request $request)
    {
        // 1. Ubah validasi menjadi opsional (nullable)
        $request->validate([
            'file_import' => 'required|mimes:xlsx,xls',
            'class_level_id' => 'nullable|exists:class_level,id'
        ]);

        try {
            // Langsung ambil class_level_id, bisa jadi null jika tidak dipilih
            $classLevelId = $request->input('class_level_id');

            // Kirim class_level_id (bisa null) ke constructor SantriImport
            Excel::import(new SantriImport($classLevelId), $request->file('file_import'));

            return redirect()->route('users.index')->with('success', 'Data santri berhasil diimport!');

        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            $errorMessages = [];
            foreach ($failures as $failure) {
                $errorMessages[] = "Kesalahan pada baris " . $failure->row() . ": " . implode(', ', $failure->errors());
            }
            return redirect()->back()->with('error', 'Gagal mengimpor data. <br>' . implode('<br>', $errorMessages));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat mengimpor data: ' . $e->getMessage());
        }
    }
}
