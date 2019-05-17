<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Kitchen; 
use App\Manager; 
use App\Admin; 
use App\Accountant; 
use App\Center;
use Session;
use App\Unit; 
use App\Pastry;
use App\KitchenShipment; 
use App\CenterShipment; 
use App\ShipmentNotification;
use Carbon\Carbon;
class AppEngineController extends Controller
{

    public function receiveValuesFromCenter(Request $request){
        $id = (int) explode(':]',$request->title)[0];
        $not = ShipmentNotification::where('id',$id)->first();
        $from_kitchen = Kitchen::where('id',$not->kitchen_id)->first();
        $new = new CenterShipment(); 
        $new->description = $request->description;
        $new->center_id = Session::get('center-auth')->id; 
        $new->kitchen_id = $not->kitchen_id;
        $new->kitchen_name = $from_kitchen->name;
        $new->save();
        $not->update([
            'center_shipment_id'=>$new->id,
            'center_sorted'=>1,
        ]);
    }
    /**
     * 
     * $request->description  Structure (Item<==>OtherItem<==>OtherItem) @string
     * Item Structure (item name:number of items:price of one of the items)
     * Eg. Cake:34:20 ( 34 cakes , and each cake costs 20 ksh)
     */
    public function receiveValuesFromKitchen(Request $request){
        $dest = Center::where('name',$request->destination)->first();
        $defaultTime = Carbon::now()->format('l jS \\of F Y h:i:s A');
        $new = new KitchenShipment();
        $new->description = $request->description; 
        $new->center_id = $dest->id;
        $new->center_name = $dest->name;
        $new->save();
        $notification = new ShipmentNotification(); 
        $notification->kitchen_shipment_id = $new->id; 
        $notification->kitchen_id = Session::get('cook-auth')->id; 
        $notification->title =" New Kitchen Shipment - ".$defaultTime;
        $notification->kitchen_sorted=1;
        $notification->center_id = $dest->id;
        $notification->save();
    }
}
