<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Company extends Model
{
    use Notifiable;

    protected $fillable=[
        'name',
    ];

    public function externalCustomers(){
        return $this->hasMany(ExternalCustomer::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}
