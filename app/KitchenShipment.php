<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class KitchenShipment extends Model
{

  protected $guarded =[];
    public function center(){
      return $this->hasOne('App\Center','id');
    }
}
