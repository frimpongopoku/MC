@extends('navs.admin')
@section('custom-title')
  Admin| Statistics
@endsection 

@section('custom-style')

@endsection
@section('content')
  <div class="phone-m-zero phone-p-zero col-md-10 col-lg-10 col-sm-10 col-xs-12 offset-md-1" style="padding:30px;"> 
    <div>
      <center> 
        <p style="padding:15px;">Refer to the shipments with the horizontal keys in the list provided for <b>Figure A</b></p>
        <select class="form-control"> 
          @foreach ($datasets['labels'] as $key => $item)
            <option>{{$key}} --> {{$item}}</option>
          @endforeach
        </select>
      </center>
    </div>
    <canvas id="shipment-to-days-chart"></canvas>
    <div>
        <center> 
          <p>Refer to the shipments with the horizontal keys in the list provided for <b>Figure B</b></p>
          <select class="form-control"> 
            @foreach ($sales_per_shipment['labels'] as $key => $item)
              <option>{{$key}} --> {{$item}}</option>
            @endforeach
          </select>
        </center>
      </div>
    <canvas id="s_p_ship"></canvas>
    <div>
        <center> 
          <p>Refer to the shipments with the horizontal keys in the list provided for <b>Figure C</b></p>
          <select class="form-control"> 
            @foreach ($sales_per_shipment['labels'] as $key => $item)
              <option>{{$key}} --> {{$item}}</option>
            @endforeach
          </select>
        </center>
      </div>
    <canvas id="diff_in_ship"></canvas>
  </div>
    
  </div>
@endsection 


@section('custom-js')
  <script> 
    var lab= [];
    var s_lab = [];
    var diff_lab = [];
    var sales_chart_data = @json($datasets);
    var s_p_s = @json($sales_per_shipment);
    var diff = @json($diff);
    sales_chart_data.labels.forEach((el,index) => {
      lab.push(index)
    });
    s_p_s.labels.forEach((el,index) => {
      s_lab.push(index);
    });
    diff.labels.forEach((el,index) => {
      diff_lab.push(index)
    });
    console.log(sales_chart_data)
    </script>
    <script src="{{asset('js/Chart.js')}}"></script>
    <script src="{{asset('js/utils.js')}}"></script>
    {{-- <script src="{{asset('js/personal.js')}}"></script> --}}
    <script src="{{asset('js/statistics.js')}}"></script>


@endsection