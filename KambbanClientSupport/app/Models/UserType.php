<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserType extends Model
{
    protected $table = 'users_types';
    protected $fillable = [
      'name',
      'status',
      'attrs'
    ];

    public function user(){
        return $this->hasMany(User::class);
    }

    public function permissions(){
        return $this->belongsTo(UserPermission::class);
    }
}
