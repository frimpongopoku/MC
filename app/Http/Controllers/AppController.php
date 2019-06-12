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
use App\ShipmentNotification;
use App\CenterShipment;
use App\CompleteShipment;
use PDF;
use Carbon\Carbon;
use App\Gossip;
class AppController extends Controller
{

	public function goToMismatches(){
		$gossips = Gossip::where('type','mismatch')->orderBy('id','DESC')->take(150)->get();
		return view('admin.mismatch',compact('gossips'));
	}
	public function downloadMismatches(){
		$carbonized = new Carbon();
		$humanized = $carbonized->format('l\\, jS \\of F Y');
	  $pdf = \PDF::loadHTML($this->generateMismatchHtml());
	  return $pdf->download("Mismatch records download - $humanized");
	}
	public function downloadCompleteShipments(){
		$carbonized = new Carbon();
		$humanized = $carbonized->format('l\\, jS \\of F Y');
		$pdf = \PDF::loadHTML($this->generateCompHtml());
		return $pdf->download("Complete Shipment records download - $humanized");
	}
	public function downloadShipmentRecords($which){
		$carbonized = new Carbon();
		$humanized = $carbonized->format('l\\, jS \\of F Y');
		$pdf = \PDF::loadHTML($this->generateLogsHtml($which));
		return $pdf->download("$which records download - $humanized");
	}
	function stringifyB($desc){
		$sent = ""; 
		foreach(explode(",",$desc) as $k){
			$temp = explode(':',$k);
			$itemName = $temp[0]; 
			$amount = $temp[1]; 
			if($sent == ""){
				$sent = $itemName.' '.$amount; 
			}
			else{
				$sent = $sent.', '.$itemName.' '.$amount;
			}
		}
		return $sent;
	}
	function stringify($desc){
		$sent = ""; 
		foreach(explode("<==>",$desc) as $k){
			$temp = explode(':',$k);
			$itemName = $temp[0]; 
			$amount = $temp[1]; 
			if($sent == ""){
				$sent = $itemName.' '.$amount; 
			}
			else{
				$sent = $sent.', '.$itemName.' '.$amount;
			}
		}
		return $sent;
	}
	public function generateCompHtml(){
		$logs = completeShipment::where('sorted',1)->orderBy('id','DESC')->get(); 
    $number= count($logs);
    $list ="";
    foreach($logs as $log){
      $carbonized = new Carbon($log->created_at);
			$humanized = $carbonized->format('l\\, jS \\of F Y');
			$items = $this->stringifyB($log->description);
			$diff = $log->expected_amount - $log->received_amount;
      $list = $list."<p style='border:solid 1px #ccc; font-size:small;padding:10px;border-radius:10px;'>
            <span><b>#$log->id</b></span>
							$items
							<br/>
            <small style='color:darkgreen'>Expected: <b>$log->expected_amount</b></small><br/>
            <small style='color:mediumseagreen'>Received: <b>$log->received_amount</b></small><br/>
            <small style='color:darkgoldenrod'>Difference: <b>$diff</b></small><br/>
            <small style='color:darkred'><b>$humanized</b></small>
          </p>";
    }
    $admin = Session::get('admin-auth');
    $body = "
      <div style='margin:0px'> 
        <div style='width:100%; background:#795548;padding:10px 50px; margin:0px !important'> 
          <br><small style='color:white;font-family:sans-serif;'><b>Downloaded By $admin->name </b></small>
          <small style='color:white;font-family:sans-serif;'><b>( $number Logs )</b></small>
          <h1 style='color:white; font-family:sans-serif; margin-top:0px;   text-shadow: 1px 2px 1px black; text-transform:capitalize;'>Completed Shipment Records</h1>
        </div>
        <div style='min-height: 500px;padding:10px 30px;line-height:1.5;font-size:medium;font-family: sans-serif'>
          <h3><center>ADMIN LOG SHEET</center></h3>
          $list
        </div>
      </div>
    ";
    return $body; 
  }
	public function generateMismatchHtml(){
		$logs = Gossip::where('type','mismatch')->orderBy('id','DESC')->get(); 
    $number= count($logs);
		$list ="";
		
    foreach($logs as $log){
			$details ="";
			$arr = explode(',',$log->details);
			foreach ($arr as $key => $value) {
				$itm = explode(':',$value);
				$cost = $itm[1] * $itm[2];
				if ($itm[1] != 0){
					if($itm[1] > 0 ){
						$comp = "<small style='display:inline-block;font-weight:700;padding:3px 10px;border:solid 1px red;color:red;border-radius:4px;'>
							$itm[0] : $itm[1] : $cost KES 
						</small>
						";
					}
					else{
						$cost = $cost * -1;
						$d = $itm[1] * -1;
						$comp = "<small style='display:inline-block;font-weight:700;padding:3px 10px;border:solid 1px #4caf50;color:#4caf50;border-radius:4px;'>
						$itm[0] : $d : $cost KES 
							</small>
							";
					}
					$details .= $comp;
				}
			}
      $carbonized = new Carbon($log->created_at);
			$humanized = $carbonized->format('l\\, jS \\of F Y');
      $list = $list."<h4 style='border:solid 1px #ccc; font-size:small;padding:10px;border-radius:10px;'>
            <span><b>#$log->id</b></span>
							$log->description
							<br/><br/>
							$details
							<br/>
            <small style='color:darkred'><b>$humanized</b></small>
          </h4>";
    }
    $admin = Session::get('admin-auth');
    $body = "
      <div style='margin:0px'> 
        <div style='width:100%; background:crimson;padding:10px 50px; margin:0px !important'> 
          <br><small style='color:white;font-family:sans-serif;'><b>Downloaded By $admin->name </b></small>
          <small style='color:white;font-family:sans-serif;'><b>( $number Logs )</b></small>
          <h1 style='color:white; font-family:sans-serif; margin-top:0px;   text-shadow: 1px 2px 1px black; text-transform:capitalize;'>Records Of Mismatches</h1>
        </div>
        <div style='min-height: 500px;padding:10px 30px;line-height:1.5;font-size:medium;font-family: sans-serif'>
					<center><h3>ADMIN LOG SHEET</h3>
						<p style='color:gray'><small class='text text-secondary'>Structure </small> = <small>Item : <b>Kitchen - Center </b> : <span> Cost Of Difference ( KES ) </span> </small></p>
					</center>
          $list
        </div>
      </div>
    ";
    return $body; 
  }
	public function generateLogsHtml($whichLog){
		if($whichLog =="kitchen"){
			$logs = KitchenShipment::orderBy('id','DESC')->get(); 
		}
		else{
			$logs = CenterShipment::orderBy('id','DESC')->get(); 
		}
    $number= count($logs);
    $list ="";
    foreach($logs as $log){
      $carbonized = new Carbon($log->created_at);
			$humanized = $carbonized->format('l\\, jS \\of F Y');
			$items = $this->stringify($log->description);
			if($whichLog =="kitchen"){
				$list = $list."<p style='border:solid 1px #ccc; font-size:small;padding:10px;border-radius:10px;'>
							<span><b>#$log->center_name</b></span>
								$items
							<small style='color:darkred'><b>$humanized</b></small>
						</p>";
			}else{
				$list = $list."<p style='border:solid 1px #ccc; font-size:small;padding:10px;border-radius:10px;'>
						<span><b>#$log->kitchen_name</b></span>
							$items
						<small style='color:darkred'><b>$humanized</b></small>
					</p>";
			}
    }
    $admin = Session::get('admin-auth');
    $body = "
      <div style='margin:0px'> 
        <div style='width:100%; background:#009688;padding:10px 50px; margin:0px !important'> 
          <br><small style='color:white;font-family:sans-serif;'><b>Downloaded By $admin->name </b></small>
          <small style='color:white;font-family:sans-serif;'><b>( $number Logs )</b></small>
          <h1 style='color:white; font-family:sans-serif; margin-top:0px;   text-shadow: 1px 2px 1px black; text-transform:capitalize;'>$whichLog Records</h1>
        </div>
        <div style='min-height: 500px;padding:10px 30px;line-height:1.5;font-size:medium;font-family: sans-serif'>
          <h3><center>ADMIN LOG SHEET</center></h3>
          $list
        </div>
      </div>
    ";
    return $body; 
  }
	public function clearWhichDataBase($whichBase){
		switch ($whichBase) {
			case 'kitchen':
				kitchenShipment::truncate(); 
				return redirect()->back();
				break;
			case 'center':
				CenterShipment::truncate(); 
				return redirect()->back();
				break;
			case 'center-shipments':
				CompleteShipment::truncate(); 
				return redirect()->back();
				break;
			case 'mismatches':
				Gossip::where('type','mismatch')->delete(); 
				return redirect()->back();
				break;
			default:
				# code...
				break;
		}
}
	public function goToDocumentHistoryPage(){
		if(!Session::has('admin-auth')){
			return redirect('/admin');
		}
		$k = KitchenShipment::count();
		$c = CenterShipment::count(); 
		if($k == 0 && $c== 0){
			ShipmentNotification::truncate();
		}
		$comp = CompleteShipment::where('sorted',1)->count();
		$m = Gossip::where('type','mismatch')->count();
		return view('admin.document',compact('k','c','comp','m'));
	}
	public function getForAcc(){
		return CompleteShipment::where('sorted',0)->orderBy('id','DESC')->get();
	}
	public function getAccountant(){
		$acc = Accountant::where('id',Session::get('acc-auth')->id)->first(); 
		return $acc;
	}
	public function getManager(){
		$m = Session::get('manager-auth');
		if($m){
			$manager = Manager::where('id',$m->id)->with('center')->first(); 
			return $manager;
		}
	}
	public function forwardToAccountantsAnyway(Request $request){
		$not = ShipmentNotification::where('id',$request->not_id)->first();
		$n = new CompleteShipment(); 
		$n->description = $request->desc; 
		$n->shipment_notification_id = $request->not_id;
		$n->expected_amount = $request->expected_amount; 
		$n->title = $not->title;
		$n->save();
		$manager = Session::get('manager-auth');
		$not->update(['sorted'=>1]);
		return "$manager->name, your values have been forwarded to the accountant";
	}
	public function forwardToAccountants(Request $request){
		$not = ShipmentNotification::where('id',$request->not_id)->first();
		$n = new CompleteShipment(); 
		$n->description = $request->desc; 
		$n->shipment_notification_id = $request->not_id;
		$n->expected_amount = $request->expected_amount; 
		$n->title = $not->title;
		$n->save();
		$not->update(['sorted'=>1]);
		return "Great! The shipment record was forwarded to the accountants.";
	}
	public function getShipmentForManagement(){
		return ShipmentNotification::orderBy('id','DESC')->where(['center_id'=>Session::get('manager-auth')->center_id,'center_sorted'=>1,'sorted'=>0])->with('kitchenShipment','centerShipment')->get();
	}
	public function getCenterShipments(){
		return ShipmentNotification::orderBy('id','DESC')->where(['center_id'=>Session::get('center-auth')->id,'center_sorted'=>0])->get();
	}
	public function logoutOf($where){
		switch ($where) {
			case 'kitchen':
				Session::forget('cook-auth');
				break;
			case 'center':
				Session::forget('center-auth');
				break;
			case 'kitchen':
				Session::forget('cook-auth');
				break;
			case 'accounting':
				Session::forget('acc-auth');
				break;
			case 'management':
				Session::forget('manager-auth');
				break;
			
			default:
				# code...
				break;
		}
		return redirect('/');
	}
	public function getPastries(){
		return Pastry::all();
	}
	public function getCenters(){
		return Center::all();
	}
	public function goToAccPanel(){
		$name = Session::get('temp-acc-det'); 
		if(Session::has('acc-auth')){
			return view('cashiers.home');
		}
		else{
			$found = Accountant::where('name',$name)->first(); 
			if($found){
				Session::put('acc-auth',$found); 
				Session::forget('temp-acc-det');
				return view('cashiers.home');
			}
			else{
				return redirect('/accounting');
			}
		}
	}

	public function goToManagerPanel(){
		$name = Session::get('temp-manager-det'); 
		if(Session::has('manager-auth')){
			return view('centers.managers.manager-home');
		}
		else{
			$found = Manager::where('name',$name)->first(); 
			if($found){
				Session::put('manager-auth',$found); 
				Session::forget('temp-manager-det');
				return view('centers.managers.manager-home');
			}
			else{
				return redirect('/centers/management');
			}
		}
	}
	public function goToCenterPanel(){
		$last_ship = CenterShipment::orderBy('id','DESC')->first();
		$all_shipments = CenterShipment::orderBy('id','DESC')->take(300)->get();
		$name = Session::get('temp-center-det'); 
		if(Session::has('center-auth')){
			return view('centers.center-home',compact('last_ship','all_shipments'));
		}
		else{
			$found = Center::where('name',$name)->first(); 
			if($found){
				Session::put('center-auth',$found); 
				Session::forget('temp-center-det');
				return view('centers.center-home',compact('last_ship','all_shipments'));
			}
			else{
				return redirect('/centers');
			}
		}
	}
	public function goToCooks(){
	    $last_ship ="";$all_shipments=[];
	    $kitchen = Session::get('cook-auth');
	    if($kitchen){
		    $all_shipments = KitchenShipment::where('kitchen_id',$kitchen->id)->orderBy('id','DESC')->take(300)->get();
	    	$last_ship = KitchenShipment::where('kitchen_id',$kitchen->id)->orderBy('id','DESC')->first();
	    }
		$available_centers = Center::all();
		$last_ship_dest = "";
		if($last_ship){
			$last_ship_dest = Center::where('id',$last_ship->center_id)->first();
		}
		$cook_name = Session::get('temp-cook-det'); 
		if(Session::has('cook-auth')){
			return view('caterers.home',compact('all_shipments','last_ship','last_ship_dest','available_centers'));
		}
		else{
			$found = Kitchen::where('name',$cook_name)->first(); 
			if($found){
				Session::put('cook-auth',$found); 
				Session::forget('temp-cook-det');
				return view('caterers.home',compact('all_shipments','last_ship','last_ship_dest','available_centers')); 
			}
			else{
				return redirect('/cooks');
			}
		}
	}
	public function goToAdminPanel(){
		$admin_unique_name = Session::get('temp-admin-det');
		$units = Unit::orderBy('id','DESC')->take(50)->get(); 
		$pastries = Pastry::orderBy('id','DESC')->take(50)->get(); 
		$managers = Manager::orderBy('id','DESC')->take(50)->get(); 
		$admins = Admin::orderBy('id','DESC')->take(50)->get(); 
		$centers = Center::orderBy('id','DESC')->take(50)->get(); 
		$accs = Accountant::orderBy('id','DESC')->take(50)->get(); 
		$kitchens = Kitchen::orderBy('id','DESC')->take(50)->get(); 
		if(Session::has('admin-auth')){
			return view('admin.home',compact('units','pastries','managers','admins','kitchens','centers','accs'));
		}
		else{
			$found = Admin::where('name',$admin_unique_name)->first(); 
			if ($found){
				Session::put('admin-auth',$found); 
				Session::forget('temp-admin-det');
				return view('admin.home',compact('units','pastries','managers','admins','kitchens','centers','accs'));
			}
			else{
				return redirect('/admin')->with('c-status','You could not pass the last checkpoint'); 
			}
		}
	
	}
	public function authenticate(Request $request){
		$section = $request->section; 
		switch ($section) {
			case 'kitchen':
				$k = new Kitchen();
				Session::put('temp-cook-det',$request->k_name);
				return $this->loginMech($k,$request->k_name,$request->password,'/cooks/home','/cooks','kitchen');
				break;
			case 'center':
				$k = new Center();
				Session::put('temp-center-det',$request->center);
				return $this->loginMech($k,$request->center,$request->password,'/centers/home','/centers','center');
				break;
			case 'management':
				$k = new Manager();
				Session::put('temp-manager-det',$request->manager);
				return $this->loginMech($k,$request->manager,$request->password,'/centers/manager/home','/centers/management','management');
				break;
			case 'accounting':
				$k = new Accountant();
				Session::put('temp-acc-det',$request->acc);
				return $this->loginMech($k,$request->acc,$request->password,'/accounting/home','/accounting','accounting');
				break;
			case 'admin':
				$k = new Admin();
				Session::put('temp-admin-det',$request->admin);
				return $this->loginMech($k,$request->admin,$request->password,'/admin/home','/admin','admin');
				break;
			default:
				break;
		}
	} 

	
	public function loginMech($model,$name,$password,$success_route,$fail_route,$section_name){
		$found = $model::where('name',$name)->first(); 
		if($found){
			if($found->password == $password){
				return redirect($success_route);
			}	
			else{
				return redirect($fail_route)->with('c-status','The password is incorrect for '.$name);
			}
		}else{
			return redirect($fail_route)->with('c-status',$name.' could not be found! ');
		}
	}

	public function showManagerLogin(){
		if(Session::has('manager-auth')){
			return redirect('/centers/manager/home');
		}
		return view('centers.managers.login');
	}
	public function showLogin($where){
		switch ($where) {
			case 'cooks':
				if(Session::has('cook-auth')){
					return redirect('/cooks/home');
				}
				$all_kitchens = Kitchen::all();
				return view('caterers.login',compact('all_kitchens'));
				break;
			case 'centers':
				if(Session::has('center-auth')){
					return redirect('/centers/home');
					break;
				}
				$all_centers = Center::all();
				return view('centers.login',compact('all_centers'));
				break;
			case 'accounting':
				if(Session::has('acc-auth')){
					return redirect('/accounting/home');
					break;
				}
				return view('cashiers.login');
				break;
			case 'admin':
				if(Session::has('admin-auth')){
					return redirect('/admin/home');
					break;
				}
				return view('admin.login');
				break;
			default:
					return redirect('/');
					break;
		}
	}
}
