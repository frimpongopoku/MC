<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Manager extends Model
{
    public function center(){
        return $this->belongsTo('App\Center');
    }
}
