@extends('navs.admin')
@section('custom-title')
 Mismatch History
@endsection 

@section('custom-style')
 <style> 

  .desc-item:hover{
      background:palegoldenrod;
      cursor:pointer; 
      transition: .3s ease-in-out all; 
  }
 </style>
@endsection
@section('content')
  <div class="phone-m-zero phone-p-zero container">
  
      <h2 style="padding-top:20px;">List of all latest discrepancies</h2> 
        <p class="text text-danger">If a shipment faces one or more mismatches, each one of them gets a record</p>
      
      <div> 
        @forelse ($gossips as $item)
        <div class="thumbnail clearfix desc-item thumb-hover" style="padding:20px; border-color:#ccc; border-radius:7px;"> 
            <small class="text text-secondary ">3 seconds ago </small>
            <p>
             {{$item->description}}
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