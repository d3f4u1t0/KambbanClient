<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use szana8\Laraflow\Traits\Flowable;

class Assignment extends Model
{

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

    }

    use Flowable;

    public function requests(){
        return $this->belongsTo(Request::class);
    }

    public function users(){
        return $this->belongsTo(User::class);
    }

    public function workflow(){
       return $this->belongsTo(Workflow::class);
    }

    public function getLaraflowState(){

    }
}
