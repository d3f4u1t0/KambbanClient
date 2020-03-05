<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    public function requests(){
        return $this->belongsTo(Request::class);
    }

    public function users(){
        return $this->belongsTo(User::class);
    }

    public function workflow(){
       return $this->belongsTo(Workflow::class);
    }

    public function setLaraflowState(){

    }
}
