<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExternalUserPermission extends Model
{
    protected $table='external_user_permissions';
    protected $fillable=[
        'permission',
        'description',
        'attrs'
    ];

    public function externalUserTypes(){

    }
}
