<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Request extends Model
{

    protected $dates = ['date_create'];

    protected $fillable=[
      'request',
      'status',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function category(){
        return $this->hasMany(Category::class);
    }

    public function assignment(){
        return $this->belongsTo(Assignment::class);
    }

}
