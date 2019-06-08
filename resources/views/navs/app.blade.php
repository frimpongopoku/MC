<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>@yield('custom-title')</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600,700,900" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Google+Sans" rel="stylesheet">
        <link href="{{asset('css/application.css')}}" rel="stylesheet">
        <link rel="shortcut icon"  type="img/png" href="{{asset('imgs/bb.png')}}">
        <link href="{{asset('css/app.css')}}" rel="stylesheet">
        <link href="{{asset('font/css/font-awesome.min.css')}}" rel="stylesheet">
    </head>
    @yield('custom-style')
    <body>

    @yield('content')

    <script src="{{asset('js/app.js')}}"></script>
    <script src="{{asset('js/jquery-3.3.1.min.js')}}"></script>
    @yield('custom-js')
    </body> 
  </html>
