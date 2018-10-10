@extends('admin/layouts/default')

@section('title')
Task
@parent
@stop

{{-- page level styles --}}
@section('header_styles')
    
    <link href="{{ asset('assets/vendors/fullcalendar/css/fullcalendar.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/vendors/fullcalendar/css/fullcalendar.print.css') }}" rel="stylesheet"  media='print' type="text/css">
    <!--<link href="{{ asset('assets/vendors/iCheck/css/all.css') }}"  rel="stylesheet" type="text/css" />-->
    <link href="{{ asset('assets/css/pages/calendar_custom.css') }}" rel="stylesheet" type="text/css" />
    <!--page level styles ends-->
@stop

@section('content')

<section class="content-header">
    <h1>Task</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('admin.dashboard') }}"> <i class="livicon" data-name="home" data-size="16" data-color="#000"></i>
                Dashboard
            </a>
        </li>
        <li>Tasks</li>
        <li class="active">Create Task </li>
    </ol>
</section>
<section class="content paddingleft_right15">
<div class="row">
    @include('flash::message')
 <div class="panel panel-primary">
        <div class="panel-heading">
            <h4 class="panel-title"> <i class="livicon" data-name="user" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
                Create New  Task
            </h4></div>
        <br />
        <div class="panel-body">
        {!! Form::open(['route' => 'admin.tasks.store','autocomplete'=>'off']) !!}

            @include('admin.tasks.fields')

        {!! Form::close() !!}
    </div>
  </div>
 </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="box">
                                <div class="box-body">
                                    <div id="calendar"></div>

                                    <div id="fullCalModal" class="modal fade">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span> <span class="sr-only">close</span></button>
                                                    <h4 id="modalTitle" class="modal-title"></h4>
                                                </div>
                                                <div id="modalBody" class="modal-body">
                                                    <i class="mdi-action-alarm-on"></i>&nbsp;&nbsp;Start: <span id="startTime"></span>&nbsp;&nbsp;- End: <span id="endTime"></span>
                                                    <h4 id="eventInfo"></h4>
                                                    <br>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-raised btn-danger" data-dismiss="modal">Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /.box -->
                        </div>
                        <!-- /.col -->
                    </div>
                    
                    <!-- Modal -->
                    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close reset" data-dismiss="modal" aria-hidden="true">&times;</button>
                                    <h4 class="modal-title" id="myModalLabel">
                                        <i class="fa fa-plus"></i> Create Event
                                    </h4>
                                </div>
                                <div class="modal-body">
                                    <div class="input-group">
                                        <input type="text" id="new-event" class="form-control" placeholder="Event">
                                        <div class="input-group-btn">
                                            <button type="button" id="color-chooser-btn" class="color-chooser btn btn-info dropdown-toggle" data-toggle="dropdown">
                                                Type
                                                <span class="caret"></span>
                                            </button>
                                            <ul class="dropdown-menu pull-right" id="color-chooser">
                                                <li>
                                                    <a class="palette-primary" href="#">Primary</a>
                                                </li>
                                                <li>
                                                    <a class="palette-success" href="#">Success</a>
                                                </li>
                                                <li>
                                                    <a class="palette-info" href="#">Info</a>
                                                </li>
                                                <li>
                                                    <a class="palette-warning" href="#">warning</a>
                                                </li>
                                                <li>
                                                    <a class="palette-danger" href="#">Danger</a>
                                                </li>
                                                <li>
                                                    <a class="palette-default" href="#">Default</a>
                                                </li>
                                            </ul>
                                        </div>
                                        <!-- /btn-group -->
                                    </div>
                                    <!-- /input-group -->
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-danger pull-right reset" data-dismiss="modal">
                                        Close
                                        <i class="fa fa-times"></i>
                                    </button>
                                    <button type="button" class="btn btn-success pull-left" id="add-new-event" data-dismiss="modal">
                                        <i class="fa fa-plus"></i> Add
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal fade" id="evt_modal" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close reset" data-dismiss="modal" aria-hidden="true">&times;</button>
                                    <h6 class="modal-title" id="myModalLabel">
                                        <i class="fa fa-plus"></i>
                                        Edit Event
                                    </h6>

                                </div>
                                <div class="modal-body">
                                    <div class="input-group">
                                        {!! Form::open(['route' => ['admin.tasks.calendarupdated' ], 'method' => 'post']) !!}
                                        <div class="form-group col-sm-4" style="display: none;">
                                        {!! Form::label('modalEmployeeId', 'EmployeeId:') !!}
                                        {!! Form::text('modalEmployeeId', $employeeId, null, ['class' => 'form-control','id'=>'modalemployeeId']) !!}
                                    </div>
                                    <div class="form-group col-sm-4" style="display: none;">
                                        {!! Form::text('task_id', null, ['class' => 'form-control','id'=>'task_id']) !!}
                                    </div>
                                    
                                    <!-- Tasktype Field -->
                                    <div class="form-group col-sm-4">
                                        {!! Form::label('modalTasktype', 'Tasktype:') !!}
                                        {!! Form::select('modalTasktype', [null => '--Select Task Type-- ','Visit Shop' => 'Visit Shop', 'Scan QR' => 'Scan QR'], null, ['class' => 'form-control','id'=>'modalTasktype','onchange' => 'modalupdatetaskfield(this)','required']) !!}
                                    </div>
                                    <div class="form-group col-sm-4" id="modalStore">
                                        {!! Form::label('modalSelectStore', 'Selectstore:') !!}
                                        {!! Form::Select('modalSelectStore', ($stores), null, ['class' => 'form-control','id'=>'modalSelectStore']) !!}
                                    </div>
    
                                    <div class="form-group col-sm-4" id="modalAsset">
                                        {!! Form::label('modalSelectAsset', 'Selectasset:') !!}
                                        {!! Form::Select('modalSelectAsset', ($assets), null, ['class' => 'form-control','id'=>'modalSelectAsset']) !!}
                                    </div>
                                    
                                    <!-- Startdate Field -->
                                    <div class="form-group col-sm-4">
                                        {!! Form::label('modalStartDate', 'Startdate:') !!}
                                        {!! Form::date('modalStartDate', null, ['class' => 'form-control','min'=>date("Y-m-d"),'id'=>'modalStartDate']) !!}
                                    </div>
                                    
                                    <!-- Enddate Field -->
                                    <div class="form-group col-sm-4">
                                        {!! Form::label('modalEndDate', 'Enddate:') !!}
                                        {!! Form::date('modalEndDate', null, ['class' => 'form-control','min'=>date("Y-m-d"),'id'=>'modalEndDate']) !!}
                                    </div>
                                    
                                    <!-- Undefined Field -->
                                    <div class="form-group col-sm-12">
                                        {!! Form::label('modalTaskDetails', 'Task Details:') !!}
                                        {!! Form::textarea('modalTaskDetails', null, ['class' => 'form-control','rows'=>6,'cols'=>50,'required','id'=>'modalTaskDetails']) !!}
                                    </div>
                                    
                                    <!-- Submit Field -->
                                    <div class="form-group col-sm-12 text-center"><br/>
                                    <div class="modal-footer">
                                        {!! Form::submit('Save', ['class' => 'btn btn-success pull-left']) !!}
                                        <button type="button" class="btn btn-danger pull-right reset" data-dismiss="modal">
                                        Close
                                    </button>
                                    <button type="button" class="btn btn-info pull-left" id="copy_event" data-dismiss="modal"> Copy
                                    </button>
                                    <button type="button" class="btn btn-danger pull-left" id="remove_event" data-dismiss="modal"> Remove
                                    </button>
                                    </div>
                                    </div>
                                    {!! Form::close() !!}
                                    </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
</section>
{!! Form::open(['route' => 'admin.tasks.store', 'id'=>'task_copier_form']) !!}
    {!! Form::hidden('EmployeeId', $employeeId, ['class' => 'form-control', 'id'=>'employeeId_c']) !!}
    {!! Form::hidden('tasks[1][Tasktype]', '', ['class' => 'form-control', 'id'=>'tasktype_c']) !!}
    {!! Form::hidden('tasks[1][SelectStore]', '', ['class' => 'form-control', 'id'=>'store_c']) !!}
    {!! Form::hidden('tasks[1][SelectAsset]', '', ['class' => 'form-control', 'id'=>'asset_c']) !!}
    {!! Form::hidden('tasks[1][StartDate]', '', ['class' => 'form-control', 'id'=>'startdate_c']) !!}
    {!! Form::hidden('tasks[1][EndDate]', '', ['class' => 'form-control', 'id'=>'enddate_c']) !!}
    {!! Form::hidden('tasks[1][TaskDetails]', '', ['class' => 'form-control', 'id'=>'taskdetails_c']) !!}
    {!! Form::close() !!}
 @stop

{{-- page level scripts --}}
@section('footer_scripts')
        <!--<script src="{{ asset('assets/vendors/moment/js/jquery.min.js') }}"  type="text/javascript"></script>-->
        <script src="{{ asset('assets/vendors/moment/js/moment.min.js') }}"  type="text/javascript"></script>
        <script src="{{ asset('assets/vendors/fullcalendar/js/fullcalendar.min.js') }}"  type="text/javascript"></script>
        <script src="{{ asset('assets/vendors/iCheck/js/icheck.js') }}"></script>
        <script src="{{ asset('assets/js/pages/calendar.js') }}"  type="text/javascript"></script>
        <script>

        $('#modalStore').hide(); 
        $('#modalAsset').hide();
        var id='<?php echo $employeeId; ?>';
        $.ajax({ type: "get", 
            url:"{{url('/admin/tasks/users/')}}/"+id, 
            dataType: 'json',
            success: function (response){ 
                add_calendar_task(response);
                } 
            });
            var type=$('#modalTasktype').val();
            if(type=='Visit Shop')
            {
             $('#modalStore').show(); 
             $('#modalAsset').hide();
            }
            else if(type=='Scan QR')
            {
                $('#modalAsset').show();
                $('#modalStore').hide();
            }
            function modalupdatetaskfield(input) {
            var val=input.value;
            var text=input.name;
            if(val=='Visit Shop')
            {
                $('#modalAsset').css('display','none');
                $('#modalStore').show();
                $('#modalStore').prop('required',true);
                $('#modalAsset').prop('required',false);
            }
            else if(val=='Scan QR')
            {
                $('#modalAsset').show();
                $('#modalStore').hide(); 
                $('#modalAsset').prop('required',true);
                $('#modalStore').prop('required',false);
            }
            }
            $('#remove_event').on('click',function(){
                var taskid=$('#task_id').val();
                remove_calendar_task(taskid);
                $.ajax({ type: "get", 
                url:"{{url('/admin/tasks/delete/')}}/"+taskid, 
                success: function (response){ 
                alert(response);
                } 
                });
            });
            $('#copy_event').on('click',function(){
                var startdate=$('#modalStartDate').val();
                var enddate=$('#modalEndDate').val();
                if(new Date(startdate) <= new Date(enddate)) 
                {
                    alert('start date: '+);
                var task=$('#modalTasktype').val();
                $('#tasktype_c').val(task);
                $('#startdate_c').val(startdate);
                $('#enddate_c').val(enddate);
                $('#taskdetails_c').val($('#modalTaskDetails').val());
                if(task=='Visit Shop')
                {
                    $('#store_c').val($('#modalSelectStore').val());
                }
                else if(task=='Scan QR')
                {
                    $('#asset_c').val($('#modalSelectAsset').val());
                }
                // $('#task_copier_form').submit();
                }
                else {

                }
            });
            
</script>
@stop


@section('footer_scripts')

    <script type="text/javascript">
        $(document).ready(function() {
            $("form").submit(function() {
                $('input[type=submit]').attr('disabled', 'disabled');
                return true;
            });
        });
    </script>
    
@stop
