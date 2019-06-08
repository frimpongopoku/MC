@extends('navs.admin')
@section('custom-title')
  Document History
@endsection 

@section('custom-style')

@endsection
@section('content')
  <div class="phone-m-zero phone-p-zero container">
    <h4 style="padding:20px">You can generate PDF files of all history in the categories provided below</h4>
    <div class="raise-hover thumbnail" style="padding:30px"> 
      <h3>Kitchen</h3>
      <h5>Download all history of inputs provided by all kitchens</h5>
      @if($k >0)
        <button class="btn btn-danger raise"  onclick ="window.location ='/clear-data/kitchen'">Clear All History </button>
        <button class="btn btn-success raise" onclick ="window.location ='/download/records/kitchen'">Download</button>
      @else
        <small class="text text-default">No records to download, or clear.</small>
      @endif
    </div>
    <div class="raise-hover thumbnail" style="padding:30px"> 
      <h3>Center</h3>
      <h5>Download all history of values counted in centers</h5>
      @if($c >0)
        <button class="btn btn-danger raise"  onclick ="window.location ='/clear-data/center'">Clear All History </button>
        <button class="btn btn-success raise" onclick ="window.location ='/download/records/center'">Download</button>
      @else
        <small class="text text-default">No records to download, or clear.</small>
      @endif
    </div>
    <div class="raise-hover thumbnail" style="padding:30px"> 
      <h3>Complete Shipments</h3>
      <h5>Download all history of complete shimpents</h5>
      @if($comp >0)
        <button class="btn btn-danger raise" onclick ="window.location ='/clear-data/center-shipments'">Clear All History </button>
        <button class="btn btn-success raise"  onclick ="window.location ='/download/complete-shipments'">Download</button>
      @else
        <small class="text text-default">No records to download, or clear.</small>
      @endif
    </div>
    <div class="raise-hover thumbnail" style="padding:30px"> 
      <h3>Mismatch</h3>
      <h5>Download all history of mismatches</h5>
      @if($m >0)
        <button class="btn btn-danger raise" onclick ="window.location ='/clear-data/mismatches'">Clear All History </button>
        <button class="btn btn-success raise"  onclick ="window.location ='/download/mismatches'">Download</button>
      @else
        <small class="text text-default">No records to download, or clear.</small>
      @endif
    </div>
  </div>
@endsection 


@section('custom-js')

@endsection