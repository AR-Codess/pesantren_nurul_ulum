<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Admin extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'department',
        'employee_id',
        'office_location',
    ];

    /**
     * Get the user associated with the admin.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
