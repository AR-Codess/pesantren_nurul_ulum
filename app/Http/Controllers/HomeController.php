<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Display the welcome page with dynamic content.
     */
    public function index()
    {
        // Get berita items ordered by newest first
        $beritaItems = Berita::orderBy('id', 'desc')->get();
        
        // Add image display information
        $beritaItems->each(function($item) {
            // Menggunakan nama kolom yang benar yaitu path_gambar
            $item->image_url = filter_var($item->path_gambar, FILTER_VALIDATE_URL)
                ? $item->path_gambar
                : asset('storage/' . $item->path_gambar);
            
            // Tambahkan property untuk kompatibilitas dengan view
            $item->title = $item->judul;
            $item->description = $item->deskripsi;
        });
        
        return view('welcome', compact('beritaItems'));
    }
}
