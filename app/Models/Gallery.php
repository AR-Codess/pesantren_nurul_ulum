<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Gallery extends Model
{
    use HasFactory;
    
    /**
     * Nama tabel yang digunakan model ini.
     *
     * @var string
     */
    protected $table = 'galeri';
    
    protected $fillable = [
        'judul',
        'path_gambar',
        'deskripsi',
        'admin_id'
    ];
    
    /**
     * Get the admin who created this gallery item.
     */
    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class);
    }
    
    /**
     * Get the full image URL
     */
    public function getImageUrlAttribute()
    {
        // If the image path starts with http/https, it's an external URL
        if (filter_var($this->path_gambar, FILTER_VALIDATE_URL)) {
            return $this->path_gambar;
        }
        
        // Otherwise, it's a local file path
        return asset('storage/' . $this->path_gambar);
    }
}
