@extends('navs.app')

@section('content') 
  <div class="container"> 
    <div class="col-md-6 col-lg-6 col-sm-6 col-xs-12 offset-md-3" style="padding-top:5vh;">
    
      <div class="thumbnail  raise-hover clearfix" style="cursor:pointer;background:burlywood;border:solid 4px #d8cccc;padding:40px;margin-top:20vh !important">
        <form action ="/do-authentication" method="post">
        <h5>MANAGEMENT</h5>
          {{csrf_field()}}
          <label>Uppercase and Lowercases do matter
          <input placeholder="username" type="name" name="manager" class="form-control little-margin" /> 
          <label>Password for your center</label>
          <input type="hidden" name="section" value="management" />
          <input type="password" name="password" class="form-control" style="font-size:20px;"/> 
          <button  class="btn btn-success  float-right little-margin">Go</button>
        </form>
       
        <a href="/centers">Go to center</a> 
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