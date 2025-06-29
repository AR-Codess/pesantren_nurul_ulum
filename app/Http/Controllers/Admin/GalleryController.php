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
        $galleries = Gallery::orderBy('order', 'asc')->get();
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
        $path = $request->file('image')->store('galleries', 'public');

        // Find the highest order value
        $maxOrder = Gallery::max('order');

        Gallery::create([
            'title' => $request->title,
            'image_path' => $path,
            'alt_text' => $request->alt_text ?? $request->title,
            'description' => $request->description,
            'active' => $request->has('active') ? 1 : 0,
            'order' => $maxOrder + 1,
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
            'title' => $request->title,
            'alt_text' => $request->alt_text ?? $request->title,
            'description' => $request->description,
            'active' => $request->has('active') ? 1 : 0,
        ];

        // Handle image upload if a new image is provided
        if ($request->hasFile('image')) {
            // Delete the old image if it exists and is not a URL
            if ($gallery->image_path && !filter_var($gallery->image_path, FILTER_VALIDATE_URL)) {
                Storage::disk('public')->delete($gallery->image_path);
            }
            
            $data['image_path'] = $request->file('image')->store('galleries', 'public');
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
        if (!filter_var($gallery->image_path, FILTER_VALIDATE_URL)) {
            Storage::disk('public')->delete($gallery->image_path);
        }
        
        $gallery->delete();

        return redirect()->route('admin.gallery.index')->with('success', 'Gambar berhasil dihapus.');
    }

    /**
     * Update the order of gallery items.
     */
    public function updateOrder(Request $request)
    {
        $items = $request->items;
        
        foreach ($items as $item) {
            Gallery::find($item['id'])->update(['order' => $item['order']]);
        }
        
        return response()->json(['success' => true]);
    }
}
