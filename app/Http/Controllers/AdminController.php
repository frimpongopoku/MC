<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use App\Pastry; 
use App\Unit; 
use App\Kitchen; 
use App\Center; 
use App\Accountant; 
use App\Manager;
use App\Admin; 

class AdminController extends Controller
{

		public function removeItem($id, $where){
			switch ($where) {
				case 'unit':
					Unit::where('id',$id)->first()->delete();
					return redirect()->back();
					break;
				case 'pastry':
					Pastry::where('id',$id)->first()->delete();
					return redirect()->back();
					break;
				case 'kitchen':
					Kitchen::where('id',$id)->first()->delete();
					return redirect()->back();
					break;
				case 'center':
					Center::where('id',$id)->first()->delete();
					return redirect()->back();
					break;
				case 'acc':
					Accountant::where('id',$id)->first()->delete();
					return redirect()->back();
					break;
				case 'manager':
					Manager::where('id',$id)->first()->delete();
					return redirect()->back();
					break;
				
				case 'admin':
					Admin::where('id',$id)->first()->delete();
					return redirect()->back();
					break;
				
				default:
					return redirect()->back();
					break;
			}
		}
		public function addManager(Request $request){
			if(trim($request->name) !="" && trim($request->password) !=""){
				$k = new Manager(); 
				$k->name = $request->name; 
				$k->email = $request->email;
				$k->center_id = Center::where('name',$request->center_name)->first()->id;
				$k->password = $request->password; 
				$k->save(); 
				return redirect()->back();
			}
			else{
				return redirect()->back()->with('status','Please make sure all inputs are filled');
			}
		}
		public function addAdmin(Request $request){
			if(trim($request->name) !="" && trim($request->password) !=""  && $request->password == $request->confirm_password){
				$k = new Admin(); 
				$k->name = $request->name; 
				$k->email = $request->email;
				$k->password = $request->password; 
				$k->save(); 
				return redirect()->back();
			}
			else{
				return redirect()->back()->with('status','Please make sure all inputs are filled, and all passwords match!');
			}
		}
		public function addAcc(Request $request){
			if(trim($request->name) !="" && trim($request->password) !=""  && $request->password == $request->confirm_password){
				$k = new Accountant(); 
				$k->name = $request->name; 
				$k->email = $request->email;
				$k->password = $request->password; 
				$k->save(); 
				return redirect()->back();
			}
			else{
				return redirect()->back()->with('status','Please make sure all inputs are filled, and all passwords match!');
			}
		}
		public function addCenter(Request $request){
			if(trim($request->name) !="" && trim($request->password) !=""  && $request->password == $request->confirm_password){
				$k = new Center(); 
				$k->name = $request->name; 
				$k->password = $request->password; 
				$k->save(); 
				return redirect()->back();
			}
			else{
				return redirect()->back()->with('status','Please make sure all inputs are filled, and all passwords match!');
			}
		}
		public function addKitchen(Request $request){
			if(trim($request->name) !="" && trim($request->password) !="" && $request->password == $request->confirm_password){
				$k = new Kitchen(); 
				$k->name = $request->name; 
				$k->password = $request->password; 
				$k->save(); 
				return redirect()->back();
			}
			else{
				return redirect()->back()->with('status','Please make sure all inputs are filled, and passwords match!');
			}
		}
		public function addUnit(Request $request){
			if(trim($request->name) !=""){
				$unit = new Unit(); 
				$unit->name = $request->name; 
				$unit->save(); 
			}
			return redirect()->back();
		}
		public function addPastry(Request $request){
			if(trim($request->name) !="" && trim($request->price) !=""){
				$pas = new Pastry(); 
				$pas->name = $request->name; 
				$pas->price = $request->price;
				$pas->save(); 
			}
			return redirect()->back();
		}
    public function logout(){
			Session::forget('admin-auth'); 
			return redirect('/admin');
    }
}
