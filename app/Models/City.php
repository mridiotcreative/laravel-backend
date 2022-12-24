<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $table = 'city';

    protected $fillable = ['name'];

    protected $hidden = [
        'created_at', 'updated_at', 'state_id'
    ];

    public function state()
    {
        return $this->belongsTo(State::class);
    }
}
