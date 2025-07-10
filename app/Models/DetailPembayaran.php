<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetailPembayaran extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'detail_pembayaran';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'pembayaran_id',
        'jumlah_dibayar',
        'tanggal_bayar',
        'metode_pembayaran',
        'bukti_pembayaran',
        'admin_id_pencatat',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'tanggal_bayar' => 'datetime',
        'jumlah_dibayar' => 'integer',
    ];

    /**
     * Get the pembayaran that owns this detail.
     */
    public function pembayaran(): BelongsTo
    {
        return $this->belongsTo(Pembayaran::class);
    }

    /**
     * Get the admin who recorded this payment detail.
     */
    public function adminPencatat(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'admin_id_pencatat');
    }
}
