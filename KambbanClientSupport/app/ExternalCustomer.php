<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExternalCustomer extends Model
{
    public function company(){
        return $this->belongsTo(Company::class);
    }
}
