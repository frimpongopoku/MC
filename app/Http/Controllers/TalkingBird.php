<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Gossip; 
class TalkingBird extends Controller
{
    public function reportMismatch($center,$kitchen,$shipment_title,$center_shipment_values,$kitchen_shipment_values,$details){
        $msg = "Values from $center->name and $kitchen->name did not match. \n$center->name recorded: $center_shipment_values and \n$kitchen->name: recorded: $kitchen_shipment_values\n Title: '$shipment_title'";
        $talk = new Gossip(); 
        $talk->type = "mismatch"; 
        $talk->description = trim($msg);  
        $talk->details = $details;
        $talk->save();
        return 1;
    }
}
