<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ClassLevel;

class KelolaSppController extends Controller
{
    public function index()
    {
        $levels = ClassLevel::orderBy('level')->get();
        return view('kelola-spp', compact('levels'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'level' => 'required|string|max:10|unique:class_level,level',
            'spp' => 'required|integer|min:0',
            'spp_beasiswa' => 'nullable|integer|min:0',
        ]);
        return redirect()->route('kelola-spp')->with('success', 'Level kelas berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'spp' => 'required|integer|min:0',
            'spp_beasiswa' => 'nullable|integer|min:0',
        ]);
        $level = ClassLevel::findOrFail($id);
        $level->update([
            'spp' => $request->spp,
            'spp_beasiswa' => $request->spp_beasiswa,
        ]);
        return redirect()->route('kelola-spp')->with('success', 'Nominal SPP berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $level = ClassLevel::findOrFail($id);
        $level->delete();
        return redirect()->route('kelola-spp')->with('success', 'Level kelas berhasil dihapus!');
    }
}
