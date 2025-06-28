<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PembayaranController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pembayarans = Pembayaran::with('user')->latest()->get();
        return view('pembayaran.index', compact('pembayarans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::role('user')->get();
        return view('pembayaran.create', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'bulan' => 'required|string',
            'jumlah' => 'required|numeric',
            'status' => 'required|in:pending,confirmed,rejected',
            'tanggal' => 'required|date',
            'metode_pembayaran' => 'required|string',
        ]);

        Pembayaran::create($request->all());

        return redirect()->route('pembayaran.index')
            ->with('success', 'Pembayaran berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Pembayaran $pembayaran)
    {
        return view('pembayaran.show', compact('pembayaran'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pembayaran $pembayaran)
    {
        $users = User::role('user')->get();
        return view('pembayaran.edit', compact('pembayaran', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pembayaran $pembayaran)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'bulan' => 'required|string',
            'jumlah' => 'required|numeric',
            'status' => 'required|in:pending,confirmed,rejected',
            'tanggal' => 'required|date',
            'metode_pembayaran' => 'required|string',
        ]);

        $pembayaran->update($request->all());

        return redirect()->route('pembayaran.index')
            ->with('success', 'Pembayaran berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pembayaran $pembayaran)
    {
        $pembayaran->delete();

        return redirect()->route('pembayaran.index')
            ->with('success', 'Pembayaran berhasil dihapus.');
    }

    /**
     * Display payments for the authenticated user.
     */
    public function userIndex()
    {
        $pembayarans = Pembayaran::where('user_id', Auth::id())
            ->latest()
            ->get();
        
        return view('pembayaran.user', compact('pembayarans'));
    }
}