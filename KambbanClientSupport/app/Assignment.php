<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    public function requests(){
        return $this->hasMany(Request::class);
    }

    public function users(){
        return $this->hasMany(User::class);
    }
}
