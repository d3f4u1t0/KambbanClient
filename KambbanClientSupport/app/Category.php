<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    public function request(){
        return $this->belongsTo(Request::class);
    }
}
