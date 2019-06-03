@extends('navs.app')

@section('content') 
  <div class="container"> 
    <div class="col-md-6 col-lg-6 col-sm-6 col-xs-12 offset-md-3" style="padding-top:5vh;">
      <center> 
          <img src="/imgs/cookery.jpg" class="k_img" style="margin-bottom:15px"/>
      </center>
      <center>
        {{-- <div class="triangle"> 
        </div> --}}
      </center>
      <div class="thumbnail t-hover clearfix" style="background:gainsboro; border:solid 4px #DEE;padding:40px;margin-top:0px !important">
        <form action ="/do-authentication" method="post">
          {{csrf_field()}}
          <label>Choose which center you would like to get into</label>
          <select class="form-control" name="center"style="margin-bottom:5px"> 
           @foreach($all_centers as $c)
            <option>{{$c->name}}</option>
           @endforeach
          </select>
          <label>Password for your center</label>
          <input type="password" name="password" class="form-control" style="font-size:20px;"/> 
          <input type="hidden" name="section" value="center"/> 
          <button  class="btn btn-success  float-right little-margin">Go</button>
        </form>
        <button onclick="window.location ='/'"class="btn btn-secondary  float-right little-margin">Back</button>
        <a href="/centers/management">I am a manager</a> 
     </div> 
     <div >
        @if(Session::has('c-status'))
          <p style="margin:5px"class=" alert alert-danger alert-dismissable">{{Session::get('c-status')}}</p>
          @endif
      </div>
    </div> 
  </div>

@endsection

@section('custom-js')

@endsection