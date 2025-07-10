<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Admin extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'admin';

    /**
     * The guard name that this model uses.
     *
     * @var string
     */
    protected $guard_name = 'admin';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
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
            'password' => 'hashed',
        ];
    }

    /**
     * Get the news articles created by this admin.
     */
    public function beritas(): HasMany
    {
        return $this->hasMany(Berita::class);
    }

    /**
     * Get the payment records created by this admin.
     */
    public function pembayaranCreate(): HasMany
    {
        return $this->hasMany(Pembayaran::class, 'admin_id_pembuat');
    }

    /**
     * Get the payment detail records recorded by this admin.
     */
    public function detailPembayaran(): HasMany
    {
        return $this->hasMany(DetailPembayaran::class, 'admin_id_pencatat');
    }
}
