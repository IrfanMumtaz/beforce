@extends('admin/layouts/default')

@section('title')
Interception Report
@parent
@stop

@section('header_styles')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/gmaps/css/examples.css') }}"/>
<link href="{{ asset('assets/css/pages/googlemaps_custom.css') }}" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.5.2/css/buttons.dataTables.min.css">
<style type="text/css">
	.dataTables_length{
		text-align: left;
	}
	h2{
		text-align: left;
	}
</style>
@stop

{{-- Page content --}}
@section('content')

<section class="content-header">
	<h1>Interception Report</h1>
	<ol class="breadcrumb">
		<li>
			<a href="#">
				Dashboard
			</a>
		</li>
		<li><a href="#"> Reports</a></li>
		<li class="active">Interception Report</li>
	</ol>
</section>


<section class="content">
	<div class="row">
		<div class="col-lg-4 col-md-4 col-sm-6 margin_10 animated fadeInLeftBig">
			<!-- Trans label pie charts strats here-->
			<div class="lightbluebg no-radius">
				<a href="javascript:void(0)" style="color: #ffffff;" id="total_employees">
				<div class="panel-body squarebox square_boxs">
					<div class="col-xs-12 pull-left nopadmar">
						<div class="row">
							<div class="square_box col-xs-7 text-right">
								<span>Total Interception</span>
								<div class="number" id="myTargetElement1">{{ @$interception ? count($interception) : 0 }}</div>
							</div>
							<i class="fa fa-users fa-4x pull-right"></i>
						</div>
					</div>
				</div>
				</a>
			</div>
		</div>
		<div class="col-lg-4 col-md-4 col-sm-6 margin_10 animated fadeInLeftBig">
			<!-- Trans label pie charts strats here-->
			<div class="redbg no-radius">
				<a href="javascript:void(0)" style="color: #ffffff;" id="total_employees">
				<div class="panel-body squarebox square_boxs">
					<div class="col-xs-12 pull-left nopadmar">
						<div class="row">
							<div class="square_box col-xs-7 text-right">
								<span>Total Competitor</span>
								<div class="number" id="myTargetElement1">{{ @$competitor ? count($competitor) : 0 }}</div>
							</div>
							<i class="fa fa-users fa-4x pull-right"></i>
						</div>
					</div>
				</div>
				</a>
			</div>
		</div>
		<div class="col-lg-4 col-md-4 col-sm-6 margin_10 animated fadeInLeftBig">
			<!-- Trans label pie charts strats here-->
			<div class="palebluecolorbg no-radius">
				<a href="javascript:void(0)" style="color: #ffffff;" id="total_employees">
				<div class="panel-body squarebox square_boxs">
					<div class="col-xs-12 pull-left nopadmar">
						<div class="row">
							<div class="square_box col-xs-7 text-right">
								<span>Total No Sale</span>
								<div class="number" id="myTargetElement1">{{ @$noSale ? count($noSale) : 0 }}</div>
							</div>
							<i class="fa fa-users fa-4x pull-right"></i>
						</div>
					</div>
				</div>
				</a>
			</div>
		</div>
	</div>
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
						<input type="hidden" name="request_to" value="brand_share">
						<div class="row">
							<div class="col-md-3 col-sm-6 col-xs-12">
								<div class="form-group">
									<input type="date" class="form-control" name="report_from" value="@if(isset($request)){{ $request['report_from'] }}@endif">
								</div>
							</div>
							<div class="col-md-3 col-sm-6 col-xs-12">
								<div class="form-group">
									<input type="date" class="form-control" name="report_to" value="@if(isset($request)){{ $request['report_to'] }}@endif">
								</div>
							</div>
							<div class="col-md-3 col-sm-6 col-xs-12">
								<div class="form-group">
									<select class="form-control brand_change city_change emp_change cat_change" name="brands" target=".brand_shop" city-target=".brand-city" bae-target=".bae-brands" cat-target=".brand-cat">
										<option value="-1">All Brands</option>
										@if(isset($brands) && count($brands) > 0)
										@foreach($brands as $brand)
										<option value="{{ $brand->id }}" @if(isset($request)){{ ($request['brands'] == $brand->id) ? "selected" : "" }}@endif>{{ $brand->BrandName }}</option>
										@endforeach
										@endif
									</select>
								</div>
							</div>
							<div class="col-md-3 col-sm-6 col-xs-12">
								<div class="form-group">
									<select class="form-control brand-cat" name="categories">
										<option value="-1">All Categories</option>
										@if(isset($categories) && count($categories) > 0)
										@foreach($categories as $c)
										<option value="{{ $c->id }}" @if(isset($request)){{ ($request['categories'] == $c->id) ? "selected" : "" }}@endif>{{ $c->Category }}</option>
										@endforeach
										@endif
									</select>
								</div>
							</div>
							<div class="col-md-3 col-sm-6 col-xs-12">
								<div class="form-group">

									<select class="form-control brand-city city_shop" name="cities" target=".city_shops">
										<option value="-1">All Cities</option>
										@if(isset($cities) && count($cities) > 0)
										@foreach($cities as $city)
										<option value="{{ $city->id }}" @if(isset($request)){{ ($request['cities'] == $city->id) ? "selected" : "" }}@endif>{{ $city->name }}</option>

										@endforeach
										@endif
									</select>
								</div>
							</div>
							<div class="col-md-3 col-sm-6 col-xs-12">
								<div class="form-group">

									<select class="form-control brand_shop city_shops" name="shops">
										<option value="-1">All Shops</option>
										@if(isset($shops) && count($shops) > 0)
										@foreach($shops as $s)
										<option value="{{ $s->id }}" @if(isset($request)){{ ($request['shops'] == $s->id) ? "selected" : "" }}@endif>{{ $s->name }}</option>

										@endforeach
										@endif
									</select>
								</div>
							</div>
							<div class="col-md-3 col-sm-6 col-xs-12">
								<div class="form-group">
									<select class="form-control bae-brands" name="employees">
										<option value="-1">All Employees</option>
										@if(isset($employees) && count($employees) > 0)
										@foreach($employees as $emp)
										<option value="{{ $emp->id }}" @if(isset($request)){{ ($request['employees'] == $emp->id) ? "selected" : "" }}@endif>{{ $emp->EmployeeName }}</option>
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
					<h2>Success full Interception Report</h2>
					<table class="table interception-report">
						<thead>
							<tr>
								<th>#</th>
								<th>Date</th>
								<th>Store</th>
								<th>Employees</th>
								<th>Name</th>
								<th>Contact</th>
								<th>Email</th>
								<th>Previous Brand</th>
								<th>Current Brand</th>
								<th>Customer Type</th>
								<th>Gender</th>
								<th>Age</th>
								<th>SKU</th>
							</tr>
						</thead>

						<tbody>
							@isset($interception)
							@foreach($interception as $key =>  $int)
								<tr>
									<td>{{ ++$key }}</td>
									<td>{{ date('Y-m-d', strtotime($int->date)) }}</td>
									<td>{{ $int->name }}</td>
									<td>{{ str_replace('"', '', $int->empName) }}</td>
									<td>{{ str_replace('"', '', $int->cusName) }}</td>
									<td>{{ str_replace('"', '', $int->Contact) }}</td>
									<td>{{ str_replace('"', '', $int->email) }}</td>
									<td>{{ $int->pName }}</td>
									<td>{{ $int->cName }}</td>
									<td>{{ ($int->pName == $int->cName) ? "existing" : "new" }}</td>
									<td>{{ str_replace('"', '', $int->gender) }}</td>
									<td>{{ $int->age }}</td>
									<td><p class="article">{{ $int->skuName }}</p></td>
								</tr>

							@endforeach
							@endisset
						</tbody>

					</table>

					<h2>Competitor Interception Report</h2>
					<table class="table competitor-report">
						<thead>
							<tr>
								<th>#</th>
								<th>Date</th>
								<th>Store</th>
								<th>Employees</th>
								<th>Name</th>
								<th>Contact</th>
								<th>Email</th>
								<th>Previous Brand</th>
								<th>Current Brand</th>
								<th>Customer Type</th>
								<th>Gender</th>
								<th>Age</th>
								<th>SKU</th>
							</tr>
						</thead>

						<tbody>
							@isset($competitor)
							@foreach($competitor as $key =>  $int)
								<tr>
									<td>{{ ++$key }}</td>
									<td>{{ date('Y-m-d', strtotime($int->date)) }}</td>
									<td>{{ $int->name }}</td>
									<td>{{ str_replace('"', '', $int->empName) }}</td>
									<td>{{ str_replace('"', '', $int->cusName) }}</td>
									<td>{{ str_replace('"', '', $int->Contact) }}</td>
									<td>{{ str_replace('"', '', $int->email) }}</td>
									<td>{{ $int->pName }}</td>
									<td>{{ $int->cName }}</td>
									<td>{{ ($int->pName == $int->cName) ? "existing" : "new" }}</td>
									<td>{{ str_replace('"', '', $int->gender) }}</td>
									<td>{{ $int->age }}</td>
									<td><p class="article">{{ $int->skuName }}</p></td>
								</tr>

							@endforeach
							@endisset
						</tbody>

					</table>

					<h2>No Sale Interception Report</h2>
					<table class="table nosale-report">
						<thead>
							<tr>
								<th>#</th>
								<th>Date</th>
								<th>Store</th>
								<th>Employees</th>
								<th>Name</th>
								<th>Contact</th>
								<th>Email</th>
								<th>Previous Brand</th>
								<th>Current Brand</th>
								<th>Customer Type</th>
								<th>Gender</th>
								<th>Age</th>
							</tr>
						</thead>

						<tbody>
							@isset($noSale)
							@foreach($noSale as $key =>  $int)
								<tr>
									<td>{{ ++$key }}</td>
									<td>{{ date('Y-m-d', strtotime($int->date)) }}</td>
									<td>{{ $int->name }}</td>
									<td>{{ str_replace('"', '', $int->empName) }}</td>
									<td>{{ str_replace('"', '', $int->cusName) }}</td>
									<td>{{ str_replace('"', '', $int->Contact) }}</td>
									<td>{{ str_replace('"', '', $int->email) }}</td>
									<td>{{ $int->pName }}</td>
									<td>{{ $int->cName }}</td>
									<td>{{ ($int->pName == $int->cName) ? "existing" : "new" }}</td>
									<td>{{ str_replace('"', '', $int->gender) }}</td>
									<td>{{ $int->age }}</td>
								</tr>

							@endforeach
							@endisset
						</tbody>

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
<script src="https://cdn.datatables.net/buttons/1.5.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.flash.min.js"></script>
<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.flash.min.js"></script>
<script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
<script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.html5.min.js"></script>
<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.print.min.js"></script>
<script type="text/javascript" language="javascript" src="https://cdn.jsdelivr.net/npm/readmore-js@2.2.1/readmore.min.js"></script>
<script type="text/javascript">

	

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

	$(".city_change").on("change", function(){
		let target = $(this).attr("city-target");
		$.ajax({
			method: "GET",
			url: "{{ route('admin.getCitiesByBrands') }}/"+$(this).val(),
			success: function(res){
				res = JSON.parse(res);
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

	$(".emp_change").on("change", function(){
		let target = $(this).attr("bae-target");
		$.ajax({
			method: "GET",
			url: "{{ route('admin.getBasByBrands') }}/"+$(this).val(),
			success: function(res){
				res = JSON.parse(res);
				let html = `<option value="-1">All Employees</option>`;

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

	$(".interception-report").DataTable({
        dom: 'Bfrtip',
        buttons: ['excel']
    });
	$(".competitor-report").DataTable({
        dom: 'Bfrtip',
        buttons: ['excel']
    });
	$(".nosale-report").DataTable({
        dom: 'Bfrtip',
        buttons: ['excel']
    });

	$('.article').readmore({
		speed: 75,
		lessLink: '<a href="#">Read less</a>',
		collapsedHeight: 20
	});

	

	$(".cat_change").on("change", function(){
		let target = $(this).attr("cat-target");
		$.ajax({
			method: "GET",
			url: "{{ route('admin.getCatByBrands') }}/"+$(this).val(),
			success: function(res){
				res = JSON.parse(res);
				let html = `<option value="-1">All Categories</option>`;

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

	

	$(".city_shop").on("change", function(){
		let target = $(this).attr("target");
		let brand = $(this).parents('form').find('select[name=brands]').val();

		$.ajax({
			method: "GET",
			url: "{{ route('admin.getShopByBrandsNCity') }}/"+brand+"/"+$(this).val(),
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
		});
	});

</script>
@stop
