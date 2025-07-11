<?php

namespace App\Models;

use Carbon\Carbon; // <-- WAJIB TAMBAHKAN INI
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pembayaran extends Model
{
    use HasFactory;

    protected $table = 'pembayaran';

    protected $fillable = [
        'user_id',
        'total_tagihan',
        'periode_bulan',
        'periode_tahun',
        'status',
        'is_cicilan',
        'admin_id_pembuat',
        'midtrans_order_id',
        'deskripsi',
        'jenis_pembayaran',
    ];

    protected $casts = [
        'is_cicilan' => 'boolean',
        'total_tagihan' => 'integer',
        'periode_bulan' => 'integer',
        'periode_tahun' => 'integer',
    ];

    // ============== TAMBAHKAN BLOK ACCESSOR & MUTATOR INI ==============
    /**
     * ACCESSOR: Membuat atribut virtual 'periode_pembayaran'
     * dari 'periode_bulan' dan 'periode_tahun' yang ada di database.
     */
    public function getPeriodePembayaranAttribute()
    {
        if ($this->periode_tahun && $this->periode_bulan) {
            return Carbon::createFromDate($this->periode_tahun, $this->periode_bulan, 1);
        }
        return null;
    }

    /**
     * MUTATOR: Memecah 'periode_pembayaran' menjadi 'periode_bulan' dan 'periode_tahun'
     * saat data disimpan.
     */
    public function setPeriodePembayaranAttribute($value)
    {
        if ($value) {
            $date = Carbon::parse($value);
            $this->attributes['periode_tahun'] = $date->year;
            $this->attributes['periode_bulan'] = $date->month;
        }
    }
    // ====================================================================

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function adminPembuat(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'admin_id_pembuat');
    }

    public function detailPembayaran(): HasMany
    {
        return $this->hasMany(DetailPembayaran::class);
    }
}