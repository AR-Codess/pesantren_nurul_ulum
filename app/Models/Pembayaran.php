<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pembayaran extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'pembayaran';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'total_tagihan',
        'periode_pembayaran',
        'status',
        'is_cicilan',
        'admin_id_pembuat',
        'midtrans_order_id',
        'deskripsi',
        'jenis_pembayaran',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'periode_pembayaran' => 'date',
        'is_cicilan' => 'boolean',
        'total_tagihan' => 'integer',
    ];

    /**
     * Get the santri (user) that owns the payment.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the admin who created this payment record.
     */
    public function adminPembuat(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'admin_id_pembuat');
    }

    /**
     * Get the payment details for this payment.
     */
    public function detailPembayaran(): HasMany
    {
        return $this->hasMany(DetailPembayaran::class);
    }
}