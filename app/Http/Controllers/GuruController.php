<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Spatie\Permission\Models\Role;

class GuruController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Get all guru users with the 'guru' role
        $gurus = User::role('guru')->latest()->get();
        return view('admin.guru.index', compact('gurus'));
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
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'nip' => ['required', 'string', 'max:20', 'unique:users,nis'], // Using NIS field for NIP
            'alamat' => ['nullable', 'string'],
            'no_hp' => ['nullable', 'string', 'max:15'],
            'bidang' => ['nullable', 'string', 'max:100'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'nis' => $request->nip, // Using NIS field for NIP
            'alamat' => $request->alamat,
            'no_hp' => $request->no_hp,
        ]);

        // Create guru details if we have a Guru model with additional fields
        if ($request->filled('bidang')) {
            Guru::create([
                'user_id' => $user->id,
                'bidang' => $request->bidang
            ]);
        }

        // Assign the 'guru' role
        $user->assignRole('guru');

        return redirect()->route('guru.index')
            ->with('success', 'Data guru berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::findOrFail($id);
        return view('admin.guru.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::findOrFail($id);
        $guru = Guru::where('user_id', $user->id)->first();
        return view('admin.guru.edit', compact('user', 'guru'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);
        
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.$id],
            'nip' => ['required', 'string', 'max:20', 'unique:users,nis,'.$id], // Using NIS field for NIP
            'alamat' => ['nullable', 'string'],
            'no_hp' => ['nullable', 'string', 'max:15'],
            'bidang' => ['nullable', 'string', 'max:100'],
        ];

        // Only validate password if it's provided
        if ($request->filled('password')) {
            $rules['password'] = ['confirmed', Rules\Password::defaults()];
        }

        $request->validate($rules);

        // Update user data
        $user->name = $request->name;
        $user->email = $request->email;
        $user->nis = $request->nip; // Using NIS field for NIP
        $user->alamat = $request->alamat;
        $user->no_hp = $request->no_hp;
        
        // Update password if provided
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();
        
        // Update guru details
        if ($request->filled('bidang')) {
            $guru = Guru::firstOrCreate(['user_id' => $user->id]);
            $guru->bidang = $request->bidang;
            $guru->save();
        }

        return redirect()->route('guru.index')
            ->with('success', 'Data guru berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        
        // Delete guru details if exists
        Guru::where('user_id', $user->id)->delete();
        
        // Delete user
        $user->delete();

        return redirect()->route('guru.index')
            ->with('success', 'Data guru berhasil dihapus.');
    }
}
