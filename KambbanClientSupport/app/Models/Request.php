<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Request extends Model
{

    protected $dates = ['date_create'];
    protected $table = 'request';
    protected $fillable=[
      'request',
      'user_id',
      'request_type_id',
      'category_id',
      'status'
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
