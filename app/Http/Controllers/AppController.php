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
class AppController extends Controller
{

	
	public function getShipmentForManagement(){
		return ShipmentNotification::where(['center_id'=>Session::get('manager-auth')->center_id,'center_sorted'=>1,'sorted'=>0])->with('kitchenShipment','centerShipment')->get();
	}
	public function getCenterShipments(){
		return ShipmentNotification::where(['center_id'=>Session::get('center-auth')->id,'center_sorted'=>0])->get();
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
			$found = Manager::where('name',$name)->first(); 
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
		$all_shipments = KitchenShipment::take(300)->get();
		$available_centers = Center::all();
		$last_ship = KitchenShipment::orderBy('id','DESC')->first();
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
