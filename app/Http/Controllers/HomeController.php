<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Display the welcome page with dynamic content.
     */
    public function index()
    {
        // Get gallery items ordered by newest first
        $galleryItems = Gallery::orderBy('id', 'desc')->get();
        
        // Add image display information
        $galleryItems->each(function($item) {
            // Menggunakan nama kolom yang benar yaitu path_gambar
            $item->image_url = filter_var($item->path_gambar, FILTER_VALIDATE_URL)
                ? $item->path_gambar
                : asset('storage/' . $item->path_gambar);
            
            // Tambahkan property untuk kompatibilitas dengan view
            $item->title = $item->judul;
            $item->description = $item->deskripsi;
        });
        
        return view('welcome', compact('galleryItems'));
    }
}
