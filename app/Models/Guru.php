<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Guru extends Model
{
    use HasFactory;
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'gurus';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'bidang',
    ];

    /**
     * Get the user that owns the guru.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
