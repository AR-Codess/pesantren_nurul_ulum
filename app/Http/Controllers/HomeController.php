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
        // Get active gallery items ordered by the order field
        $galleryItems = Gallery::where('active', true)
                              ->orderBy('order', 'asc')
                              ->get();
        
        // Add image display information
        $galleryItems->each(function($item) {
            $item->image_url = filter_var($item->image_path, FILTER_VALIDATE_URL) 
                ? $item->image_path 
                : asset('storage/' . $item->image_path);
        });
        
        return view('welcome', compact('galleryItems'));
    }
}
