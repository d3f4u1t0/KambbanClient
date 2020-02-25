<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    public function requests(){
        return $this->hasMany(Request::class);
    }

    public function users(){
        return $this->hasMany(User::class);
    }

    public function workflow(){
       return $this->belongsTo(Workflow::class);
    }

    public function setLaraflowState(){

    }
}
