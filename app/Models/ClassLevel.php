<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ClassLevel extends Model
{
    protected $table = 'class_level';
    protected $fillable = ['level', 'spp', 'spp_beasiswa'];
    
    /**
     * Get the classes for this class level.
     */
    public function kelas(): HasMany
    {
        return $this->hasMany(Kelas::class);
    }
    
    /**
     * Get the users (santri) for this class level.
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
