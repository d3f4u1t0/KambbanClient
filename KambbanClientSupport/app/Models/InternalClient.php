<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InternalClient extends Model
{
    protected $table='internal_clients';
    protected $fillable=[
      'name',
      'nit',
      'attrs',

    ];

    public function externalClients(){
        return $this->hasMany(ExternalClient::class);
    }
}
