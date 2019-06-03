@extends('navs.app')

@section('content') 
  <div class="container"> 
    <div class="col-md-6 col-lg-6 col-sm-6 col-xs-12 offset-md-3" style="padding-top:5vh;">
     
      <div class="thumbnail raise-hover clearfix" style="background:#0e4775;color:white;border:solid 2px darkgroldenrod;padding:40px;margin-top:20vh !important">
        <h3>ACCOUNTING </h3>
        <form action ="/do-authentication" method="post">
          {{csrf_field()}}
          <label>Your unique name</label>
          <input  type="name" name="acc" placeholder="name" class="form-control"/>
          <label>Password </label>
          <input type="hidden" name="section" value="accounting"/> 
          <input type="password" name="password" class="form-control" style="font-size:20px;"/> 
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