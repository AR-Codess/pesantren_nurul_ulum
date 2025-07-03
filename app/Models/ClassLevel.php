<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClassLevel extends Model
{
    protected $table = 'class_level';
    protected $fillable = ['level', 'spp', 'spp_beasiswa'];
}
