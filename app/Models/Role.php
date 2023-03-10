<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = [
        'slug',
        'name',
    ];

    protected $hidden = [
        'created_at', 'updated_at'
    ];

    public $timestamps = true;
}
