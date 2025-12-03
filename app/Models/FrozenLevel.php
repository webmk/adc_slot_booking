<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FrozenLevel extends Model
{
    protected $fillable = ['level', 'is_frozen'];
}
