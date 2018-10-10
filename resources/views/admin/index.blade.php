@extends('admin/layouts/default')

{{-- Page title --}}
@section('title')
    BEForce 
    @parent
@stop

{{-- page level styles --}}
@section('header_styles')


    <link rel="stylesheet" href="{{ asset('assets/vendors/animate/animate.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/pages/only_dashboard.css') }}"/>
    <meta name="_token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/morrisjs/morris.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/pages/dashboard2.css') }}"/>
@stop

{{-- Page content --}}
@section('content')

    <section class="content-header">
        <h1>Welcome to BEForce Dashboard   <span class="hidden-xs header_info"></span></h1>

        <ol class="breadcrumb">
            <li class="active">
                <a href="#">
                    <i class="livicon" data-name="home" data-size="16" data-color="#333" data-hovercolor="#333"></i>
                    Dashboard
                </a>
            </li>
        </ol>
    </section>
    <section class="content">
        @if ($analytics_error != 0)
            <div class="alert alert-danger alert-dismissable margin5">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <strong>Error:</strong> You Need to add Google Analytics file for full working of the page
            </div>
        @endif
        <div class="row">
            <div class="col-lg-3 col-md-6 col-sm-6 margin_10 animated fadeInLeftBig">
                <!-- Trans label pie charts strats here-->
                <div class="widget-1">
                    <div class="panel-body squarebox square_boxs">
                        <div class="col-xs-12 pull-left nopadmar">
                            <div class="row">
                                <a href='http://test.brandedgesolutions.com/admin/employees' border=0> 
				<div class="square_box col-xs-7 text-left">
                                    <span>TOTAL EMPLOYEES</span>

                                    <div class="number" id="myTargetElement3"></div>
                                </div>
                                <span class="widget_circle3 pull-right">
 <i class="livicon livicon-evo-holder " data-name="user" data-l="true" data-c="#01BC8C"
    data-hc="#01BC8C" data-s="40"></i>
                                </span>
				</a>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 margin_10 animated fadeInLeftBig">
                <!-- Trans label pie charts strats here-->
                <div class="widget-1">
                    <div class="panel-body squarebox square_boxs">
                        <div class="col-xs-12 pull-left nopadmar">
                            <div class="row">
                                <a href='http://test.brandedgesolutions.com/admin/attendances' border=0>
				<div class="square_box col-xs-7 text-right">
                                    <span>TODAY ATTENDANCE</span>

                                    <div class="number" id="myTargetElement4"></div>
                                </div>
                                <span class="widget_circle4 pull-right">
 <i class="livicon livicon-evo-holder " data-name="eye-open" data-l="true" data-c="#F89A14"
    data-hc="#F89A14" data-s="40"></i>
                                </span>
				</a>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 margin_10 animated fadeInRightBig">
                <!-- Trans label pie charts strats here-->
                <div class="widget-1">
                    <div class="panel-body squarebox square_boxs">
                        <div class="col-xs-12 pull-left nopadmar">
                            <div class="row">
				<a href='http://test.brandedgesolutions.com/admin/tasks' border=0>
                                <div class="square_box col-xs-7 text-right">
                                    <span>PENDING TASKS</span>

                                    <div class="number" id="myTargetElement1"></div>
                                </div>
                                <span class="widget_circle1 pull-right">
 <i class="livicon livicon-evo-holder " data-name="flag" data-l="true" data-c="#e9573f"
    data-hc="#e9573f" data-s="40"></i>
                                </span>
				</a>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 margin_10 animated fadeInRightBig">
                <!-- Trans label pie charts strats here-->
                <div class="widget-1">
                    <div class="panel-body squarebox square_boxs">
                        <div class="col-xs-12 pull-left nopadmar">
                            <div class="row">
                                <div class="square_box col-xs-7 text-right">
                                    <span>% COMPLETED</span>

                                    <div class="number" id="myTargetElement2"></div>
                                </div>
                                <span class="widget_circle2 pull-right">
 <i class="livicon livicon-evo-holder " data-name="pen" data-l="true" data-c="#418BCA"
    data-hc="#418BCA" data-s="40"></i>
                                </span>

                            </div>

                        </div>
                    </div>
                </div>
            </div>

        </div>
        <!--/row-->
        <div class="row ">
            <div class="col-md-12 col-sm-12 no_padding">
                <div class="row">
                    <div class="col-md-12 ">
                        <!-- <div class="panel panel-border main_chart">
                            <div class="panel-heading ">
                                <h3 class="panel-title">
                                    <i class="livicon" data-name="barchart" data-size="16" data-loop="true" data-c="#EF6F6C" data-hc="#EF6F6C"></i> Periodic Attendance Report
                                </h3>
                            </div>
                            <div class="panel-body">
                                {!! $db_chart->html() !!}

                            </div>
                        </div> -->
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <h3 class="panel-title">
                                    <i class="livicon" data-name="signal" data-size="16" data-loop="true" data-c="#fff" data-hc="white" id="livicon-66" style="width: 16px; height: 16px;"><svg style="display: none;" height="16" version="1.1" width="16" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" style="overflow: hidden; position: relative;" id="canvas-for-livicon-66"><desc style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);">Created with Raphaël 2.1.2</desc><defs style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></defs><path fill="#ffffff" stroke="none" d="M6,27.5C6,27.781,5.781,28,5.5,28H2.5C2.219,28,2,27.781,2,27.5V24.5C2,24.219,2.219,24,2.5,24H5.5C5.781,24,6,24.219,6,24.5V27.5ZM12,27.5C12,27.781,11.781,28,11.5,28H8.5C8.219,28,8,27.781,8,27.5V22.5C8,22.219,8.219,22,8.5,22H11.5C11.781,22,12,22.219,12,22.5V27.5ZM18,27.5C18,27.781,17.781,28,17.5,28H14.5C14.219,28,14,27.781,14,27.5V18.5C14,18.219,14.219,18,14.5,18H17.5C17.781,18,18,18.219,18,18.5V27.5Z" stroke-width="0" transform="matrix(0.5,0,0,0.5,0,0)" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></path><path fill="#ffffff" stroke="none" d="M24,27.5C24,27.781,23.781,28,23.5,28H20.5C20.219,28,20,27.781,20,27.5V12.5C20,12.219,20.219,12,20.5,12H23.5C23.781,12,24,12.219,24,12.5V27.5Z" stroke-width="0" opacity="1" transform="matrix(0.5,0,0,0.5,0,0)" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); opacity: 1;"></path><path fill="#ffffff" stroke="none" d="M30,27.5C30,27.781,29.781,28,29.5,28H26.5C26.219,28,26,27.781,26,27.5V4.5C26,4.219,26.219,4,26.5,4H29.5C29.781,4,30,4.219,30,4.5V27.5Z" stroke-width="0" opacity="1" transform="matrix(0.5,0,0,0.5,0,0)" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); opacity: 1;"></path></svg></i>
                                    Periodic Attendance Report
                                </h3>
                                <span class="pull-right">
                             <i class="glyphicon glyphicon-chevron-up clickable"></i>
                             <i class="glyphicon glyphicon glyphicon-remove removepanel clickable"></i>
                        </span>
                            </div>
                            <div class="panel-body"><div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                                    <div class="container form">
                                        {!! Form::open(['route' => 'admin.dashboard.get_attendance','autocomplete'=>'off', 'method'=>'post']) !!}
                 <div class="form-group col-md-4 col-xs-4 col-sm-4">
                        <label>From Date: </label>
                        @if(isset($selected_attendance_from_date))
                        <input type="date" required id='attendance_from_date' name="attendance_from_date" value="{{$selected_attendance_from_date}}" class="form-control" placeholder="From Date" max="{{date('Y-m-d')}}">
                        @else
                        <input type="date" required id='attendance_from_date' name="attendance_from_date" class="form-control" max="{{date('Y-m-d')}}" placeholder="From Date" >
                        @endif
                    </div> 
                    <div class="form-group col-md-4 col-xs-4 col-sm-4">
                        <label>To Date: </label>
                        @if(isset($selected_attendance_to_date))
                        <input type="date" id='attendance_to_date' required name="attendance_to_date" value="{{$selected_attendance_to_date}}" class="form-control" placeholder="To Date" max="{{date('Y-m-d')}}">
                        @else
                        <input type="date" id='attendance_to_date' required name="attendance_to_date" class="form-control" max="{{date('Y-m-d')}}" placeholder="To Date" >
                        @endif
                    </div>           
                    <div class="col-md-4 col-xs-4 col-sm-4">
                        <label> </label>
                        {!! Form::submit('Filter', ['class' => 'btn btn-primary','id'=>'attendance_chart_btn']) !!}
                    </div>
                    {!! Form::close() !!}
                                    </div>
                                </div>
                                <div class="panel-group" id="periodic_attendace_chart" role="tablist" style="width: 100%; height: 400px; margin: 0 auto"></div>
                                <!-- nav-tabs-custom -->
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 ">
                        <div class="panel panel-border roles_chart">

                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <i class="livicon" data-name="users" data-size="16" data-loop="true" data-c="#F89A14"
                                       data-hc="#F89A14"></i>
                                    User Roles
                                </h4>

                            </div>
                            <div class="panel-body nopadmar">
                                {!! $user_roles->html() !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 ">
                        <div class="panel panel-border">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <i class="livicon" data-name="barchart" data-size="16" data-loop="true" data-c="#67C5DF"
                                       data-hc="#67C5DF"></i>
                                    Yearly visitors
                                </h4>

                            </div>
                            <div class="panel-body nopadmar">
                                <div id="bar_chart"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 ">
                        <div class="panel panel-border map">

                            <div class="panel-heading">
                                <h3 class="panel-title">
                                    <i class="livicon" data-name="map" data-size="16" data-loop="true" data-c="#515763"
                                       data-hc="#515763"></i>
                                    Users from countries
                                </h3>

                            </div>
                            <div class="panel-body nopadmar">
                                {!! $geo->html() !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-4 col-sm-5">
                <div class="panel panel-border">
                    <div class="panel-heading border-light">
                        <h3 class="panel-title">
                            <i class="livicon" data-name="users" data-size="18" data-color="#00bc8c" data-hc="#00bc8c"
                               data-l="true"></i>
                            Recent Users
                        </h3>
                    </div>
                    <div class="panel-body nopadmar users">
                        @foreach($users as $user )
                            <div class="media">
                                <div class="media-left">
                                    @if($user->pic)
                                        @if((substr($user->pic, 0,5)) == 'https')
                                            <img src="{{ $user->pic }}" alt="img"
                                                 class="media-object img-circle"/>
                                        @else
                                            <img src="{!! url('/').'/uploads/users/'.$user->pic !!}"
                                                 class="media-object img-circle"/>
                                        @endif
                                    @else
                                        <img src="{{ asset('assets/images/authors/no_avatar.jpg') }}" class="media-object img-circle" >
                                     @endif
                                </div>
                                <div class="media-body">
                                    <h5 class="media-heading">{{ $user->full_name }}</h5>
                                    <p>{{ $user->email }}  <span class="user_create_date pull-right">{{ $user->created_at->format('d M') }} </span></p>
                                </div>
                            </div>
                        @endforeach

                    </div>
                </div>
                <div class="panel panel-border">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <i class="livicon" data-name="eye-open" data-size="16" data-loop="true" data-c="#EF6F6C"
                               data-hc="#EF6F6C"></i>
                            This week visitors
                        </h4>

                    </div>
                    <div class="panel-body nopadmar">
                        <div id="visitors_chart"></div>
                    </div>
                </div>
                <div class="panel panel-border">
                    <div class="panel-heading border-light">
                        <h3 class="panel-title">
                            <i class="livicon" data-name="pen" data-size="16" data-color="#00bc8c" data-hc="#00bc8c"
                               data-l="true"></i>
                            Recent Blogs
                        </h3>
                    </div>
                    <div class="panel-body nopadmar blogs">
                        @foreach($blogs as $blog )
                            <div class="media">
                                <div class="media-left">
                                    @if($blog->author->pic)
                                        <img src="{!! url('/').'/uploads/users/'.$blog->author->pic !!}" class="media-object img-circle" >
                                    @else
                                        <img src="{{ asset('assets/images/authors/no_avatar.jpg') }}" class="media-object img-circle" >
                                    @endif
                                </div>
                                <div class="media-body">
                                    <h5 class="media-heading">{{ $blog->title }}</h5>

                                    <p>category:  {{ $blog->category->title }} <span class="user_create_date pull-right">by  {{ $blog->author->full_name }} </span></p>
                                </div>
                            </div>
                        @endforeach

                    </div>
                </div>
            </div>
        </div>
    </section>
    <div class="modal fade" id="editConfirmModal" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Alert</h4>
                </div>
                <div class="modal-body">
                    <p>You are already editing a row, you must save or cancel that row before edit/delete a new row</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>
@stop

{{-- page level scripts --}}
@section('footer_scripts')
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/series-label.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>
<script type="text/javascript">
        $(document).ready(function() {
            $('#attendance_from_date').on('change',function(){
                var min=$(this).val();
                var date = new Date(min);
                var newdate = new Date(date);
                newdate.setDate(newdate.getDate() + 1);
                var dd = newdate.getDate();
                if(dd<10)
                {
                    dd='0'+dd;
                }
                var mm = newdate.getMonth() + 1;
                if(mm<10)
                {
                    mm='0'+mm;
                }
                var y = newdate.getFullYear();
                var someFormattedDate = y + '-' + mm + '-' + dd;
                $('#attendance_to_date').attr('min',someFormattedDate);
            });
            $('#attendance_to_date').on('change',function(){
                var max=$(this).val();
                var date = new Date(max);
                var newdate = new Date(date);
                newdate.setDate(newdate.getDate() - 1);
                var dd = newdate.getDate();
                if(dd<10)
                {
                    dd='0'+dd;
                }
                var mm = newdate.getMonth() + 1;
                if(mm<10)
                {
                    mm='0'+mm;
                }
                var y = newdate.getFullYear();
                var someFormattedDate = y + '-' + mm + '-' + dd;
                $('#attendance_from_date').attr('max',someFormattedDate);
            });
            $('#attendance_submit_btn').css('width','100%');
            $('#attendance_chart_btn').css('width','100%');
            
            // High chart script code
            var subtitle='<?php echo $from.'-'.$to; ?>';
            var categories=<?php echo $xaxis; ?>;
            var yaxis=<?php echo $yaxis; ?>;
            var average=<?php echo $average; ?>;
            Highcharts.chart('periodic_attendace_chart', {

              title: {
                text: 'Periodic Attendance Report',
                x: -20
              },

              subtitle: {
                text: subtitle,
                x: -20
              },

              yAxis: {
                title: {
                  text: 'Attendance'
                },
                plotLines: [{
                    value: 0,
                    width: 1,
                    color: '#808080'
                }]
            },
                xAxis: {
                categories: categories,
                tickInterval: 3
              },
              tooltip: {
                valueSuffix: ''
            },
              legend: {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'middle',
                borderWidth: 0
              },


              series: [{
                name: 'Attendance',
                data: yaxis
              },{
                name: 'Average',
                data: average,
                color: 'red',
                marker: {enabled: false}
              }]

            });
// High chart code ends
        $('#highcharts-1aysmh7-0').css('width','100%');
        });
    </script>
    <script type="text/javascript" src="{{ asset('assets/vendors/moment/js/moment.min.js') }}"></script>
    <!--for calendar-->
    <script src="{{ asset('assets/vendors/moment/js/moment.min.js') }}" type="text/javascript"></script>
    <!-- Back to Top-->
    <script type="text/javascript" src="{{ asset('assets/vendors/countUp_js/js/countUp.js') }}"></script>
    {{--<script src="http://demo.lorvent.com/rare/default/vendors/raphael/js/raphael.min.js"></script>--}}
    <script src="{{ asset('assets/vendors/morrisjs/morris.min.js') }}"></script>

    <script>
        var useOnComplete = false,
            useEasing = false,
            useGrouping = false,
        options = {
            useEasing: useEasing, // toggle easing
            useGrouping: useGrouping, // 1,000,000 vs 1000000
            separator: ',', // character to use as a separator
            decimal: '.' // character to use as a decimal
        };
        var demo = new CountUp("myTargetElement1", 12.52, {{ $task_count }}, 0, 6, options);
        demo.start();
        var demo = new CountUp("myTargetElement2", 1, {{ $blog_count }}, 0, 6, options);
        demo.start();
        var demo = new CountUp("myTargetElement3", 24.02, {{ $emp_count }}, 0, 6, options);
        demo.start();
        var demo = new CountUp("myTargetElement4", 125, {{ $att_count }}, 0, 6, options);
        demo.start();

        $('.blogs').slimScroll({
            color: '#A9B6BC',
            height: 350 + 'px',
            size: '5px'
        });

        var week_data = {!! $month_visits !!};
        var year_data = {!! $year_visits !!};

        function lineChart() {
            Morris.Line({
                element: 'visitors_chart',
                data: week_data,
                lineColors: ['#418BCA', '#00bc8c', '#EF6F6C'],
                xkey: 'date',
                ykeys: ['pageViews', 'visitors'],
                labels: ['pageViews', 'visitors'],
                pointSize: 0,
                lineWidth: 2,
                resize: true,
                fillOpacity: 1,
                behaveLikeLine: true,
                gridLineColor: '#e0e0e0',
                hideHover: 'auto'

            });
        }
        function barChart() {
            Morris.Bar({
                element: 'bar_chart',
                data: year_data.length ? year_data :   [ { label:"No Data", value:100 } ],
                barColors: ['#418BCA', '#00bc8c'],
                xkey: 'date',
                ykeys: ['pageViews', 'visitors'],
                labels: ['pageViews', 'visitors'],
                pointSize: 0,
                lineWidth: 2,
                resize: true,
                fillOpacity: 0.4,
                behaveLikeLine: true,
                gridLineColor: '#e0e0e0',
                hideHover: 'auto'

            });
        }
        lineChart();
        barChart();
        $(".sidebar-toggle").on("click",function () {
            setTimeout(function () {
                $('#visitors_chart').empty();
                $('#bar_chart').empty();
                lineChart();
                barChart();
            },10);
        });

    </script>
    {!! Charts::scripts() !!}
    {!! $db_chart->script() !!}
    {!! $geo->script() !!}
    {!! $user_roles->script() !!}
    {{--{!! $line_chart->script() !!}--}}
@stop