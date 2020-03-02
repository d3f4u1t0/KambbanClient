<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequestType extends Model
{
    protected $table = 'requests_types';
    protected $fillable = [
      'name',
      'description',
      'attrs'
    ];

    public function request(){
        return $this->hasMany(Request::class);
    }
}
