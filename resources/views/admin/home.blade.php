@extends('navs.admin')

@section('custom-title')
    Admin Panel
@endsection
@section('custom-style')
@endsection
@section('content')
	<div class=""> 
		<div class="row" style="padding-top:20px;">
				{{-- =================PASTRY AREA ===================== --}}
				<div class="col-md-6 col-mg-6" style="margin:0px;">
					<div class="thumbnail  t-hover clearfix"> 
						<h5>ADD NEW PASTRY</h5>
						<h6 style="margin-left:15x;">Available Pastries</h6>
						<div style="padding:20px;border-radius:5px; border: solid 1px #ccc;overflow-y:scroll;height:100px;"> 
								@foreach($pastries as $value)
								<div class="clearfix">
										<button onclick="window.location='/admin/remove-item-{{$value->id}}/pastry'"class="btn btn-danger btn-sm float-right very-little-margin">
											<i class="fa fa-close"></i>
										</button>
										<span>{{$value->name}}  ( {{$value->price}} KES )</span>
								</div>
							@endforeach
						</div>
						<form action="/add-pastry" method="post">
							{{csrf_field()}}
							<input type="text" placeholder="pastry name" name="name" class="form-control little-margin" /> 
							
							<input type="number" placeholder="price" name="price" class="form-control little-margin" /> 
							<label class="float-left">Prices are in Kenyan Shillings</label>
							<button class="btn btn-success float-right">create</button>
						</form>
					</div> 
				</div>
				{{-- /END OF PASTRY AREA --}}
			{{-- =================MEASURMENT AREA ===================== --}}
			{{-- <div class="col-md-6 col-mg-6" style="margin:0px;">
				<div class="thumbnail  t-hover clearfix"> 
					<h5>ADD UNIT OF MEASUREMENT</h5>
					<h6 style="margin-left:15x;">Available Units</h6>
					<div style="padding:20px;border-radius:5px;border: solid 1px #ccc;overflow-y:scroll;height:100px;"> 
								@foreach($units as $unit)
									<div class="clearfix">
											<button onclick="window.location='/admin/remove-item-{{$unit->id}}/unit'"class="btn btn-danger btn-sm float-right very-little-margin">
												<i class="fa fa-close"></i>
											</button>
											<span>{{$unit->name}}</span>
									</div>
								@endforeach
					</div>
					<form action ="/add-unit" method="post">
						{{csrf_field()}}
						<input type="text" placeholder="Eg. plates, pieces, fingers" name="name" class="form-control little-margin" /> 
						<button class="btn btn-success float-right">create</button>
					</form>
				</div> 
			</div> --}}
			{{-- /END OF MEASUREMENT AREA --}}
		
			{{-- =================KITCHEN AREA ===================== --}}
			<div class="col-md-6 col-mg-6" style="margin:0px;">
				<div class="thumbnail  t-hover clearfix"> 
					<h5>ADD KITCHEN</h5>
					<h6 style="margin-left:15x;">Available Kitchens</h6>
					<div style="padding:20px;border-radius:5px; border: solid 1px #ccc;overflow-y:scroll;height:100px;"> 
							@foreach($kitchens as $value)
							<div class="clearfix">
									<button onclick="window.location='/admin/remove-item-{{$value->id}}/kitchen'"class="btn btn-danger btn-sm float-right very-little-margin">
										<i class="fa fa-close"></i>
									</button>
									<span>{{$value->name}}</span>
							</div>
						@endforeach
					</div>
					<form action="/add-kitchen" method="post">
						{{csrf_field()}}
						<input type="text" placeholder="kitchen name" name="name" class="form-control little-margin" /> 
						<input type="password" placeholder="password" name="password" class="form-control little-margin" />
						<input type="password" placeholder="confirm password" name="confirm_password" class="form-control little-margin" />
						<button class="btn btn-success float-right">create</button>
					</form>
				</div> 
			</div>
			{{-- /END OF KITCHEN AREA --}}
			{{-- =================CENTER AREA ===================== --}}
			<div class="col-md-6 col-mg-6" style="margin:0px;">
				<div class="thumbnail  t-hover clearfix"> 
					<h5>ADD CENTER</h5>
					<h6 style="margin-left:15x;">Available Centers</h6>
					<div style="padding:20px;border-radius:5px; border: solid 1px #ccc;overflow-y:scroll;height:100px;"> 
							@foreach($centers as $value)
							<div class="clearfix">
									<button onclick="window.location='/admin/remove-item-{{$value->id}}/center'"class="btn btn-danger btn-sm float-right very-little-margin">
										<i class="fa fa-close"></i>
									</button>
									<span>{{$value->name}}</span>
							</div>
						@endforeach
					</div>
					<form action="/add-center" method="post">
						{{csrf_field()}}
						<input type="text" placeholder="center name" name="name" class="form-control little-margin" /> 
						<input type="password" placeholder="password" name="password" class="form-control little-margin" />
						<input type="password" placeholder="confirm password" name="confirm_password" class="form-control little-margin" />
						<button class="btn btn-success float-right">create</button>
					</form>
				</div> 
			</div>
			{{-- /END OF KITCHEN AREA --}}
			{{-- =================ACCOOUNTING AREA ===================== --}}
			<div class="col-md-6 col-mg-6" style="margin:0px;">
				<div class="thumbnail  t-hover clearfix"> 
					<h5>ADD ACCOUNTANT</h5>
					<h6 style="margin-left:15x;">Available Accountants</h6>
					<div style="padding:20px;border-radius:5px; border: solid 1px #ccc;overflow-y:scroll;height:100px;"> 
							@foreach($accs as $value)
							<div class="clearfix">
									<button onclick="window.location='/admin/remove-item-{{$value->id}}/acc'"class="btn btn-danger btn-sm float-right very-little-margin">
										<i class="fa fa-close"></i>
									</button>
									<span>{{$value->name}} - <small>{{$value->email}}</small></span>
							</div>
						@endforeach
					</div>
					<form action="/add-acc" method="post">
						{{csrf_field()}}
						<input type="text" placeholder="acc name" name="name" class="form-control little-margin" /> 
						<input type="email" placeholder="acc email" name="email" class="form-control little-margin" required/> 
						<input type="password" placeholder="password" name="password" class="form-control little-margin" />
						<input type="password" placeholder="confirm password" name="confirm_password" class="form-control little-margin" />
						<button class="btn btn-success float-right">create</button>
					</form>
				</div> 
			</div>
			{{-- /END OF ACCOUNTING AREA --}}
			{{-- =================MANAGEMENT AREA ===================== --}}
			<div class="col-md-6 col-mg-6" style="margin:0px;">
				<div class="thumbnail t-hover  clearfix"> 
					<h5>ADD MANAGER</h5>
					<h6 style="margin-left:15x;">Available managers</h6>
					<div style="padding:20px;border-radius:5px; border: solid 1px #ccc;overflow-y:scroll;height:100px;"> 
							@foreach($managers as $value)
							<div class="clearfix">
									<button onclick="window.location='/admin/remove-item-{{$value->id}}/manager'"class="btn btn-danger btn-sm float-right very-little-margin">
										<i class="fa fa-close"></i>
									</button>
									<span>{{$value->name}} - <small>{{$value->email}}</small></span>
							</div>
						@endforeach
					</div>
					<form action ="/add-manager" method="post">
						{{csrf_field()}}
						<input type="text" placeholder="manager name" name="name" class="form-control little-margin" /> 
						<input type="email" placeholder="manager email" name="email" class="form-control little-margin" /> 
						<label>Which center will this manager be in charge of?</label>
						<select class="form-control" name="center_name"> 
							@foreach($centers as $center)
								<option>{{$center->name}}</option>
							@endforeach
						</select>
						<input type="password" placeholder="password" name="password" class="form-control little-margin" />
						<input type="password" placeholder="confirm password" name="confirm_password" class="form-control little-margin" />
						<button class="btn btn-success float-right">create</button>
					</form>
				</div> 
			</div>
			{{-- /END OF MANAGEMENT AREA --}}
				{{-- =================ADMINS AREA ===================== --}}
				<div class="col-md-6 col-mg-6" style="margin:0px;">
						<div class="thumbnail  t-hover clearfix"> 
							<h5>ADD NEW ADMIN</h5>
							<h6 style="margin-left:15x;">All Admins</h6>
							<div style="padding:20px;border-radius:5px; border: solid 1px #ccc;overflow-y:scroll;height:100px;"> 
									@foreach($admins as $value)
										<div class="clearfix">
										<button onclick="window.location='/admin/remove-item-{{$value->id}}/admin'"class="btn btn-danger btn-sm float-right very-little-margin">
													<i class="fa fa-close"></i>
												</button>
												<span>{{$value->name}} - <small>{{$value->email}}</small></span>
										</div>
									@endforeach
							</div>
							<form action="/add-admin" method="post">
								{{csrf_field()}}
								<input type="text" placeholder="admin name" name="name" class="form-control little-margin" /> 
								<input type="email" placeholder="admin email" name="email" class="form-control little-margin" required/> 
								<input type="password" placeholder="password" name="password" class="form-control little-margin" />
								<input type="password" placeholder="confirm password" name="confirm_password" class="form-control little-margin" />
								<button class="btn btn-success float-right">create</button>
							</form>
						</div> 
					</div>
					{{-- /END OF ADMINS AREA --}}
		</div>
	</div>
@endsection
@section('custom-js')
@endsection