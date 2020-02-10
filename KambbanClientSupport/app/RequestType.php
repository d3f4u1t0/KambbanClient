<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RequestType extends Model
{
    protected $fillable = [
      'name'
    ];

    public function request(){
        return $this->hasMany(Request::class);
    }
}
