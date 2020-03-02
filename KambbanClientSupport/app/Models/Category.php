<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'name',
        'description',
        'attrs'
    ];

    public function request(){
        return $this->hasMany(Request::class);
    }
}
