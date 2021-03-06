@extends('admin/layouts/default')

@section('title')
Brand Share
@parent
@stop

@section('header_styles')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/gmaps/css/examples.css') }}"/>
<link href="{{ asset('assets/css/pages/googlemaps_custom.css') }}" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
@stop

{{-- Page content --}}
@section('content')

<section class="content-header">
	<h1>Brand Share</h1>
	<ol class="breadcrumb">
		<li>
			<a href="#">
				Dashboard
			</a>
		</li>
		<li><a href="#"> Reports</a></li>
		<li class="active">Brand Share</li>
	</ol>
</section>


<section class="content">
	<!-- Brand Share Report -->
	<div class="row ">
		<div class="col-md-12">
			<div class="panel panel-primary">
				<div class="panel-heading">
					<h3 class="panel-title">
						Brand Share Report
					</h3>
					<span class="pull-right">
						<i class="glyphicon glyphicon-chevron-up showhide clickable" title="Hide Panel content"></i>
						<i class="glyphicon glyphicon-remove removepanel clickable"></i>
					</span>
				</div>
				<div class="form">
					<form method="POST" action="{{ route('admin.brandShareAjax') }}">
						{{ csrf_field() }}
						<input type="hidden" name="request_to" value="brand_share">
						<input type="hidden" name="type" value="pie">
						<div class="row">
							<div class="col-md-2 col-sm-4 col-xs-12">
								<div class="form-group">
									<input type="date" class="form-control" name="report_from">
								</div>
							</div>
							<div class="col-md-2 col-sm-4 col-xs-12">
								<div class="form-group">
									<input type="date" class="form-control" name="report_to">
								</div>
							</div>
							<div class="col-md-3 col-sm-6 col-xs-12">
								<div class="form-group">
									<select class="form-control" name="sale_type">
										@if(isset($saleType) && count($saleType) > 0)
										@foreach($saleType as $sale)
										<option value="{{ $sale->id }}">{{ $sale->sale_type }}</option>
										@endforeach
										@endif
									</select>
								</div>
							</div>
							<div class="col-md-3 col-sm-6 col-xs-12">
								<div class="form-group">
									<select class="form-control brand_change city_change cat_change shop_change" name="brand" target=".brand_shop" city-target=".brand-city" cat-target=".brand-cat">
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
									<select class="form-control brand-cat" name="categories">
										<option value="-1" selected="true">All Products</option>
										@if(isset($categories) && count($categories) > 0)
										@foreach($categories as $c)
										<option value="{{ $c->id }}">{{ $c->Category }}</option>
										@endforeach
										@endif
									</select>
								</div>
							</div>
							<div class="col-md-3 col-sm-6 col-xs-12">
								<div class="form-group">
									<select class="form-control brand-city city_shop" name="cities" target=".city_shops">
										<option value="-1" selected="true">All Cities</option>
										@if(isset($cities) && count($cities) > 0)
										@foreach($cities as $c)
										<option value="{{ $c->id }}">{{ $c->name }}</option>
										@endforeach
										@endif
									</select>
								</div>
							</div>
							<div class="col-md-3 col-sm-6 col-xs-12">
								<div class="form-group">
									<select class="form-control brand_shop city_shops" name="shops">
										<option value="-1" selected="true">All Shops</option>
										@if(isset($shops) && count($shops) > 0)
										@foreach($shops as $s)
										<option value="{{ $s->id }}">{{ $s->name }}</option>
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
					<h3>Brand Share Report</h3>
					<div id="brand_share" style="min-width: 310px; height: 400px; max-width: 100%; margin: 0 auto"></div>
				</div>
			</div>
		</div>
	</div>


	<!-- Interception Share Report -->
	<div class="row ">
		<div class="col-md-12">
			<div class="panel panel-primary">
				<div class="panel-heading">
					<h3 class="panel-title">
						Interception Sale Share Report
					</h3>
					<span class="pull-right">
						<i class="glyphicon glyphicon-chevron-up showhide clickable" title="Hide Panel content"></i>
						<i class="glyphicon glyphicon-remove removepanel clickable"></i>
					</span>
				</div>
				<div class="form">
					<form method="POST" action="{{ route('admin.brandShareAjax') }}">
						{{ csrf_field() }}
						<input type="hidden" name="request_to" value="interception_brand">
						<input type="hidden" name="type" value="pie">
						<div class="row">
							<div class="col-md-3 col-sm-6 col-xs-12" style="display: none">
								<div class="form-group">
									<select class="form-control" name="sale_type">
										<option value="0">own</option>
									</select>
								</div>
							</div>
							<div class="col-md-3 col-sm-6 col-xs-12">
								<div class="form-group">
									<select class="form-control brand_change city_change cat_change shop_change" name="brand" target=".brand_shop" city-target=".brand-city" cat-target=".brand-cat">
										<option value="-1" selected="true">All Brands</option>
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
									<select class="form-control brand-cat" name="categories[]" multiple="multiple">
										<option value="-1" selected="true">All Products</option>
										@if(isset($categories) && count($categories) > 0)
										@foreach($categories as $c)
										<option value="{{ $c->id }}">{{ $c->Category }}</option>
										@endforeach
										@endif
									</select>
								</div>
							</div>
							<div class="col-md-3 col-sm-6 col-xs-12">
								<div class="form-group">
									<select class="form-control brand-city city_shop" name="cities[]" target=".city_shops"multiple="multiple">
										<option value="-1" selected="true">All Cities</option>
										@if(isset($cities) && count($cities) > 0)
										@foreach($cities as $c)
										<option value="{{ $c->id }}">{{ $c->name }}</option>
										@endforeach
										@endif
									</select>
								</div>
							</div>
							<div class="col-md-3 col-sm-6 col-xs-12">
								<div class="form-group">
									<select class="form-control brand_shop city_shops" name="shops[]" multiple="multiple">
										<option value="-1" selected="true">All Shops</option>
										@if(isset($shops) && count($shops) > 0)
										@foreach($shops as $s)
										<option value="{{ $s->id }}">{{ $s->name }}</option>
										@endforeach
										@endif
									</select>
								</div>
							</div>
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
									<input type="submit" class="btn btn-primary" name="submit" value="Filter">
								</div>
							</div>
						</div>
					</form>
				</div>
				<div class="panel-body text-center graph-body">
					<h3>SKU Brand Share Report</h3>
					<div id="interception_brand" style="min-width: 310px; height: 400px; max-width: 100%; margin: 0 auto"></div>
				</div>
			</div>
		</div>
	</div>


	<!-- Interception Total Report -->
	<div class="row ">
		<div class="col-md-12">
			<div class="panel panel-primary">
				<div class="panel-heading">
					<h3 class="panel-title">
						Total Interception
					</h3>
					<span class="pull-right">
						<i class="glyphicon glyphicon-chevron-up showhide clickable" title="Hide Panel content"></i>
						<i class="glyphicon glyphicon-remove removepanel clickable"></i>
					</span>
				</div>
				<div class="form">
					<form method="POST" action="{{ route('admin.brandShareAjax') }}">
						{{ csrf_field() }}
						<input type="hidden" name="request_to" value="total_interception">
						<input type="hidden" name="type" value="pie">
						<div class="row">
							<div class="col-md-3 col-sm-6 col-xs-12">
								<div class="form-group">
									<select class="form-control brand_change city_change cat_change shop_change" name="brand" target=".brand_shop" city-target=".brand-city" cat-target=".brand-cat">
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
									<select class="form-control brand-cat" name="categories[]" multiple="multiple">
										<option value="-1" selected="true">All Products</option>
										@if(isset($categories) && count($categories) > 0)
										@foreach($categories as $c)
										<option value="{{ $c->id }}">{{ $c->Category }}</option>
										@endforeach
										@endif
									</select>
								</div>
							</div>
							<div class="col-md-3 col-sm-6 col-xs-12">
								<div class="form-group">
									<select class="form-control brand-city city_shop" name="cities[]" multiple="multiple" target=".city_shops">
										<option value="-1" selected="true">All Cities</option>
										@if(isset($cities) && count($cities) > 0)
										@foreach($cities as $c)
										<option value="{{ $c->id }}">{{ $c->name }}</option>
										@endforeach
										@endif
									</select>
								</div>
							</div>
							<div class="col-md-3 col-sm-6 col-xs-12">
								<div class="form-group">
									<select class="form-control brand_shop city_shops" name="shops[]" multiple="multiple">
										<option value="-1" selected="true">All Shops</option>
										@if(isset($shops) && count($shops) > 0)
										@foreach($shops as $s)
										<option value="{{ $s->id }}">{{ $s->name }}</option>
										@endforeach
										@endif
									</select>
								</div>
							</div>
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
									<input type="submit" class="btn btn-primary" name="submit" value="Filter">
								</div>
							</div>
						</div>
					</form>
				</div>
				<div class="panel-body text-center graph-body">
					<h3>Store Brand Share Report</h3>
					<div id="total_interception" style="min-width: 310px; height: 400px; max-width: 100%; margin: 0 auto"></div>
				</div>
			</div>
		</div>
	</div>

	<!-- Target Vs Sale -->
	<div class="row ">
		<div class="col-md-12">
			<div class="panel panel-primary">
				<div class="panel-heading">
					<h3 class="panel-title">
						Target VS Sale
					</h3>
					<span class="pull-right">
						<i class="glyphicon glyphicon-chevron-up showhide clickable" title="Hide Panel content"></i>
						<i class="glyphicon glyphicon-remove removepanel clickable"></i>
					</span>
				</div>
				<div class="form">
					<form method="POST" action="{{ route('admin.brandShareAjax') }}">
						{{ csrf_field() }}
						<input type="hidden" name="request_to" value="target_sale">
						<input type="hidden" name="type" value="bar">
						<div class="row">
							<div class="col-md-3 col-sm-6 col-xs-12">
								<div class="form-group">
									<select class="form-control brand_change city_change cat_change shop_change" name="brand" target=".brand_shop" city-target=".brand-city" cat-target=".brand-cat">
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
									<select class="form-control brand-cat" name="categories">
										<option value="-1">All Products</option>
										@if(isset($categories) && count($categories) > 0)
										@foreach($categories as $c)
										<option value="{{ $c->id }}">{{ $c->Category }}</option>
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
										@foreach($cities as $c)
										<option value="{{ $c->id }}">{{ $c->name }}</option>
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
										<option value="{{ $s->id }}">{{ $s->name }}</option>
										@endforeach
										@endif
									</select>
								</div>
							</div>
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
									<input type="submit" class="btn btn-primary" name="submit" value="Filter">
								</div>
							</div>
						</div>
					</form>
				</div>
				<div class="panel-body text-center graph-body">
					<h3>Target VS Achievement Report</h3>
					<div id="target_sale" style="min-width: 310px; height: 400px; max-width: 100%; margin: 0 auto"></div>
				</div>
			</div>
		</div>
	</div>

	<!-- Brand Share Report -->
	<div class="row ">
		<div class="col-md-12">
			<div class="panel panel-primary">
				<div class="panel-heading">
					<h3 class="panel-title">
						Brand Size Report
					</h3>
					<span class="pull-right">
						<i class="glyphicon glyphicon-chevron-up showhide clickable" title="Hide Panel content"></i>
						<i class="glyphicon glyphicon-remove removepanel clickable"></i>
					</span>
				</div>
				<div class="form">
					<form method="POST" action="{{ route('admin.brandShareAjax') }}" id="skuSize">
						{{ csrf_field() }}
						<input type="hidden" name="request_to" value="brand_size">
						<input type="hidden" name="type" value="donut">
						<div class="row">
							<div class="col-md-3 col-sm-6 col-xs-12" style="display: none">
								<div class="form-group">
									<select class="form-control" name="sale_type">
										<option value="0">own</option>
									</select>
								</div>
							</div>
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
									<select class="form-control Brands" name="brand">
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
									<select class="form-control Products brand-cat" name="categories[]" multiple="multiple">
										<option value="-1" selected="true">All Products</option>
										@if(isset($categories) && count($categories) > 0)
										@foreach($categories as $c)
										<option value="{{ $c->id }}">{{ $c->Category }}</option>
										@endforeach
										@endif
									</select>
								</div>
							</div>
							<div class="col-md-3 col-sm-6 col-xs-12">
								<div class="form-group">
									<select class="form-control Sizes brand-sku" name="skus[]" multiple="multiple">
										<option value="-1" selected="true">All Sizes</option>
										@if(isset($skus) && count($skus) > 0)
										@foreach($skus as $s)
										<option value="{{ $s->id }}">{{ $s->name }}</option>
										@endforeach
										@endif
									</select>
								</div>
							</div>
							<div class="col-md-3 col-sm-6 col-xs-12">
								<div class="form-group">
									<select class="form-control Cities brand-city" name="cities[]" multiple="multiple">
										<option value="-1" selected="true">All Cities</option>
										@if(isset($cities) && count($cities) > 0)
										@foreach($cities as $c)
										<option value="{{ $c->id }}">{{ $c->name }}</option>
										@endforeach
										@endif
									</select>
								</div>
							</div>
							<div class="col-md-3 col-sm-6 col-xs-12">
								<div class="form-group">
									<select class="form-control Shops brand_shop" name="shops[]" multiple="multiple">
										<option value="-1" selected="true">All Shops</option>
										@if(isset($shops) && count($shops) > 0)
										@foreach($shops as $s)
										<option value="{{ $s->id }}">{{ $s->name }}</option>
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
					<h3>Brand Size Report</h3>
					<div id="brand_size" style="min-width: 310px; height: 400px; max-width: 100%; margin: 0 auto"></div>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>

<script type="text/javascript">
	$(".form form").on('submit',function(e){
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
				if(type == 'donut'){
					createDonutChart(res, target, type, title);
				}
			},
			error: function(err){
				console.log(err);
			}
		});
	});

	function createDonutChart(data, target, type, _title){
		Highcharts.chart(target, {
		    chart: {
		        type: 'pie',
		        options3d: {
		            enabled: true,
		            alpha: 45
		        }
		    },
		    title: {
		        text: _title
		    },
		    plotOptions: {
		        pie: {
		            innerSize: 100,
		            depth: 45,
					cursor: 'pointer',
		            dataLabels: {
						enabled: true,
						format: '<b>{point.name}</b>: {point.percentage:.1f} %',
						style: {
							color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
						}
					}
		        }
		    },
		    series: [{
		        name: 'Total Quantity',
		        data
		    }]
		});
	}


	function createPieChart(data, target, type, _title){


		Highcharts.chart(target, {
			chart: {
				plotBackgroundColor: null,
				plotBorderWidth: null,
				plotShadow: false,
				type: type
			},
			title: {
				text: _title
			},
			tooltip: {
				pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
			},
			plotOptions: {
				pie: {
					allowPointSelect: true,
					cursor: 'pointer',
					dataLabels: {
						enabled: true,
						format: '<b>{point.name}</b>: {point.percentage:.1f} %',
						style: {
							color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
						}
					}
				}
			},
			series: [{
				name: 'Brands',
				colorByPoint: true,
				data: data
			}]
		});
	}

	function createBarChart(data, target, type, _title){
		let dates = new Array() ;
		let sale = new Array() ;
		let _target = new Array() ;

		data.forEach(function(d){
			dates.push(d.categories.split(" ")[0]);
			sale.push(parseFloat(d.sale));
			_target.push(parseFloat(d.sale_target));
		});
		console.log(dates)
		console.log(sale)
		console.log(_target)
		Highcharts.chart('target_sale', {
			chart: {
				zoomType: 'xy'
			},
			title: {
				text: 'Targets VS Sale'
			},
			subtitle: {
				text: "title"
			},
			xAxis: [{
				categories: dates,
				crosshair: true
			}],
            yAxis: [{// Primary yAxis
            	labels: {
            		format: '{value}',
            		style: {
            			color: Highcharts.getOptions().colors[1]
            		}
            	},
            	title: {
            		text: 'Targets',
            		style: {
            			color: Highcharts.getOptions().colors[1]
            		}
            	}
                }, {// Secondary yAxis
                	title: {
                		text: 'Sale',
                		style: {
                			color: Highcharts.getOptions().colors[0]
                		}
                	},
                	labels: {
                		format: '{value}',
                		style: {
                			color: Highcharts.getOptions().colors[0]
                		}
                	},
                	opposite: true
                }],
                tooltip: {
                	shared: true
                },
                legend: {
                	layout: 'vertical',
                	align: 'left',
                	x: 120,
                	verticalAlign: 'top',
                	y: 100,
                	floating: true,
                	backgroundColor: (Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'
                },
                series: [{
                	name: 'Targets',
                	type: 'column',
                	color: "#339933",
                	data: _target,
                	tooltip: {
                		valueSuffix: ''
                	}

                }, {
                	name: 'Sale',
                	type: 'column',
                	data: sale,
                	tooltip: {
                		valueSuffix: ''
                	}
                }]
            });

	}

	$(".brand_change").on("change", function(){
		let target = $(this).attr("target");
		$.ajax({
			method: "GET",
			url: "{{ route('admin.getShopsByBrands') }}/"+$(this).val(),
			success: function(res){
				res = JSON.parse(res);
				let html = `<option value="-1" selected="true">All Shops</option>`;

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
				let html = `<option value="-1" selected="true">All Cities</option>`;

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

	$(".cat_change").on("change", function(){
		let target = $(this).attr("cat-target");
		let competition = $(this).parents("form").find("select[name=sale_type]").val()
		$.ajax({
			method: "GET",
			url: "{{ route('admin.getCatByBrands') }}/"+$(this).val()+"/"+competition,
			success: function(res){
				res = JSON.parse(res);
				let html = `<option value="-1" selected="true">All Products</option>`;

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
		let brand = $(this).parents('form').find('select[name=brand]').val();

		$.ajax({
			method: "GET",
			url: "{{ route('admin.getShopByBrandsNCity') }}/"+brand+"/"+$(this).val(),
			success: function(res){
				res = JSON.parse(res);
				let html = `<option value="-1" selected="true">All Shops</option>`;

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

	$("#skuSize select").on("change", function(){
		let target = $(this).attr("sku-target");
		let data = $(this).parents('form').serialize();
		let selection = $(this);
		console.log(data)
		$.ajax({
			method: "POST",
			url: "{{ route('admin.brandSizeReport') }}",
			data: data,
			success: function(res){
				res = JSON.parse(res);

				if(selection.attr("name") == "brand"){
					insertHTML("Products", res['categories']);
					insertHTML("Sizes", res['skus']);
					insertHTML("Cities", res['cities']);
					insertHTML("Shops", res['shops']);
				}
				else if(selection.attr("name") == "categories[]"){
					insertHTML("Sizes", res['skus']);
					insertHTML("Cities", res['cities']);
					insertHTML("Shops", res['shops']);
				}
				else if(selection.attr("name") == "skus[]"){
					insertHTML("Cities", res['cities']);
					insertHTML("Shops", res['shops']);
				}
				else if(selection.attr("name") == "cities[]"){
					insertHTML("Shops", res['shops']);
				}

				
			},
			error: function(err){
				console.log(err);
			}
		});
	});

	$('.brand-cat').select2();
	$('.brand-city').select2();
	$('.brand_shop').select2();
	$('.brand-sku').select2();

	function insertHTML(target, data){
		let html = `<option value="-1" selected="true">All ${target}</option>`;

		$.each(data, function( index, value ) {
			html += `<option value="${value[0]}">${value[1]}</option>`;
		  console.log( value );
		});

		$("."+target).html(html);
	}

	

</script>
@stop
