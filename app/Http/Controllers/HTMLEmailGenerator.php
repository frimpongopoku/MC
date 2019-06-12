<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HTMLEmailGenerator extends Controller
{
    function __construct($not_id,$desc,$expected_amount,$kitchen,$center,$manager,$pairings){
				$this->notification_id = $not_id;	
			$this->description =$desc ;
				$this->expected_amount = $expected_amount;	
				$this->kitchen = $kitchen->name; 
        $this->center = $center->name; 
				$this->manager = $manager;
				$this->pairings = $pairings;
    }

   function generateHtml($status){
			$list = ""; 
			foreach ($this->pairings as $value) {
				$c_amount =  $value['c']['amount'];
				$temp = $this->listItem($value['k']['name'],$value['k']['amount'],$value['c']['name'],$c_amount,$value['numbers_match']);
				$list .=$temp;
			}
			if($status){
				$html = $this->getBody($list);
			}
			else{
				$html = $this->getFailBody($list);
			}
			return $html;
		}
		
		function getFailBody($list){
			$body = "
				<h2>Hi $this->manager </h2>
				<div style='padding:10px'>
						<h4>Here is a comparison of items counted by the kitchen staff <br> and the staff at the centers. </h4>
						<h5>The items were shipped from <b>$this->kitchen</b>, and counted at <b>$this->center</b></h5>
						$list
						
						<br>
						<hr>
						<br>
						<small>Please deal with this in person, and visit your dashboard to input correct and matching values
						</small><br><br>
					
						<a href='http://philedwardscatering.commcycle.co/centers/management' style='background:darkred;color:white;text-decoration: none;border:solid 2px white; padding:10px 30px; margin:10px;border-radius:5px;'>Fix This</a>
						</div>
				";
			return $body;
		}
		function getBody($list){
			$body = "
				<h2>Hi $this->manager </h2>
				<div style='padding:10px'>
						<h4>Here is a comparison of items counted by the kitchen staff <br> and the staff at the centers. </h4>
						<h5>The items were shipped from <b>$this->kitchen</b>, and counted at <b>$this->center</b></h5>
						$list
						
						<br>
						<hr>
						<br>
						<small>This button will push the above values to the accountants,and you will be done with this shipment!
						</small><br><br>
						<a href='http://philedwardscatering.commcycle.co/manager/forward-to-acc/?not_id=$this->notification_id&desc=$this->description&expected_amount=$this->expected_amount' style='background:darkgreen;color:white;text-decoration: none;border:solid 2px white; padding:10px 30px; margin:10px;border-radius:5px;'>Confirm</a>
						</div>
				";
			return $body;
		}
		function listItem($k,$k_amount,$c,$c_amount,$status){
			if($status){
				return " <div>
					<div style='margin:3px;font-weight:700;color:darkgreen;border-radius:7px;padding:5px 15px;border:solid 2px #ccc; display:inline-block'>
					<p>$k <span style='color:darkgreen'> $k_amount</span></p>
					</div>
					<hr style='width:20%; display:inline-block'/>
					<div style='font-weight:700;color:darkgreen;border-radius:7px;padding:5px 15px;border:solid 2px #ccc; display:inline-block'>
					<p>$c <span style=''> $c_amount  </span></p>
					</div>
				</div>";
			}
			else{
				return " <div>
				<div style='margin:3px;font-weight:700;color:darkred;border-radius:7px;padding:5px 15px;border:solid 2px #ccc; display:inline-block'>
				<p>$k <span style='color:darkred'> $k_amount </span></p>
				</div>
				<hr style='width:20%; display:inline-block'/>
				<div style='font-weight:700;color:darkred;border-radius:7px;padding:5px 15px;border:solid 2px #ccc; display:inline-block'>
				<p>$c <span style=''> $c_amount </span></p>
				</div>
			</div>";
			}
		}
}
