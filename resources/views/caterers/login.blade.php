@extends('navs.app')

@section('custom-title')
  Cooks
@endsection
@section('custom-style')
 
@endsection
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
      <div class="thumbnail t-hover clearfix" style="border:solid 4px antiquewhite;background:bisque;padding:40px;margin-top:0px !important">
        <form action ="/do-authentication" method="post">
          {{csrf_field()}}
          <label>Choose which kitchen you belong to</label>
          <select class="form-control" name="k_name" style="margin-bottom:5px"> 
            @foreach($all_kitchens as $k)
              <option>{{$k->name}}</option> 
            @endforeach
          </select>
          <label>Password for your group</label>
          <input type="password" name="password" class="form-control" style="font-size:20px;"/> 
          <input type="hidden" name="section" value="kitchen" class="form-control" /> 
          
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