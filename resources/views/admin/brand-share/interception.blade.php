@extends('admin/layouts/default')

@section('title')
Brand Share
@parent
@stop

@section('header_styles')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/gmaps/css/examples.css') }}"/>
<link href="{{ asset('assets/css/pages/googlemaps_custom.css') }}" rel="stylesheet">
@stop

{{-- Page content --}}
@section('content')

<section class="content-header">
	<h1>Interception Share</h1>
	<ol class="breadcrumb">
		<li>
			<a href="#">
				Dashboard
			</a>
		</li>
		<li><a href="#"> Reports</a></li>
		<li class="active">Interception</li>
	</ol>
</section>


<section class="content">
	<!-- Interception Report -->
	<div class="row ">
		<div class="col-md-12">
			<div class="panel panel-primary">
				<div class="panel-heading">
					<h3 class="panel-title">
						Interception Report
					</h3>
					<span class="pull-right">
						<i class="glyphicon glyphicon-chevron-up showhide clickable" title="Hide Panel content"></i>
						<i class="glyphicon glyphicon-remove removepanel clickable"></i>
					</span>
				</div>
				<div class="form">
					<form method="POST" action="{{ route('admin.interceptionReport') }}">
						{{ csrf_field() }}
						<input type="hidden" name="request_to" value="complete_interception">
						<div class="row">
							<div class="col-md-3 col-sm-6 col-xs-12">
								<div class="form-group">
									<input type="date" class="form-control" name="report_from">
								</div>
							</div>
							<div class="col-md-3 col-sm-6 col-xs-12">
								<div class="form-group">
									<input type="date" class="form-control" name="report_to">
								</div>
							</div>
							<div class="col-md-3 col-sm-6 col-xs-12">
								<div class="form-group">
									<select class="form-control brand_change brand_to_city brand_to_bas" name="brands" target=".inter_shops" city_target=".inter_cities" bas_target=".inter_bas">
										<option value="-1">All Brands</option>
										@if(isset($brands) && count($brands) > 0)
										@foreach($brands as $brand)
										<option value="{{ $brand->id }}">{{ $brand->BrandName }}</option>
										@endforeach
										@endif
									</select>
								</div>
							</div>
							<div class="col-md-3 col-sm-6 col-xs-12">
								<div class="form-group">
									<select class="form-control inter_cities" name="cities">
										<option value="-1">All Cities</option>
										@if(isset($cities) && count($cities) > 0)
										@foreach($cities as $city)
										<option value="{{ $city->id }}">{{ $city->name }}</option>
										@endforeach
										@endif
									</select>
								</div>
							</div>
							<div class="col-md-3 col-sm-6 col-xs-12">
								<div class="form-group">
									<select class="form-control inter_shops" name="shops">
										<option value="-1">All Shops</option>
										@if(isset($shops) && count($shops) > 0)
										@foreach($shops as $shop)
										<option value="{{ $shop->id }}">{{ $shop->name }}</option>
										@endforeach
										@endif
									</select>
								</div>
							</div>
							<div class="col-md-3 col-sm-6 col-xs-12">
								<div class="form-group">
									<select class="form-control inter_bas" name="employees">
										<option value="-1">All BAS</option>
										@if(isset($employees) && count($employees) > 0)
										@foreach($employees as $employee)
										<option value="{{ $employee->id }}">{{ $employee->EmployeeName }}</option>
										@endforeach
										@endif
									</select>
								</div>
							</div>
							<div class="col-md-2 col-sm-4 col-xs-12">
								<div class="form-group">
									<input type="submit" class="btn btn-primary" name="submit" value="Filter">
								</div>
							</div>
						</div>
					</form>
				</div>
				<div class="panel-body text-center graph-body">
					<table>
						<tr>
							<th>ID</th>
							<th>Name</th>
							<th>Name</th>
						</tr>
					</table>
				</div>
			</div>
		</div>
	</div>


</section>

@stop

{{-- page level scripts --}}

@section('footer_scripts')
<script src="https://code.highcharts.com/highcharts.js"></script>
<script type="text/javascript" src="{{ asset('assets/js/pages/maps_api.js') }}"></script>
<script type="text/javascript"
src="http://maps.google.com/maps/api/js?key=AIzaSyADWjiTRjsycXf3Lo0ahdc7dDxcQb475qw&libraries=places"></script>
<script type="text/javascript" src="{{ asset('assets/vendors/gmaps/js/gmaps.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/pages/custommaps.js') }}"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script type="text/javascript">
	/* $(".form form").on('submit',function(e){
		e.preventDefault();
		data = $(this).serialize();
		var from = $(this).find("input[name=report_from]").val();
		var to = $(this).find("input[name=report_to]").val();
		var target = $(this).find("input[name=request_to]").val();
		var type = $(this).find("input[name=type]").val();
		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
			}
		});
		$.ajax({
			method: "POST",
			url: $(this).attr('action'),
			data: data,
			dataType: "json",
			success: function(res){
				console.log(res)
				let title = `from ${from} to ${to}`;
				if(type == 'pie'){
					createPieChart(res, target, type, title);
				}
				if(type == 'bar'){
					createBarChart(res, target, type, title);
				}
			},
			error: function(err){
				console.log(err);
			}
		});
	}); */

	$(".brand_change").on("change", function(){
		let target = $(this).attr("target");
		$.ajax({
			method: "GET",
			url: "{{ route('admin.getShopsByBrands') }}/"+$(this).val(),
			success: function(res){
				res = JSON.parse(res);
				let html = `<option value="-1">All Shops</option>`;

				res.forEach((r) =>{
					html += `<option value="${r.id}">${r.name}</option>`;
				});
				console.log(target);
				$(target).html(html);
			},
			error: function(err){
				console.log(err);
			}
		})
	});

	$(".brand_to_city").on("change", function(){
		let target = $(this).attr("city_target");
		$.ajax({
			method: "GET",
			url: "{{ route('admin.getCitiesByBrands') }}/"+$(this).val(),
			success: function(res){
				res = JSON.parse(res);
				console.log(res);
				let html = `<option value="-1">All Cities</option>`;

				res.forEach((r) =>{
					html += `<option value="${r.id}">${r.name}</option>`;
				});
				console.log(target);
				$(target).html(html);
			},
			error: function(err){
				console.log(err);
			}
		})
	});

	$(".brand_to_bas").on("change", function(){
		let target = $(this).attr("bas_target");
		$.ajax({
			method: "GET",
			url: "{{ route('admin.getBasByBrands') }}/"+$(this).val(),
			success: function(res){
				res = JSON.parse(res);
				console.log(res);
				let html = `<option value="-1">All Bas</option>`;

				res.forEach((r) =>{
					html += `<option value="${r.id}">${r.name}</option>`;
				});
				console.log(target);
				$(target).html(html);
			},
			error: function(err){
				console.log(err);
			}
		})
	});


</script>
@stop
