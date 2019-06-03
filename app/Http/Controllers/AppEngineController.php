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
use App\CompleteShipment;
use App\Http\Controllers\MatchMaker;
use App\Http\Controllers\HTMLEngineGenerator;
class AppEngineController extends Controller
{

	
	function getDifference(){
		$ships = CompleteShipment::where('sorted',1)->take(300)->get();
		$labels = []; 
		$data = []; 
		$colors  =[]; 
		foreach ($ships as $key => $value) {
			$ex = explode('-',$value->title); 
			$diff = $value->expected_amount - $value->received_amount;
			array_push($data,$diff);
			array_push($colors,$this->generateRandomColor());
			array_push($labels,$ex[1]);
		}
		
		return [
			'labels'=>$labels,
			'datasets'=>[ 
					[
					'label'=> ' KES ',
					'data'=>$data, 
					'backgroundColor'=>$colors
					]
				]
			];
		
	}
	function salesPerShipment(){
		$ships = CompleteShipment::where('sorted',1)->take(300)->get();
		$labels = []; 
		$data = []; 
		$colors  =[]; 
		foreach ($ships as $key => $value) {
			$ex = explode('-',$value->title); 
			array_push($data,$value->received_amount);
			array_push($colors,$this->generateRandomColor());
			array_push($labels,$ex[1]);
		}

		return [
			'labels'=>$labels,
			'datasets'=>[ 
					[
					'label'=> ' KES ',
					'data'=>$data, 
					'backgroundColor'=>$colors
					]
				]
			];
		
	}
	public function goToAdminStats(){
		if(Session::has('admin-auth')){
			$datasets = $this->fetchShipmentStats();
			$sales_per_shipment = $this->salesPerShipment();
			$diff = $this->getDifference();
			return view('admin.stats',compact('diff','datasets','sales_per_shipment'));

		}
		else{
			return redirect('/admin');
		}
	}
		public function fetchShipmentStats(){
			$deconstructed = $this->deconstruct(); 
			$sets = $this->getDatasets($deconstructed['names'],$deconstructed['totals']);
			return
			[
				'labels'=>$deconstructed['titles'],
				'datasets'=>$sets,
			];
		}

		function generateRandomColor(){
			$letters = '0123456789ABCDEF';
			$color = '#';
			for ($i=0; $i < 6 ; $i++) { 
				$color .= $letters[rand(0,15)];
			}
			return $color;
		}
		function getDatasets($names,$totals){//expects an array of item names, and an array of arrays that correspond to the values in the name array
			$data = [];
			foreach ($names as  $key => $value) {
				$set = $this->groomValue($value,$totals[$key]);
				array_push($data,$set);
			}
			return $data;
		}
		function groomValue($name,$totalArray){
			return [
				'data'=>$totalArray,
				'label'=>$name,
				'borderColor'=>$this->generateRandomColor(),
				'fill'=>false
			];
		}
		function elementize($desc){
			$arr = explode(',',$desc); 
			$n_array= []; 
			foreach ($arr as  $value) {
				array_push($n_array,explode(':',$value)); 
			}
			return $n_array;
		}

		function search($string,$array){
			foreach ($array as $i => $value) {
				if($string ==$value){
					return  $i;
				}
			}
			return -1;
		}
		function zeroedArray($length,$value){
			//expects length = how many zeroes should lead in the array
			//value = the item that should come after the zeroes
			$temp = [];
			if($length !==0){
				for ($i=0; $i < $length; $i++) { 
					array_push($temp,0);
				}
			}
			array_push($temp,$value);
			return $temp;
		}
		 function deconstruct(){//receieves a string value of compressed kitchen items
			$list = CompleteShipment::where('sorted',1)->get(); 
			$n_list = []; 
			$v_arr =[]; //an array of arrays
			$title_list = []; 
			foreach ($list as  $key => $value) {
				$ex = explode("-",$value->title);
				array_push($title_list,$ex[1]);
				$el =$this->elementize($value->received_description);
				foreach($el as $val){
					$name = $val[0]; 
					$total = $val[2];
					$key_found = $this->search($name,$n_list);
					if($key_found !== -1 ){
						$arr_val =$v_arr[$key_found]; 
						$length = count($arr_val);
						if($length == $key){
							//normal flow, just push it on
							array_push($v_arr[$key_found],$total);
						}
						else{
							$diff =$key - $length; 
							$zeroed_set = $this->zeroedArray($diff,$total);
							$new_array = array_merge($arr_val,$zeroed_set);
							$v_arr[$key_found] = $new_array;
						}
					}else{
						array_push($n_list,$name); 
						array_push($v_arr,$this->zeroedArray($key,$total));
					}
				}
			}
			return [
				'titles'=>$title_list,
				'names'=>$n_list,
				'totals'=>$v_arr
			];
		}
		public function metExpectations(Request $r){
			$f = CompleteShipment::where('id',$r->id)->first(); 
			$f->update([
				'accountant_id'=>Session::get('acc-auth')->id,
				'received_amount'=>$f->expected_amount,
				'received_description'=>$f->description,
				'sorted'=>1
			]);
			return 'true';
		}
		public function didntMeetExpectations(Request $request){
			$f = CompleteShipment::where('id',$request->id)->first(); 
			$f->update([
				'accountant_id'=>Session::get('acc-auth')->id,
				'received_description'=>$request->received_description,
				'received_amount'=>$request->received_amount,
				'sorted'=>1
			]);
			return 'true';
		}
		public function rectByManager(Request $request){
			$not = ShipmentNotification::where('id',$request->id)->first(); 
			$k = KitchenShipment::where('id',$not->kitchen_shipment_id)->first();
			$c = CenterShipment::where('id',$not->center_shipment_id)->first();
			$k->update(['description'=>$request->kitchen_description]); 
			$c->update(['description'=>$request->center_description]);
			return 'true';
		}
		function prepDescForCompletion($desc){//returns compressed string
			//take format (item:amount:price) and change to (item:price:total)
			$desc = explode('<==>',$desc); 
			$full ="";
			foreach ($desc as $key => $value) {
				$val = explode(':',$value);
				$string =$val[0].':'.$val[2].':'.$val[1]*$val[2]; 
				if($full ==""){
					$full = $string;
				}
				else{
					$full = $full.','.$string;
				}

			}
			return $full;
		}
		function notifyManagers($shipment_notification_id){
		 $headers = "From: MCSystems\r\n";
			 $headers .= "MIME-Version: 1.0\r\n";
			$headers .= "Content-Type: text/html; charset=UTF-8\r\n";
			$match = new MatchMaker($shipment_notification_id);
			$results = $match->start();
			$title = $match->properties->title;
			$desc = $match->properties->kitchenShipment->description;
			if($results['flag'] == 0 ){
				foreach ($match->managers as $man) {
					$htmlGen = new HTMLEmailGenerator(
						$match->properties->id,
						$this->prepDescForCompletion($desc),
						$match->expectedAmount()['kitchen_estimate'],
						$match->properties->kitchen,
						$match->properties->center,
						$man->name,
						$results['pairings']
					);
					$msg  = $htmlGen->generateHtml(1);
					mail($man->email,"$title - Turn Out - [ Match ]",$msg,$headers);
				}
			}
			else{
				foreach ($match->managers as $man) {
					$htmlGen = new HTMLEmailGenerator(
						$match->properties->id,
						$this->prepDescForCompletion($desc),
						$match->expectedAmount()['kitchen_estimate'],
						$match->properties->kitchen,
						$match->properties->center,
						$man->name,
						$results['pairings']
					);
					$msg  = $htmlGen->generateHtml(0);
					mail($man->email,"$title - Turn Out - [ Mismatch ]",$msg,$headers);
				}
			}
			return $msg;
		}
    public function receiveValuesFromCenter(Request $request){
        $id = (int) explode(':',$request->title)[0];
        $not = ShipmentNotification::where('id',$id)->first();
        $from_kitchen = Kitchen::where('id',$not->kitchen_id)->first();
        $new = new CenterShipment(); 
        $new->description = $request->description;
        $new->center_id = Session::get('center-auth')->id; 
        $new->kitchen_id = $not->kitchen_id;
        $new->kitchen_name = $from_kitchen->name;
        $new->shipment_notification_id = $not->id;
        $new->save();
        $not->update([
            'center_shipment_id'=>$new->id,
            'center_sorted'=>1,
				]);
				$this->notifyManagers($not->id);
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
        $new->update(['shipment_notification_id'=>$notification->id]);
    }
}
