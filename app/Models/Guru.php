<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Guru extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'guru';
    
    /**
     * The guard name that this model uses.
     *
     * @var string
     */
    protected $guard_name = 'guru';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nik',
        'nama_pendidik',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'pendidikan_terakhir',
        'riwayat_pendidikan_keagamaan',
        'email',
        'password',
        'no_telepon',
        'provinsi',
        'kabupaten',
        'alamat',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'tanggal_lahir' => 'date',
            'jenis_kelamin' => 'boolean',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the kelas that the guru manages.
     */
    public function kelas(): HasMany
    {
        return $this->hasMany(Kelas::class);
    }

    /**
     * Get the absensi records created by this guru.
     */
    public function absensi(): HasMany
    {
        return $this->hasMany(Absensi::class);
    }
}
