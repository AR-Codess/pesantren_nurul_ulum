<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Absensi extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'absensi';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'kelas_id',
        'tanggal',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'tanggal' => 'date'
    ];

    /**
     * Get the santri (users) associated with this attendance record.
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'absensi_user')
            ->withPivot('status')
            ->withTimestamps();
    }

    /**
     * Get the gurus who manage this attendance record.
     */
    public function gurus(): BelongsToMany
    {
        return $this->belongsToMany(Guru::class, 'absensi_guru')
            ->withTimestamps();
    }

    /**
     * Get the kelas associated with this attendance record.
     */
    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class);
    }
}