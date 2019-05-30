<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HTMLEmailGenerator extends Controller
{
    function __construct($kitchen,$center,$manager,$pairings){
        $this->kitchen = $kitchen->name; 
        $this->center = $center->name; 
				$this->manager = $manager;
				$this->pairings = $pairings;
    }

   function generateHtml(){
			$list = ""; 
			foreach ($this->pairings as $value) {
				$c_amount =  $value['c']['amount'];
				$temp = $this->listItem($value['k']['name'],$value['k']['amount'],$value['c']['name'],$c_amount,$value['numbers_match']);
				$list .=$temp;
			}
			$html = $this->getBody($list);
			return $html;
		}
		
		function getBody($list){
			$body = "
				<h2>Hi $this->manager </h2>
				<div style='padding:30px'>
						<h4>Here is a comparison of items counted by the kitchen staff <br> and the staff at the centers. </h4>
						<h5>The items were shipped from $this->kitchen, and counted at $this->center</h5>
						$list
						
						<br>
						<hr>
						<br>
						<p>If all the items match, this button will push the list to the account straight forward
						and there would be nothing else to do. 
							If there is a mismatch, the button will take you to your dashboard, where you will 
							be given the tools to correct this.
						</p>
						<a href='/get/some' style='text-decoration: none; color:black;border:solid 2px black; padding:15px; margin:10px;border-radius:5px;'>Confirm</a>
						</div>
				";
			return $body;
		}
		function listItem($k,$k_amount,$c,$c_amount,$status){
			if($status){
				return " <div>
					<div style='margin:5px;font-weight:700;color:darkgreen;border-radius:7px;padding:5px 15px;border:solid 2px #ccc; display:inline-block'>
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
				<div style='margin:15px;font-weight:700;color:darkred;border-radius:7px;padding:5px 15px;border:solid 2px #ccc; display:inline-block'>
				<p>$k <span style='color:darkred'>$k_amount</span></p>
				</div>
				<hr style='width:20%; display:inline-block'/>
				<div style='font-weight:700;color:darkred;border-radius:7px;padding:5px 15px;border:solid 2px #ccc; display:inline-block'>
				<p>$c<span style=''>$c_amount </span></p>
				</div>
			</div>";
			}
		}
}
