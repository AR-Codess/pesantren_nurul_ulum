<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ClassLevel extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'class_level';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'level',
        'spp'
    ];

    /**
     * The users that belong to this class level.
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * The classes that belong to this level.
     */
    public function kelas(): HasMany
    {
        return $this->hasMany(Kelas::class);
    }
}