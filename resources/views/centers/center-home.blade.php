@extends('navs.app')
@section('custom-title')
	Center
@endsection 

@section('custom-style')

@endsection
@section('content')
	<div class="phone-m-zero phone-p-zero col-md-10 col-lg-10 col-sm-10 col-xs-12 offset-md-1" style="padding:30px;"> 
			<div class="mobile-vanish-key pc-appearance-key">
				<div onclick ="swap('history-area','creation-area','history-button','create-button')"id="create-button" class="circle s-round-btn s-btn-active" style="height:100px; width:100px;position:absolute"> 
					<center><h1 class=""><i class="fa fa-pencil" style="margin-top:9px;"></i></h1> </center> 
				</div>
				<div onclick ="swap('creation-area','history-area','create-button','history-button')" id="history-button" class="circle s-round-btn" style="height:100px; width:100px;position:absolute; top:22vh;"> 
						<center><h1><i class="fa fa-history" style="margin-top:9px;"></i></h1> </center>
				</div>
				<div onclick ="window.location='/center/logout'" id="history-button" class="circle s-round-btn" style="height:100px; width:100px;position:absolute; top:39vh;"> 
						<center><h1><i class="fa fa-sign-out" style="margin-top:9px;"></i></h1> </center>
				</div>
			</div>
		<div style="padding:20px;" class="pc-vanish-key">
			<center>
				<small id="create-button" onclick ="swap('history-area','creation-area','history-button','create-button')" class="phone-menu-circles"><i class="fa fa-pencil" style="font-size:50px"></i></small> 
				<small id="history-button" onclick ="swap('creation-area','history-area','create-button','history-button')"class="phone-menu-circles"><i class="fa fa-history" style="font-size:50px;margin-top:4px;"></i></small> 
				<small onclick ="window.location='/center/logout'" class="phone-menu-circles"><i class="fa fa-sign-out" style="font-size:50px"></i></small> 
			</center>
		</div>
		<div class="components phone-m-zero"> 
			@if($last_ship)
				<p class="alert alert-info">Last items were counted from {{$last_ship->kitchen_name}} {{$last_ship->created_at->diffForHumans()}}
			@endif
			<div id="creation-area"> 
				<div id="center-react-div"></div>
			</div>
			<div id="history-area" style="display:none;"> 
					@forelse($all_shipments as $s)
					<p class="alert alert-warning clearfix" style="font-weight:700">
						@php 
							$sent = ""; 
							foreach(explode("<==>",$s->description) as $k){
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
							echo $sent." were counted from ".$s->kitchen_name;
	
						@endphp
						<small class="float-right">{{$s->created_at->diffForHumans()}}</small>
					</p>
				@empty
					<p> No records! </p>
				@endforelse
			</div>
		</div>
	</div>
@endsection 


@section('custom-js')
<script> 
    var swap = function(from,to,fromBtn,toBtn){
      $('#'+fromBtn).removeClass('s-btn-active');
      $('#'+toBtn).addClass('s-btn-active');
      $('#'+from).fadeOut(200,function(){
        $('#'+to).fadeIn(200);
      })
    }
   
  </script>

@endsection