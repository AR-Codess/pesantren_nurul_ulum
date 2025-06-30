<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Berita extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'judul',
        'slug',
        'isi_berita',
        'gambar_utama',
        'status',
        'admin_id',
        'published_at'
    ];
    
    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'published_at' => 'datetime'
    ];
    
    /**
     * Get the admin who created this news article.
     */
    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class);
    }
    
    /**
     * Scope a query to only include published news.
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'Published')
                    ->whereNotNull('published_at')
                    ->where('published_at', '<=', now());
    }
}
