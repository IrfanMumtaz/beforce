@extends('admin/layouts/default')

@section('title')
Daily Attendance Report
@parent
@stop

{{-- Page content --}}
@section('content')
<style type="text/css">
*{-webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box;}
strong{font-weight:bold;}
img{border:0 none;vertical-align:top;width:auto;max-width:100%;max-height:auto;}
[class*="row"]{margin-top:0;}
[class*="row"]:empty:after{content:"\00a0";}
@media screen and (min-width:650px) and (max-width:1024px){
[class*="row"]{margin-top:0;}
[class*="row"]:empty:after{content:"\00a0";}
}
@media screen and (max-width:769px){
[class*="row"]:empty:after{content:"\00a0";}
}
.wojo.form .field{clear:both;margin:0 0 1em;position:relative;}
.wojo.form .field:after{content:'';display:table;clear:both;}
.wojo.form ::-webkit-input-placeholder{color:#AAA;}
.wojo.form ::-moz-placeholder{color:#AAA;}
.wojo.form :focus::-webkit-input-placeholder{color:#999;}
.wojo.form :focus::-moz-placeholder{color:#999;}
.wojo.form .field :disabled{opacity:0.85;}
.wojo.form .fields{clear:both;}
.wojo.form .fields:after{content:' ';display:block;clear:both;visibility:hidden;line-height:0;height:0;}
.wojo.form .fields > .field{clear:none;float:left;-webkit-box-sizing:border-box;-moz-box-sizing:border-box;-ms-box-sizing:border-box;box-sizing:border-box;}
.wojo.form .fields > .field:first-child{border-left:none;-webkit-box-shadow:none;box-shadow:none;}
.wojo.form .two.fields > .field{width:50%;padding-left:1%;padding-right:1%;}
.wojo.form .fields .field:first-child{padding-left:0;}
.wojo.form .fields .field:last-child{padding-right:0;}
@media only screen and (min-width:320px) and (max-width:768px){
.wojo.form .two.fields:not(.block) > .field{float:none;width:100%;diplay:block;padding-left:0;padding-right:0;}
.wojo.form .fields .field:first-child,.wojo.form .fields .field:last-child{padding-left:0;padding-right:0;}
}
.gm-style img{max-width:none!important;max-height:auto!important;}
/*! CSS Used from: Embedded */
.gm-style img{max-width:none;}
</style>
<section class="content-header">
    <h1>Stores</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('admin.dashboard') }}"> <i class="livicon" data-name="home" data-size="16" data-color="#000"></i>
                Dashboard
            </a>
        </li>
        <li>Reports</li>
        <li class="active">Daily Attendance Report</li>
    </ol>
</section>

<section class="content paddingleft_right15">
    <div class="row">
     @include('flash::message')
        <div class="panel panel-primary ">
            <div class="panel-heading clearfix">
                <h4 class="panel-title pull-left"> <i class="livicon" data-name="list-ul" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
                    Daily Attendance Report
                </h4>
            </div>
            <br />
            <input type="hidden" value="{{ url('') }}" id="base_url">
            <div class="panel-body table-responsive">
                 {!! Form::open(['route' => 'admin.reports.get_attendance_report','autocomplete'=>'off', 'method'=>'post']) !!}
                 <div class="form-group col-md-4 col-xs-4 col-sm-4">
                        <label>Select Date: </label>
                        @if(isset($selected_date))
                        <input type="date" id='date' name="date" value="{{$selected_date}}" class="form-control" placeholder="Date From" max="{{date('Y-m-d')}}">
                        {!! $errors->first('date', '<p class="alert alert-danger">:message</p>') !!}
                        @else
                        <input type="date" id='date' name="date" class="form-control" max="{{date('Y-m-d')}}" placeholder="Select Date" >
                        {!! $errors->first('date', '<p class="alert alert-danger">:message</p>') !!}
                        @endif
                    </div>
                    <div class="form-group col-md-4 col-xs-4 col-sm-4">
                        {!! Form::label('brands', 'Select brand:') !!}
                        {!! Form::Select('brands', $brands, null, ['class' => 'form-control','id'=>'brands']) !!}
                        {!! $errors->first('brands', '<p class="alert alert-danger">:message</p>') !!}
                    </div>
                    <div class="form-group col-md-4 col-xs-4 col-sm-4">
                        {!! Form::label('city', 'Select city:') !!}
                        {!! Form::Select('city', $cities, null, ['class' => 'form-control','id'=>'cities']) !!}
                        {!! $errors->first('city', '<p class="alert alert-danger">:message</p>') !!}
                    </div> 
                    <div class="form-group col-md-4 col-xs-4 col-sm-4">
                        {!! Form::label('role', 'Select User Role:') !!}
                        {!! Form::Select('role', $roles, null, ['class' => 'form-control','id'=>'roles']) !!}
                        {!! $errors->first('role', '<p class="alert alert-danger">:message</p>') !!}
                    </div>
                    <div class="form-group col-md-4 col-xs-4 col-sm-4">
                        {!! Form::label('department', 'Select Department:') !!}
                        {!! Form::Select('department', $departments, null, ['class' => 'form-control','id'=>'departments']) !!}
                        {!! $errors->first('department', '<p class="alert alert-danger">:message</p>') !!}
                    </div>                    
                    <div class="col-md-4 col-xs-4 col-sm-4">
                        <label> </label>
                        {!! Form::submit('Filter', ['class' => 'btn btn-primary','id'=>'attendance_submit_btn']) !!}
                    </div>
            {!! Form::close() !!}
            </div>
            <hr style="margin-top: 5px; margin-bottom: 5px;">
            <div class="row">
                    <div class="col-lg-3 col-md-6 col-sm-6 margin_10 animated fadeInLeftBig">
                        <!-- Trans label pie charts strats here-->
                        <div class="lightbluebg no-radius">
                            <a href="javascript:void(0)" style="color: #ffffff;" id="total_employees">
                            <div class="panel-body squarebox square_boxs">
                                <div class="col-xs-12 pull-left nopadmar">
                                    <div class="row">
                                        <div class="square_box col-xs-7 text-right">
                                            <span>Total Employees</span>
                                            <div class="number" id="myTargetElement1">{{ $total_employees }}</div>
                                        </div>
                                        <i class="fa fa-users fa-4x pull-right"></i>
                                    </div>
                                </div>
                            </div>
                        </a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6 col-md-6 margin_10 animated fadeInDownBig">
                        <!-- Trans label pie charts strats here-->
                        <div class="palebluecolorbg no-radius">
                            <a href="javascript:void(0)" style="color: #ffffff;" id="total_marked">
                            <div class="panel-body squarebox square_boxs">
                                <div class="col-xs-12 pull-left nopadmar">
                                    <div class="row">
                                        <div class="square_box col-xs-7 pull-left">
                                            <span>Marked</span>
                                            <div class="number" id="myTargetElement3">{!! $marked !!}</div>
                                        </div>
                                        <i class="fa fa-send fa-4x pull-right"></i>
                                    </div>
                                </div>
                            </div>
                        </a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-6 margin_10 animated fadeInUpBig">
                        <!-- Trans label pie charts strats here-->
                        <div class="redbg no-radius">
                            <a href="javascript:void(0)" style="color: #ffffff;" id="total_notmarked">
                            <div class="panel-body squarebox square_boxs">
                                <div class="col-xs-12 pull-left nopadmar">
                                    <div class="row">
                                        <div class="square_box col-xs-7 pull-left">
                                            <span>Not Marked</span>
                                            <div class="number" id="myTargetElement2">{!! $notmarked !!}</div>
                                        </div>
                                        <i class="fa fa-user fa-4x pull-right"></i>
                                    </div>                                    
                                </div>
                            </div>
                        </a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-6 margin_10 animated fadeInRightBig">
                        <!-- Trans label pie charts strats here-->
                        <div class="goldbg no-radius">
                            <a href="javascript:void(0)" style="color: #ffffff;" id="total_attendance">
                            <div class="panel-body squarebox square_boxs">
                                <div class="col-xs-12 pull-left nopadmar">
                                    <div class="row">
                                        <div class="square_box col-xs-7 pull-left">
                                            <span>Attendance</span>
                                            <div class="number" id="myTargetElement4">{!! $attendance_percentage !!}%</div>
                                        </div>
                                        <i class="fa fa-ticket fa-4x pull-right"></i>
                                    </div>
                                </div>
                            </div>
                        </a>
                        </div>
                    </div>
                </div>
                <hr style="margin-top: 5px; margin-bottom: 5px;">
                <div class="portlet box primary">
                            <div class="portlet-title">
                                <div class="caption">
                                    <i class="fa fa-clock-o"></i>
                                    Here you can see attendance report
                                </div>
                            </div>
                            <div class="portlet-body flip-scroll">
                                <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer"><div class="row"><div class="col-sm-12" id="table_responsive"><table class="table table-bordered table-striped table-condensed flip-content dataTable no-footer dtr-inline" id="DataTables_Table_0" role="grid" aria-describedby="DataTables_Table_0_info" style="width: 100%;">
                                    <thead class="flip-content">
                                        <tr role="row">
                                            <th class="sorting_asc" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" style="width: 59px;" aria-sort="ascending" aria-label="Code: activate to sort column descending">Sr.</th><th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" style="width: 100px;" aria-label="Company: activate to sort column ascending">Name</th><th class="numeric sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" style="width: 59px;" aria-label="Price: activate to sort column ascending">Attendance</th><th class="numeric sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" style="width: 83px;" aria-label="Change: activate to sort column ascending">User Role</th><th class="numeric sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" style="width: 106px;" aria-label="Change %: activate to sort column ascending">City</th><th class="numeric sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" style="width: 61px;" aria-label="Open: activate to sort column ascending">Location</th><th class="numeric sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" style="width: 200px;" aria-label="High: activate to sort column ascending">Attendance Start Time</th><th class="numeric sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" style="width: 50px;" aria-label="Low: activate to sort column ascending">Start Image</th><th class="numeric sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" style="width: 200px;" aria-label="Volume: activate to sort column ascending">Attendance End Time</th><th class="numeric sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" style="width: 82px;" aria-label="Volume: activate to sort column ascending">End Image</th></tr>
                                    </thead>
                                    <tbody>
                                    @php($i=1)
                                    @foreach($attendance_report_result as $attendance)
                                    @if($i % 2 == 0)
                                    <tr role="row" class="even">
                                    @else
                                    <tr role="row" class="odd">
                                    @endif
                                            <td tabindex="0" class="sorting_1">{!! $i++ !!}</td>
                                            <td>{!! $attendance['name'] !!}</td>
                                            @if($attendance['attendance_color']=='green')
                                            <td class="numeric" style="color: green;">{!! $attendance['attendance'] !!}</td>
                                            @else
                                            <td class="numeric" style="color: red;">{!! $attendance['attendance'] !!}</td>
                                            @endif
                                            <td class="numeric">{!! $attendance['role'] !!}</td>
                                            <td class="numeric">{!! $attendance['city'] !!}</td>
                                            @if($attendance['distance']>50)
                                            <td class="numeric" style="color: red;">{!! $attendance['location'] !!}</td>
                                            @else
                                            <td class="numeric">{!! $attendance['location'] !!}</td>
                                            @endif
                                            @if(isset($attendance['time_color']))
                                            <td class="numeric" style="color: {!! $attendance['time_color'] !!}">{!! $attendance['startTime'] !!}</td>
                                            @else
                                            <td class="numeric">{!! $attendance['startTime'] !!}</td>
                                            @endif
                                            @if($attendance['StartImage']=='N/A')
                                            <td class="numeric">{!! $attendance['StartImage'] !!}</td>
                                            @else
                                            <td><img class="myImg" src="{{ url('') }}/uploadimages/{!! $attendance['StartImage'] !!}" style="width: 50px;max-height: 100px;"></td>
                                            @endif
                                            <td class="numeric">{!! $attendance['endTime'] !!}</td>
                                            @if($attendance['EndImage']=='N/A')
                                            <td class="numeric">{!! $attendance['EndImage'] !!}</td>
                                            @else
                                            <td><img class="myImg" src="{{ url('') }}/uploadimages/{!! $attendance['EndImage'] !!}" style="width: 50px;max-height: 100px;"></td>
                                            @endif
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table></div></div></div>
                            </div>
                        </div>
                    <hr style="margin-top: 5px; margin-bottom: 5px;">
                    <div id="map-container">
                        <div style="width: 100%; height: 600px;" id="map"></div>
                    </div>
                    <hr style="margin-top: 5px; margin-bottom: 5px;">
        </div>
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
                                        {!! Form::open(['route' => 'admin.reports.get_attendance_report','autocomplete'=>'off', 'method'=>'post']) !!}
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
                        <!-- The Modal -->
    <div id="ZoomImageModal" class="ZoomImageModal modal">
      <span class="ZoomImageModal-close close">&times;</span>
      <img class="ZoomImageModal-modal-content modal-content"  id="img01">
      <div id="ZoomImageModal-caption"></div>
    </div>
 </div>
</section>
@stop
@section('footer_scripts')
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/series-label.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>
<script  src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDV4k8Ka_EuBhfcQ1EpZpNzuoFxAWb9lmQ&v=3"  type="text/javascript"></script>
    <script type="text/javascript" src="{{ asset('js/markerclusterer.js') }}"></script>
    <script>
        var data=<?php echo json_encode($map_data) ?>;
        $('#total_marked').on('click',function(){
                $("tr:contains(Absent)").hide();
                $("tr:contains(Present)").show();
                data=<?php echo json_encode($map_data) ?>;
                data.photos=$.grep(data.photos, function (element, index) {
                    return element.attendance == "Present";
                });
                data.count=data.photos.length;
                initialize();
            });
            $('#total_notmarked').on('click',function(){
                $("tr:contains(Present)").hide();
                $("tr:contains(Absent)").show();
                data=<?php echo json_encode($map_data) ?>;
                data.photos=$.grep(data.photos, function (element, index) {
                    return element.attendance == "Absent";
                });
                data.count=data.photos.length;
                initialize();
            });
            $('#total_employees').on('click',function(){
                $("tr:contains(Present)").show();
                $("tr:contains(Absent)").show();
                data=<?php echo json_encode($map_data) ?>;
                initialize();
            });
        var baseUrl =$('#base_url').val();
        var pics = null;
        var map = null;
        var markerClusterer = null;
        var markers = [];
        var infoWindow = null;
        function initialize() {
          var latlng = new google.maps.LatLng(30.3753, 69.3451);
          var options = {
            'zoom': 6,
            'center': latlng,
            'mapTypeId': google.maps.MapTypeId.ROADMAP
          };

          map = new google.maps.Map(document.getElementById('map'), options);
          pics = data.photos;
          infoWindow = new google.maps.InfoWindow();

          showMarkers();
        };

        function showMarkers() {
          markers = [];

          if (markerClusterer) {
            markerClusterer.clearMarkers();
          }

          for (var i = 0; i < data.count; i++) {
            var titleText = pics[i].employee_name;
            if (titleText === '') {
              titleText = 'No title';
            }
            var latLng = new google.maps.LatLng(pics[i].latitude,
                pics[i].longitude);

            var imageUrl =pics[i].marker;
            var markerImage = new google.maps.MarkerImage(imageUrl,
                new google.maps.Size(24, 32));
            var icon = {
                url: pics[i].marker, // url
                scaledSize: new google.maps.Size(32, 32), // scaled size
                origin: new google.maps.Point(0,0), // origin
                anchor: new google.maps.Point(0, 0) // anchor
            };
            var marker = new google.maps.Marker({
              'position': latLng,
              'icon': icon
            });

            var fn = markerClickFunction(pics[i], latLng);
            google.maps.event.addListener(marker, 'click', fn);
            markers.push(marker); 
          }

          window.setTimeout(time, 0);
        };

        function markerClickFunction(pic, latlng) {
          return function(e) {
            e.cancelBubble = true;
            e.returnValue = false;
            if (e.stopPropagation) {
              e.stopPropagation();
              e.preventDefault();
            }
            var title = pic.employee_name;
            var url = pic.UserAvatar;

            var infoHtml = '<div style="display: inline-block; overflow: auto; max-height: 654px; max-width: 654px;"><div style="overflow: auto;"><div class="info-window two fields"><div class="field"><img src='+pic.UserAvatar+' style="max-height: 150px;width: 110px;" alt="No Image"></div><div class="field"><div class="info-row"><strong>Name</strong>: '+pic.employee_name+'</div><div class="info-row"><strong>Brand</strong>: '+pic.brand+'</div><div class="info-row"><strong>Designation</strong>: '+pic.role+'</div><div class="info-row"><strong>Date of Deployment</strong>: '+pic.DateOfDeployment+'</div><div class="info-row"><strong>Store</strong>: '+pic.location+'</div><div class="info-row"><strong>City</strong>: '+pic.city+'</div></div></div></div></div>';


            infoWindow.setContent(infoHtml);
            infoWindow.setPosition(latlng);
            infoWindow.open(map);
          };
        };

        function change() {
          clear();
          showMarkers();
        };

         function time() {
            markerClusterer = new MarkerClusterer(map, markers, {imagePath: 'https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/m'});
        };

      google.maps.event.addDomListener(window, 'load', initialize);
    </script>
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
            $("table").DataTable({
                responsive:true,
                "paging":false,
                "info":false
            });
            $('#brands').on('change',function () {
            $('#cities').html('<option value="">Loading..</option>');
            let val = $(this).val();
            $.ajax({
            url: "{{url('/admin/brands/cities/')}}/"+val,
            type: 'GET',
            success: function(res) {
                $('#cities').html(res);
            }
            });
        });
            $('.row').css('padding','0px 15px');
            $('#table_responsive').css('padding-left','0px');
            $('#table_responsive').css('padding-right','0px');
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
@stop