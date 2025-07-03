<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BeritaController extends Controller
{
    /**
     * Display a gallery of berita items.
     */
    public function index()
    {
        // Get all berita items, newest first
        $beritaItems = Berita::orderBy('id', 'desc')->paginate(9);
        
        // Add image display information
        $beritaItems->each(function($item) {
            // Use the correct column name path_gambar
            $item->image_url = filter_var($item->path_gambar, FILTER_VALIDATE_URL)
                ? $item->path_gambar
                : asset('storage/' . $item->path_gambar);
            
            // Add properties for view compatibility
            $item->title = $item->judul;
            $item->description = $item->deskripsi;
            $item->slug = Str::slug($item->judul);
        });
        
        return view('berita.index', compact('beritaItems'));
    }

    /**
     * Display a specific berita item with SEO optimization.
     */
    public function show($id, $slug = null)
    {
        $berita = Berita::findOrFail($id);
        
        // Generate the canonical URL with the correct slug
        $correctSlug = Str::slug($berita->judul);
        
        // Add image display information
        $berita->image_url = filter_var($berita->path_gambar, FILTER_VALIDATE_URL)
            ? $berita->path_gambar
            : asset('storage/' . $berita->path_gambar);
        
        // Add properties for view compatibility
        $berita->title = $berita->judul;
        $berita->description = $berita->deskripsi;
        $berita->slug = $correctSlug;
        
        // Get related news items
        $relatedBerita = Berita::where('id', '!=', $berita->id)
            ->orderBy('id', 'desc')
            ->take(3)
            ->get()
            ->each(function($item) {
                $item->image_url = filter_var($item->path_gambar, FILTER_VALIDATE_URL)
                    ? $item->path_gambar
                    : asset('storage/' . $item->path_gambar);
                $item->title = $item->judul;
                $item->description = $item->deskripsi;
                $item->slug = Str::slug($item->judul);
            });
        
        // Redirect to the correct URL if the slug is wrong
        if ($slug !== null && $slug !== $correctSlug) {
            return redirect()->route('berita.show', ['id' => $berita->id, 'slug' => $correctSlug]);
        }
        
        return view('berita.show', compact('berita', 'relatedBerita'));
    }
}
