<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'title',
        'image_path',
        'alt_text',
        'description',
        'active',
        'order'
    ];
    
    /**
     * Scope a query to only include active galleries.
     */
    public function scopeActive($query)
    {
        return $query->where('active', 1);
    }
    
    /**
     * Get the full image URL
     */
    public function getImageUrlAttribute()
    {
        // If the image path starts with http/https, it's an external URL
        if (filter_var($this->image_path, FILTER_VALIDATE_URL)) {
            return $this->image_path;
        }
        
        // Otherwise, it's a local file path
        return asset('storage/' . $this->image_path);
    }
}
