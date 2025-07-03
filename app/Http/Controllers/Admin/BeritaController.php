<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Berita;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BeritaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $berita = Berita::orderBy('id', 'desc')->get();
        return view('admin.berita.index', compact('berita'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.berita.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'alt_text' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        // Handle image upload
        $path = $request->file('image')->store('berita', 'public');

        Berita::create([
            'judul' => $request->title,
            'path_gambar' => $path,
            'deskripsi' => $request->description,
            'admin_id' => auth()->id(), // Pastikan admin_id terisi
        ]);

        return redirect()->route('admin.berita.index')->with('success', 'Berita berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $berita = Berita::findOrFail($id);
        return view('admin.berita.show', compact('berita'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $berita = Berita::findOrFail($id);
        return view('admin.berita.edit', compact('berita'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $berita = Berita::findOrFail($id);
        
        $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'alt_text' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        $data = [
            'judul' => $request->title,
            'deskripsi' => $request->description,
        ];

        // Handle image upload if a new image is provided
        if ($request->hasFile('image')) {
            // Delete the old image if it exists and is not a URL
            if ($berita->path_gambar && !filter_var($berita->path_gambar, FILTER_VALIDATE_URL)) {
                Storage::disk('public')->delete($berita->path_gambar);
            }
            
            $data['path_gambar'] = $request->file('image')->store('berita', 'public');
        }

        $berita->update($data);

        return redirect()->route('admin.berita.index')->with('success', 'Berita berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $berita = Berita::findOrFail($id);
        
        // Delete the image file if it's not a URL
        if (!filter_var($berita->path_gambar, FILTER_VALIDATE_URL)) {
            Storage::disk('public')->delete($berita->path_gambar);
        }
        
        $berita->delete();

        return redirect()->route('admin.berita.index')->with('success', 'Berita berhasil dihapus.');
    }

    /**
     * Update the order of berita items.
     * 
     * Catatan: Karena tabel berita tidak memiliki kolom 'order',
     * fungsi ini telah disesuaikan untuk tidak melakukan update.
     */
    public function updateOrder(Request $request)
    {
        // Fungsi ini dipertahankan untuk kompatibilitas dengan frontend yang mungkin masih memanggil endpoint ini
        // tetapi tidak melakukan update karena kolom order tidak ada
        
        return response()->json(['success' => true]);
    }
}
