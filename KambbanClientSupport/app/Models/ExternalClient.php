<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExternalClient extends Model
{
    protected $table='external_clients';
    protected $fillable=[
      'name',
      'nit',
      'attrs',
      'internal_client_id'
    ];

    public function internalClient(){
        return $this->belongsTo(InternalClient::class);
    }
}
