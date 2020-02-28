<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserPermission extends Model
{
    protected $table='user_permissions';
    protected $fillable = [
        'permission',
        'description',
        'attrs'
    ];

    public function userTypes(){
        return $this->hasMany(UserType::class);
    }
}
