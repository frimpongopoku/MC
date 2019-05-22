<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ShipmentNotification extends Model
{
    protected $guarded =[];

    public function kitchenShipment(){
        return $this->hasOne('App\KitchenShipment');
    }
    public function centerShipment(){
        return $this->hasOne('App\CenterShipment');
    }
}
