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
	<h1>Gender Wise Report</h1>
	<ol class="breadcrumb">
		<li>
			<a href="#">
				Dashboard
			</a>
		</li>
		<li><a href="#"> Reports</a></li>
		<li class="active">Gender Wise Report</li>
	</ol>
</section>


<section class="content">
	<!-- Gender wise report -->
	<div class="row ">
		<div class="col-md-12">
			<div class="panel panel-primary">
				<div class="panel-heading">
					<h3 class="panel-title">
						Gender Wise sales Report
					</h3>
					<span class="pull-right">
						<i class="glyphicon glyphicon-chevron-up showhide clickable" title="Hide Panel content"></i>
						<i class="glyphicon glyphicon-remove removepanel clickable"></i>
					</span>
				</div>
				<div class="form">
					<form method="POST" action="{{ route('admin.genderWiseAjax') }}">
						{{ csrf_field() }}
						<input type="hidden" name="request_to" value="gender_wise">
						<input type="hidden" name="type" value="pie">
						<div class="row">
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

							<div class="col-md-3 col-sm-6 col-xs-12">
								<div class="form-group">
									<select class="form-control" name="shops">
										<option value="-1">All Shops</option>
										@if(isset($shops) && count($shops) > 0)
										@foreach($shops as $shop)
										<option value="{{ $shop->id }}">{{ $shop->name }}</option>
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
					<h3>Gender Wise Sales Report</h3>
					<div id="gender_wise" style="min-width: 310px; height: 400px; max-width: 100%; margin: 0 auto"></div>
				</div>
			</div>
		</div>
	</div>

	<!-- Gender wise report ratio -->
	<div class="row ">
		<div class="col-md-12">
			<div class="panel panel-primary">
				<div class="panel-heading">
					<h3 class="panel-title">
						Gender Wise sales Report Ratio
					</h3>
					<span class="pull-right">
						<i class="glyphicon glyphicon-chevron-up showhide clickable" title="Hide Panel content"></i>
						<i class="glyphicon glyphicon-remove removepanel clickable"></i>
					</span>
				</div>
				<div class="form">
					<form method="POST" action="{{ route('admin.genderWiseAjax') }}">
						{{ csrf_field() }}
						<input type="hidden" name="request_to" value="gender_wise_ratio">
						<input type="hidden" name="type" value="ratio">
						<div class="row">
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

							<div class="col-md-3 col-sm-6 col-xs-12">
								<div class="form-group">
									<select class="form-control" name="shops">
										<option value="-1">All Shops</option>
										@if(isset($shops) && count($shops) > 0)
										@foreach($shops as $shop)
										<option value="{{ $shop->id }}">{{ $shop->name }}</option>
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
					<h3>Gender Wise Sales Report Ratio</h3>
					<div id="gender_wise_ratio" style="min-width: 310px; height: 400px; max-width: 100%; margin: 0 auto"></div>
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
            	createBarChart(res, target, type, title);
            },
            error: function(err){
                console.log(err);
            }
        });
    });

    function createBarChart(data, target, type, title) {
        Highcharts.chart(target, {
            chart: {
                type: 'column'
            },
            title: {
                text: title,
            },
            xAxis: [{
                    categories: [title],

                }],
            yAxis: [{// Primary yAxis
                    labels: {
                        format: '{value}',
                        style: {
                            color: "#fb2f2f"
                        }
                    },
                    allowDecimals: false,
                    min: 0,
                    title: {
                        text: 'Tasks'
                    },
                }],
            
            legend: {
                layout: 'horizontal',
                align: 'center',
                verticalAlign: 'bottom',
                backgroundColor: (Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'
            },
            series: [{
                    name: 'Male Interception',
                    color: "#7cb5ec",
                    data: [data[0]['male']],
                    tooltip: {
                        valueSuffix: (type == 'ratio') ? '%' : ''
                    }
                   
                }, {
                    name: 'Female Interception',
                    color : "#FFFF33",
                    data: [data[0]['female']],
                    tooltip: {
                        valueSuffix: (type == 'ratio') ? '%' : ''
                    }
                    
                },{
                    name: 'Male Sale',
                     color: "#FFA500",
                    data: [data[1]['male']],
                    tooltip: {
                        valueSuffix: (type == 'ratio') ? '%' : ''
                    }
                    
                }, {
                    name: 'Female Sale',
                     color : "#008000",
                    data: [data[1]['female']],
                    tooltip: {
                        valueSuffix: (type == 'ratio') ? '%' : ''
                    }
                }, {
                    name: 'Male productivity',
                     color: "#A9A9A9",
                    data: [data[2]['male']],
                    tooltip: {
                        valueSuffix: (type == 'ratio') ? '%' : ''
                    }
                    
                }, {
                    name: 'Female productivity',
                     color : "#FF0000",
                    data: [data[2]['female']],
                    tooltip: {
                        valueSuffix: (type == 'ratio') ? '%' : ''
                    }
                    
                }]
        });
    }
</script>
@stop
