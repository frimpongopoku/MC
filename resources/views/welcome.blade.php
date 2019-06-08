<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
				<title>Phil Edwards Catering</title>
				<link rel="shortcut icon"  type="img/png" href="{{asset('imgs/bb.png')}}">
        <!-- Fonts -->
			<link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
			<link href="https://fonts.googleapis.com/css?family=Google+Sans" rel="stylesheet">
			<link href="{{asset('css/application.css')}}" rel="stylesheet">
			<link href="{{asset('css/app.css')}}" rel="stylesheet">
             
    </head>
    <body>
			<style> 
				body{
					/* background:#343a4094; */
				}
			</style>
			<div class="welcome-page" >
					<center><h1 style="color:#afafaf;">Welcome to Phil Edwards Catering</h1></center>
					<div class="col-md-10 col-lg-10 col-sm-6  offset-md-1" style="padding:30px;">
						<div class="row main-fix">
							<div onclick="window.location='/cooks'" class="cooks-effect thumbnail raise menu-cards"> 
								<h1>Cooks</h1>
							</div>
							<div onclick="window.location='/centers'"class="centers-effect thumbnail raise menu-cards"> 
								<h1>Centers</h1>
							</div>
							<div style="padding:50px 10px !important" onclick="window.location='/accounting'"class="acc-effect thumbnail raise menu-cards"> 
								<h1>Accounting</h1>
							</div>
							<div onclick="window.location='/admin'"class=" admin-effect thumbnail raise menu-cards"> 
								<h1>Admin</h1>
							</div>
						</div>
					</div>
			</div>
    </body>
</html>
