<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    protected $table = 'state';

    protected $fillable = ['name'];

    protected $hidden = [
        'created_at', 'updated_at'
    ];

    public function city()
    {
        return $this->hasMany(City::class);
    }
}
