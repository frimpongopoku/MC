<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ShipmentNotification;
use Carbon\Carbon;
use App\CompleteShipment;
use App\Manager;
class MatchMaker extends Controller
{

  public $pairings;
    function __construct($shipment_notification_id){
      $this->id = $shipment_notification_id;
      $this->properties = ShipmentNotification::where('id',$shipment_notification_id)
      ->with('kitchenShipment','centerShipment')
      ->first();
      $this->managers  =Manager::where('center_id',$this->properties->center_id)->get();
    }
      function set_id($new_id){
      $this->id = $new_id;
    }
    function get_id(){
      return $this->id;
    }
    function deconstruct($desc){
      $first_coat = explode('<==>',$desc); 
      $final = [];
      foreach ($first_coat as $key => $value) {
        $temp = explode(':',$value);
        array_push($final,$temp);
      }
      return $final;
    }

    function furtherConstruction(){
     $kitchen =  $this->deconstruct($this->properties->kitchenShipment->description);
     $center = $this->deconstruct($this->properties->centerShipment->description);
     return [
       'kitchen'=>$kitchen,
       'center'=>$center,
     ];
   }
   function pair(){
     $group = $this->furtherConstruction();
     $kitchen = $group['kitchen'];
     $center = $group['center'];
     $pairs = []; 
     foreach ($kitchen as $key => $value) {
       $numbers_match = false;
       $c_pair =['name'=>'NA','single_price'=>0,'amount'=>0];
       $k_pair = [
         'name'=>$value[0],
         'single_price'=>$value[2],
         'amount'=>$value[1]
       ]; 
       foreach ($center as $i => $val) {
         if($value[0] ==$val[0]){
           $c_pair =  [
            'name'=>$val[0],
            'single_price'=>$val[2],
            'amount'=>$val[1]
          ]; 
          if($kitchen[$key][1] == $center[$i][1]){
            $numbers_match = true;
          }
         }
         
       }
       $paired = [
         'k'=>$k_pair,
         'c'=>$c_pair,
         'numbers_match'=>$numbers_match
       ];
       array_push($pairs,$paired);
     }
     $this->pairs = $pairs;
     return $pairs;

   }
   function itemToDifference(){
     $p = $this->pair(); 
     $itemDiffArr = [];
     foreach ($p as $key => $value) {
       $diff = $value['k']['amount'] - $value['c']['amount'];
       $diff = $diff;
       $name = $value['k']['name'];
       $price = $value['k']['single_price'];
       if($name == "NA"){
         //if centers count items that were not found in the kitchen [ very rare occurence but, "defensive" is good]
        $itemDiffArr[$value['c']['name']] = $diff.':'.$price; 
       }
       else{
        $itemDiffArr[$name] = $diff.':'.$price; 
       }
     }
     //returns this [ 'cake'=>'12:25','pie'=>'10:50'] -> [ item=> 'difference between kitchen and center counts ' : 'price']
     return $itemDiffArr;
   }
   function magnitude($n){
     if($n < 0 ){
       return $n * -1;
     }
     return $n;
   }
   function stringMismatchDetails(){//expects an item to diff arr like $itemDiffArr[] = $diff; 
    $arr = $this->itemToDifference();
    $string = ""; 
    foreach ($arr as $key => $value) {
      if($string ==""){
        $string = $key.':'.$value;
      }
      else{
        $string = $string.','.$key.':'.$value;
      }
    }
    return $string;
  }
   function check(){
     $pairings =  $this->pair();
     $flag = 0; 
     foreach ($pairings as $value) {
       if (!$value['numbers_match']){
         $flag ++;
         break;
       }
     }
     return [
       'pairings'=>$pairings,
       'flag'=>$flag
     ];
   }

   function start(){
    return $this->check();
   }
   
   function expectedAmount(){
     $pairings = $this->check()['pairings'];
     $kitchen_total = 0;
     $center_total = 0;
     foreach ($pairings as $key => $value) {
       $kitchen_total += $value['k']['single_price'] * $value['k']['amount'];
       $center_total += $value['c']['single_price'] * $value['c']['amount'];
     }
     return ['kitchen_estimate'=>$kitchen_total,
     'center_estimate'=>$center_total];
   }
//class bracket below
}
