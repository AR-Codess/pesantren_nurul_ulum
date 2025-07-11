<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Vinkla\Hashids\Facades\Hashids;

class Berita extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang digunakan model ini.
     * @var string
     */
    protected $table = 'berita';

    /**
     * The attributes that are mass assignable.
     * @var array<int, string>
     */
    protected $fillable = [
        'judul',
        'path_gambar',
        'deskripsi',
        'admin_id',
        'urut',
    ];

    /**
     * The attributes that should be cast.
     * Ini memastikan tipe data selalu konsisten.
     * @var array<string, string>
     */
    protected $casts = [
        'admin_id' => 'integer',
        'urut' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Mendapatkan URL gambar secara penuh.
     * @return string
     */
    public function getImageUrlAttribute(): string
    {
        // Jika path_gambar adalah URL valid, kembalikan langsung.
        if (filter_var($this->path_gambar, FILTER_VALIDATE_URL)) {
            return $this->path_gambar;
        }

        // Jika tidak, gunakan path dari storage.
        return asset('storage/' . $this->path_gambar);
    }
    
    /**
     * Relasi ke model Admin yang membuat berita.
     */
    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class);
    }
    
    /**
     * Membuat atribut virtual 'hashed_id'.
     * @return string
     */
    public function getHashedIdAttribute(): string
    {
        return Hashids::encode($this->id);
    }
    
    /**
     * Mengoverride key default (id) untuk Route Model Binding.
     * @return string
     */
    public function getRouteKeyName(): string
    {
        return 'hashed_id';
    }

    /**
     * Logika untuk mencari model berdasarkan hashed_id saat menggunakan Route Model Binding.
     *
     * @param  mixed  $value
     * @param  string|null  $field
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function resolveRouteBinding($value, $field = null)
    {
        // Decode hash yang diterima dari URL
        $decoded_id = Hashids::decode($value);
        
        // Cari model berdasarkan id yang sudah di-decode
        return $this->where('id', $decoded_id[0] ?? null)->firstOrFail();
    }
}