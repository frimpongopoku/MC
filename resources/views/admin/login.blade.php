@extends('navs.app')

@section('content') 
  <div class="container"> 
    <div class="col-md-6 col-lg-6 col-sm-6 col-xs-12 offset-md-3" style="padding-top:5vh;">
      <center> 
        <div class="circle"> 
            
        </div>
      </center>
      <center>
        <div class="triangle"> 
        </div>
      </center>
      <div class="thumbnail clearfix" style="padding:40px;margin-top:0px !important">
        <form action ="/do-authentication" method="post">
          {{csrf_field()}}
        
          <label>Your unique admin identity</label>
          <input type="name" name="admin" placeholder="username" class="form-control" style="margin-bottom:9px"/> 
          <label>Password</label>
          <input type="password" name="password" class="form-control" style="font-size:40px;"/> 
          <input type="hidden" name="section" value="admin" /> 
          <button  class="btn btn-success  float-right little-margin">Go</button>
        </form>
        <button onclick="window.location ='/'"class="btn btn-secondary  float-right little-margin">Back</button>
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