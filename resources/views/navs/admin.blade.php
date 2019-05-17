<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>@yield('custom-title')</title>
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Google+Sans" rel="stylesheet">
        <link href="{{asset('css/application.css')}}" rel="stylesheet">
        <link href="{{asset('css/app.css')}}" rel="stylesheet">
    </head>
    @yield('custom-style')
    <body>
        <div class="admin-nav"> 
            <div class="admin-prof-box">
              <div class="circle raise" style="border-color:white;"> 
              <center><h1 style="font-size:75px; margin-top:1vh;text-transform:uppercase;">{{substr(Session::get('admin-auth')->name,0,2)}}</h2></center>
              </div>
            </div>
            <ul style="list-style:none;padding:0px;margin:0px;"> 
              <li class="s-nav-item" style="background:white;color:black !important;font-weight:900; text-transform:uppercase;">{{Session::get('admin-auth')->name}}</h1> 
              <li class="s-nav-item" onclick="window.location ='/admin/home'">Home</h1> 
              <li class="s-nav-item">Sales And Structure Map</h1> 
              <li class="s-nav-item">History</h1> 
              <li class="s-nav-item" onclick="window.location = '/admin/logout'">Logout</h1> 
            </u>
          </div>
        <div class="col-md-8 col-lg-8 col-sm-8 offset-md-2 col-xs-12"> 
            @yield('content')
        </div>
    
      
      

    <script src="asset('js/app.js')}}"></script>
    @yield('custom-js')
    </body> 
  </html>
