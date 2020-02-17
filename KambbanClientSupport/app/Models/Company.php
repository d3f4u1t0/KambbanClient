<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Company extends Model
{
    use Notifiable;
    protected $table = "companies";
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
