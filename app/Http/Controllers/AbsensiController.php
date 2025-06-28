<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Absensi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AbsensiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $absensis = Absensi::with('user')->latest()->get();
        return view('absensi.index', compact('absensis'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::role('user')->get();
        return view('absensi.create', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'tanggal' => 'required|date',
            'status' => 'required|in:hadir,izin,sakit,alpha',
        ]);

        Absensi::create($request->all());

        return redirect()->route('absensi.index')
            ->with('success', 'Absensi berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Absensi $absensi)
    {
        return view('absensi.show', compact('absensi'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Absensi $absensi)
    {
        $users = User::role('user')->get();
        return view('absensi.edit', compact('absensi', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Absensi $absensi)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'tanggal' => 'required|date',
            'status' => 'required|in:hadir,izin,sakit,alpha',
        ]);

        $absensi->update($request->all());

        return redirect()->route('absensi.index')
            ->with('success', 'Absensi berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Absensi $absensi)
    {
        $absensi->delete();

        return redirect()->route('absensi.index')
            ->with('success', 'Absensi berhasil dihapus.');
    }

    /**
     * Show attendance records for the authenticated user.
     */
    public function check()
    {
        $absensis = Absensi::where('user_id', Auth::id())
            ->latest()
            ->get();
            
        return view('absensi.check', compact('absensis'));
    }
}