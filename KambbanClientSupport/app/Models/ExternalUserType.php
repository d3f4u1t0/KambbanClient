<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExternalUserType extends Model
{
    protected $table='external_user_types';
    protected $fillable=[
        'external_user_type',
        'status',
        'attrs',
        'permission_id'
    ];

    public function permissionsExternalUser(){
        return $this->belongsTo(ExternalUserPermission::class);
    }

    public function externalUsers(){
        return $this->hasMany();
    }
}
