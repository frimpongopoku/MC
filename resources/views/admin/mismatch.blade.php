@extends('navs.admin')
@section('custom-title')
 Mismatch History
@endsection 

@section('custom-style')
 <style> 
 small{
   margin:3px;
   font-size:14px; 
   font-weight: 700;
 }
  .det-p-danger{
    border: solid 1px red;
    display: inline-block;
    padding: 2px 12px;
    border-radius: 4px;
  }
  .det-p-success{
    border: solid 1px #4caf50;
    display: inline-block;
    padding: 2px 12px;
    border-radius: 4px;
  }
  .desc-item:hover{
      background:palegoldenrod;
      cursor:pointer; 
      transition: .3s ease-in-out all; 
  }
 </style>
@endsection
@section('content')
  <div class="phone-m-zero phone-p-zero container">
  
      <h2 style="padding-top:20px">List of all latest discrepancies</h2> 
       
       
       <div class="clearfix">
          <button  onclick="window.location = '/download/mismatches'"class="btn btn-block btn-secondary" style="cursor:pointer; display:inline-block;"><i class="fa fa-download"></i> Download </button>
          <p class="text text-danger">If a shipment faces one or more mismatches, each one of them gets a record</p>
          <p><small class="text text-secondary">Structure </small> = <small>Item : <b>Kitchen - Center </b> <i class="fa fa-caret-right"></i> <span> Cost Of Difference ( KES ) </span> </small></p>
       
       </div>
        <div> 
        @forelse ($gossips as $item)
        <div class="thumbnail clearfix desc-item thumb-hover" style="padding:20px; border-color:#ccc; border-radius:7px;"> 
            <small class="text text-secondary ">{{$item->created_at->diffForHumans()}}</small>
            <p>
             {{$item->description}}
            </p>
            <p>
              @php
                 $arr = explode(",",$item->details);
                foreach ($arr as $value) {
                  $a = explode(':',$value);
                  if($a[1] != 0){
                    $cost = $a[1] * $a[2];
                    $fa = "<i class='fa fa-caret-right' style='margin:13'></i>";
                    $small = "<small class='det-p-danger'>"; 
                    $esmall = "</small></br>"; 
                    $small_g  = "<small class='det-p-success'>";
                    $span = "<span class='text text-danger'>";
                    $span_g  = "<span class='text text-success'>";
                    $espan = "</span><br/>";
                    $espanB = "</span>";
                    if($a[1] > 0){
                      echo $small.$span.$a[0].' : '.$a[1].$espanB.$span.' '.$fa.$cost.' KES '.$espan.$esmall;
                    }
                    else{
                      $cost = $cost * -1;
                      $diff = $a[1] * -1;
                      echo $small_g.$span_g.$a[0].' : '.$diff.$espanB.$span_g.' '.$fa.' '.$cost.' KES '.$espan.$esmall;
                    }
                  }
                }
              @endphp
            </p>
        </div>
        @empty
        <div class="thumbnail clearfix desc-item thumb-hover" style="padding:20px; border-color:#ccc; border-radius:7px;"> 
            <p>
             Great news! No Descrepancies yet!
            </p>
        </div>
        @endforelse
        
       
      </div>
  </div>

@endsection 


@section('custom-js')

@endsection