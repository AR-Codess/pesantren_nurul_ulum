<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GalleryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $galleries = Gallery::orderBy('id', 'desc')->get();
        return view('admin.gallery.index', compact('galleries'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.gallery.create');
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
        $path = $request->file('image')->store('galeri', 'public');

        Gallery::create([
            'judul' => $request->title,
            'path_gambar' => $path,
            'deskripsi' => $request->description,
            'admin_id' => auth()->id(), // Pastikan admin_id terisi
        ]);

        return redirect()->route('admin.gallery.index')->with('success', 'Gambar berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $gallery = Gallery::findOrFail($id);
        return view('admin.gallery.show', compact('gallery'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $gallery = Gallery::findOrFail($id);
        return view('admin.gallery.edit', compact('gallery'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $gallery = Gallery::findOrFail($id);
        
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
            if ($gallery->path_gambar && !filter_var($gallery->path_gambar, FILTER_VALIDATE_URL)) {
                Storage::disk('public')->delete($gallery->path_gambar);
            }
            
            $data['path_gambar'] = $request->file('image')->store('galeri', 'public');
        }

        $gallery->update($data);

        return redirect()->route('admin.gallery.index')->with('success', 'Gambar berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $gallery = Gallery::findOrFail($id);
        
        // Delete the image file if it's not a URL
        if (!filter_var($gallery->path_gambar, FILTER_VALIDATE_URL)) {
            Storage::disk('public')->delete($gallery->path_gambar);
        }
        
        $gallery->delete();

        return redirect()->route('admin.gallery.index')->with('success', 'Gambar berhasil dihapus.');
    }

    /**
     * Update the order of gallery items.
     * 
     * Catatan: Karena tabel galeri tidak memiliki kolom 'order',
     * fungsi ini telah disesuaikan untuk tidak melakukan update.
     */
    public function updateOrder(Request $request)
    {
        // Fungsi ini dipertahankan untuk kompatibilitas dengan frontend yang mungkin masih memanggil endpoint ini
        // tetapi tidak melakukan update karena kolom order tidak ada
        
        return response()->json(['success' => true]);
    }
}
