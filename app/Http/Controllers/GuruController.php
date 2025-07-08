<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Spatie\Permission\Models\Role;
use App\Imports\GuruImport;
use Maatwebsite\Excel\Facades\Excel;

class GuruController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('per_page', 10); // Changed from 10 to 4 records per page

        // Get guru records with search and pagination
        $guru = Guru::when($search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('nama_pendidik', 'like', '%' . $search . '%')
                      ->orWhere('nik', 'like', '%' . $search . '%')
                      ->orWhere('email', 'like', '%' . $search . '%');
                });
            })
            ->latest()
            ->paginate($perPage)
            ->withQueryString();

        return view('admin.guru.index', compact('guru', 'search', 'perPage'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.guru.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_pendidik' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:guru'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'nik' => ['required', 'string', 'max:20', 'unique:guru'], // Changed from nullable to required
            'alamat' => ['nullable', 'string'],
            'no_telepon' => ['nullable', 'string', 'max:15'],
            'jenis_kelamin' => ['nullable'],
            'tempat_lahir' => ['nullable', 'string'],
            'tanggal_lahir' => ['nullable', 'date'],
            'pendidikan_terakhir' => ['nullable', 'string'],
            'riwayat_pendidikan_keagamaan' => ['nullable', 'string'],
            'provinsi' => ['nullable', 'string'],
            'kabupaten' => ['nullable', 'string'],
            'bidang' => ['nullable', 'string', 'max:100'],
        ]);

        Guru::create([
            'nama_pendidik' => $request->nama_pendidik,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'nik' => $request->nik,
            'alamat' => $request->alamat,
            'no_telepon' => $request->no_telepon,
            'jenis_kelamin' => $request->jenis_kelamin,
            'tempat_lahir' => $request->tempat_lahir,
            'tanggal_lahir' => $request->tanggal_lahir,
            'pendidikan_terakhir' => $request->pendidikan_terakhir,
            'riwayat_pendidikan_keagamaan' => $request->riwayat_pendidikan_keagamaan,
            'provinsi' => $request->provinsi,
            'kabupaten' => $request->kabupaten,
            'bidang' => $request->bidang,
        ]);

        return redirect()->route('guru.index')
            ->with('success', 'Data guru berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $guru = Guru::findOrFail($id);
        return view('admin.guru.show', compact('guru'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $guru = Guru::findOrFail($id);
        return view('admin.guru.edit', compact('guru'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $guru = Guru::findOrFail($id);
        
        $rules = [
            'nama_pendidik' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:guru,email,'.$id],
            'alamat' => ['nullable', 'string'],
            'no_telepon' => ['nullable', 'string', 'max:15'],
            'jenis_kelamin' => ['nullable'],
            'tempat_lahir' => ['nullable', 'string'],
            'tanggal_lahir' => ['nullable', 'date'],
            'pendidikan_terakhir' => ['nullable', 'string'],
            'riwayat_pendidikan_keagamaan' => ['nullable', 'string'],
            'provinsi' => ['nullable', 'string'],
            'kabupaten' => ['nullable', 'string'],
            'bidang' => ['nullable', 'string', 'max:100'],
        ];

        // Only validate password if it's provided
        if ($request->filled('password')) {
            $rules['password'] = ['confirmed', Rules\Password::defaults()];
        }

        $request->validate($rules);

        // Update guru data
        $guru->nama_pendidik = $request->nama_pendidik;
        $guru->email = $request->email;
        $guru->alamat = $request->alamat;
        $guru->no_telepon = $request->no_telepon;
        $guru->jenis_kelamin = $request->jenis_kelamin;
        $guru->tempat_lahir = $request->tempat_lahir;
        $guru->tanggal_lahir = $request->tanggal_lahir;
        $guru->pendidikan_terakhir = $request->pendidikan_terakhir;
        $guru->riwayat_pendidikan_keagamaan = $request->riwayat_pendidikan_keagamaan;
        $guru->provinsi = $request->provinsi;
        $guru->kabupaten = $request->kabupaten;
        $guru->bidang = $request->bidang;
        
        // Update password if provided
        if ($request->filled('password')) {
            $guru->password = Hash::make($request->password);
        }

        $guru->save();

        return redirect()->route('guru.index')
            ->with('success', 'Data guru berhasil diperbarui.');
    }

    public function importExcel(Request $request)
    {
        $request->validate([
            'file_import' => 'required|mimes:xlsx,xls'
        ]);

        try {
            Excel::import(new GuruImport, $request->file('file_import'));

            return redirect()->route('guru.index')->with('success', 'Data guru berhasil diimpor!');

        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            $errorMessages = [];
            foreach ($failures as $failure) {
                // Pesan error: "Ada kesalahan pada baris [nomor baris]: [pesan error]"
                $errorMessages[] = "Kesalahan pada baris " . $failure->row() . ": " . implode(', ', $failure->errors());
            }
            return redirect()->back()->with('error', 'Gagal mengimpor data. <br>' . implode('<br>', $errorMessages));
        } catch (\Exception $e) {
            // Tangkap error umum lainnya
            return redirect()->back()->with('error', 'Terjadi kesalahan saat mengimpor data: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $guru = Guru::findOrFail($id);
        $guru->delete();

        return redirect()->route('guru.index')
            ->with('success', 'Data guru berhasil dihapus.');
    }
}
