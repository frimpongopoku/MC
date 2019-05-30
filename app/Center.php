<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Center extends Model
{
    public function manager(){
      return $this->belongsTo('App\Manager');
    }
}
