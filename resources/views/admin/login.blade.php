@extends('navs.app')

@section('content') 
  <div class="container"> 
    <div class="col-md-6 col-lg-6 col-sm-6 col-xs-12 offset-md-3" style="padding-top:5vh;">
    
      <div class="thumbnail raise-hover clearfix" style="border:solid 4px lightcyan; background:darkcyan;color:white;cursor:pointer;padding:40px;margin-top:20vh !important">
        <form action ="/do-authentication" method="post">
          {{csrf_field()}}
          <h3>ADMIN </h3>
          <label>Your unique admin identity</label>
          <input type="name" name="admin" placeholder="username" class="form-control" style="margin-bottom:9px"/> 
          <label>Password</label>
          <input type="password" name="password" class="form-control" style="font-size:20px;"/> 
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