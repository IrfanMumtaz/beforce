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
					<form method="POST" action="{{ route('admin.brandShareAjax') }}">
						{{ csrf_field() }}
						<input type="hidden" name="request_to" value="brand_share">
						<input type="hidden" name="type" value="pie">
						<div class="row">
							<div class="col-md-3 col-sm-6 col-xs-12">
								<div class="form-group">
									<select class="form-control" name="sale_type">
										<!-- <option value="-1">Select Sale Type</option> -->
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
									<select class="form-control" name="brand">
										<!-- <option value="-1">Select Brand</option> -->
										@if(isset($brands) && count($brands) > 0)
										@foreach($brands as $brand)
										<option value="{{ $brand->id }}">{{ $brand->BrandName }}</option>
										@endforeach
										@endif
									</select>
								</div>
							</div>
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
							<div class="col-md-2 col-sm-4 col-xs-12">
								<div class="form-group">
									<input type="submit" class="btn btn-primary" name="submit" value="Filter">
								</div>
							</div>
						</div>
					</form>
				</div>
				<div class="panel-body text-center graph-body">
					<h3>Interception Report</h3>
					<div id="brand_share" style="min-width: 310px; height: 400px; max-width: 100%; margin: 0 auto"></div>
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
			},
			error: function(err){
				console.log(err);
			}
		});
	});


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


</script>
@stop
