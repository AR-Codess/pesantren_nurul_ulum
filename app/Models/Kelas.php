<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kelas extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nama_kelas',
        'tahun_ajaran',
        'guru_id',
    ];

    /**
     * Get the guru who manages this class.
     */
    public function guru(): BelongsTo
    {
        return $this->belongsTo(Guru::class);
    }

    /**
     * Get the santri (users) enrolled in this class.
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'kelas_user');
    }

    /**
     * Get the attendance records for this class.
     */
    public function absensi(): HasMany
    {
        return $this->hasMany(Absensi::class);
    }
}
