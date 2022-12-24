<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostTag extends Model
{
    protected $fillable = ['title', 'slug', 'status'];

    // tag search scope
    public function scopeWhereLike($query, $column, $value) 
    {
        return $query->where($column, 'like', '%'.$value.'%');
    }

    // tag search scope
    public function scopeOrWhereLike($query, $column, $value) 
    {
        return $query->orWhere($column, 'like', '%'.$value.'%');
    }
}
