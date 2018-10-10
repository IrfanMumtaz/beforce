@if(isset($employeeId) && !empty($employeeId))
<div id="multipletasks">
<div class="taskwrapper" data-item="1">

    <hr>
<!-- Tasktype Field -->
<div class="form-group col-sm-4" style="display: none;">
    {!! Form::label('EmployeeId', 'EmployeeId:') !!}
    {!! Form::text('EmployeeId', $employeeId, null, ['class' => 'form-control task_field','id'=>'employeeId']) !!}
</div>

<!-- Tasktype Field -->
<div class="form-group col-sm-4">
    {!! Form::label('Tasktype', 'Tasktype:') !!}
    @if(!empty($selected_task_type))
    {!! Form::select('Tasktype', [null => '--Select Task Type-- ','Visit Shop' => 'Visit Shop', 'Scan QR' => 'Scan QR'], $selected_task_type, ['class' => 'form-control task_type task_field task_type select-choosen','id'=>'Tasktype' ,'data-name'=>'Tasktype','onchange' => 'updatetaskfield(this)','required']) !!}
    @else
    {!! Form::select('tasks[1][Tasktype]', [null => '--Select Task Type-- ','Visit Shop' => 'Visit Shop', 'Scan QR' => 'Scan QR'], null, ['class' => 'form-control task_type task_field task_type select-choosen','id'=>'Tasktype','data-name'=>'Tasktype','onchange' => 'updatetaskfield(this)','required']) !!}
    @endif
</div>
@if(!empty($selected_shop_id))
<div class="form-group col-sm-4">
    {!! Form::label('SelectStore', 'Selectstore:') !!}
    @if(!empty($selected_shop_id))
    {!! Form::Select('SelectStore', ($stores), $selected_shop_id, ['class' => 'form-control task_field select-choosen','data-id'=>'$selected_shop_id','id'=>'SelectStore1','data-name'=>'SelectStore']) !!}
    @else
    {!! Form::Select('tasks[1][SelectStore]', ($stores), null, ['class' => 'form-control task_field select-choosen','id'=>'SelectStore1','data-name'=>'SelectStore']) !!}
    @endif
</div>
@endif

<!-- Selectstore Field -->
<div class="form-group col-sm-4 stores" id="store1">
    {!! Form::label('SelectStore', 'Selectstore:') !!}
    @if(!empty($selected_shop_id))
    {!! Form::Select('tasks[1][SelectStore]', ($stores), $selected_shop_id, ['class' => 'form-control task_field select-choosen','data-id'=>'$selected_shop_id','id'=>'SelectStore1','data-name'=>'SelectStore']) !!}
    @else
    {!! Form::Select('tasks[1][SelectStore]', ($stores), null, ['class' => 'form-control task_field select-choosen','id'=>'SelectStore1','data-name'=>'SelectStore']) !!}
    @endif
</div>

@if(!empty($selected_asset_id))
<div class="form-group col-sm-4">
    {!! Form::label('SelectAsset', 'Selectasset:') !!}
    @if(!empty($selected_asset_id))
    {!! Form::Select('SelectAsset', ($assets), $selected_asset_id, ['class' => 'form-control task_field','data-id'=>'$selected_asset_id','id'=>'SelectAsset1','data-name'=>'SelectAsset']) !!}
    @else
    {!! Form::Select('tasks[1][SelectAsset]', ($assets), null, ['class' => 'form-control task_field','id'=>'SelectAsset1','data-name'=>'SelectAsset']) !!}
    @endif
</div>
@endif

<!-- Selectemployee Field -->
<div class="form-group col-sm-4 assets" id="asset1">
    {!! Form::label('SelectAsset', 'Selectasset:') !!}
    @if(!empty($selected_asset_id))
    {!! Form::Select('tasks[1][SelectAsset]', ($assets), $selected_asset_id, ['class' => 'form-control task_field','data-id'=>'$selected_asset_id','id'=>'SelectAsset1','data-name'=>'SelectAsset']) !!}
    @else
    {!! Form::Select('tasks[1][SelectAsset]', ($assets), null, ['class' => 'form-control task_field','id'=>'SelectAsset1','data-name'=>'SelectAsset']) !!}
    @endif
</div>
<!-- Startdate Field -->
<div class="form-group col-sm-4">
    {!! Form::label('StartDate', 'Startdate:') !!}
    @if(!empty($task->StartDate))
    {!! Form::date('StartDate', $task->StartDate, ['class' => 'form-control task_field','data-name'=>'StartDate','min'=>$task->StartDate,'required','onchange' => 'updateenddate(this)']) !!}
    @else
    {!! Form::date('tasks[1][StartDate]', null, ['class' => 'form-control task_field','data-name'=>'StartDate','id'=>'StartDate1','min'=>date("Y-m-d"),'required','onchange' => 'updateenddate(this)']) !!}
    @endif
</div>

<!-- Enddate Field -->
<div class="form-group col-sm-4">
    {!! Form::label('EndDate', 'Enddate:') !!}
    @if(!empty($task->EndDate))
    {!! Form::date('EndDate', $task->EndDate, ['class' => 'form-control task_field','data-name'=>'EndDate','min'=>$task->EndDate,'required','onchange' => 'updatestartdate(this)']) !!}
    @else
    {!! Form::date('tasks[1][EndDate]', null, ['class' => 'form-control task_field','data-name'=>'EndDate','id'=>'EndDate1','min'=>date("Y-m-d"),'required','onchange' => 'updatestartdate(this)']) !!}
    @endif
</div>

<!-- Undefined Field -->
<div class="form-group col-sm-12">
    {!! Form::label('undefined', 'Task Details:') !!}
    @if(!empty($description))
    {!! Form::textarea('TaskDetails', $description, ['class' => 'form-control task_field','data-name'=>'TaskDetails','rows'=>6,'cols'=>50]) !!}
    @else
    {!! Form::textarea('tasks[1][TaskDetails]', null, ['class' => 'form-control task_field','data-name'=>'TaskDetails','id'=>'TaskDetails1','rows'=>6,'cols'=>50]) !!}
    @endif
</div>
<div class="clearfix content-center"><br/>
    <a href="#" class="pull-right btn btn-danger remove" style="display: none;"><i class="icon remove"></i>Remove</a>
</div>
</div>

</div>
<!-- Submit Field -->
<div class="form-group col-sm-12 text-center"><br/>
    @if(empty($description))
    <a href="#" class="pull-right btn btn-danger add-another"><i class="icon add"></i>Add Another Task</a>
    @endif
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    @if(!empty($description))
    <a href="{!! route('admin.tasks.index') !!}" class="btn btn-default">Cancel</a>
    @endif
</div>
@else
<hr>
<h4 class="text-center">Task employee does not exist</h4>
<hr>
@endif
<script type="text/javascript" src="<?php echo e(asset('assets/js/frontend/jquery.min.js')); ?>"></script>
<script type="text/javascript">
    $('#store1').css('display','none');
    $('#asset1').css('display','none');
    function updatetaskfield(input) {
        var val=input.value;
        var text=input.name;
        var dataitem = text.slice(text.indexOf('[') +1,text.indexOf(']'));
        if(val=='Visit Shop')
        {
            var stores="#SelectStore"+dataitem;
            var assets="#SelectAsset"+dataitem;
            var storeid="#"+$(stores).parent().attr("id");
            var assetid="#"+$(assets).parent().attr("id");
            $(assetid).css('display','none');
            $(storeid).show();
            $(stores).prop('required',true);
            $(assets).prop('required',false);
        }
        else if(val=='Scan QR')
        {
            var stores="#SelectStore"+dataitem;
            var assets="#SelectAsset"+dataitem;
            var storeid="#"+$(stores).parent().attr("id");
            var assetid="#"+$(assets).parent().attr("id");
            $(assetid).show();
            $(storeid).hide(); 
            $(assets).prop('required',true);
            $(stores).prop('required',false);
        }
    }
    
    function updateenddate(input) {
        var text=input.name;
        var dataitem = text.slice(text.indexOf('[') +1,text.indexOf(']'));
        $('#EndDate'+dataitem).attr('min',input.value);
        $('#EndDate'+dataitem).val(input.value);
    }
    function updatestartdate(input) {
        var text=input.name;
        var dataitem = text.slice(text.indexOf('[') +1,text.indexOf(']'));
        $('#StartDate'+dataitem).attr('max',input.value);
    }
        $(document).ready(function ($) {
        var $taskwrapper = null;
        var currenttasknumber = 1;
        var today = new Date();
        var dd = today.getDate();
        var mm = today.getMonth() + 1;
        var yyyy = today.getFullYear();
        if (dd < 10) {
            dd = '0' + dd;
        }
        if (mm < 10) {
            mm = '0' + mm;
        }
        var today = yyyy + '-' + mm + '-' + dd;

        $(document).on('click', '.add-another', function (e) {
            e.preventDefault();
            currenttasknumber++;
            $taskwrapper = $('.taskwrapper:first').clone();
            $("#multipletasks").append($taskwrapper);
            setTimeout(function () {
                updateFields();
            }, 100);
        });
        $(document).on('click', '.remove', function (e) {
            e.preventDefault();
            var r = confirm("Are you sure to remove?");
            if (r == true) {
                $(this).closest('.taskwrapper').remove();
            }
        });
        $taskwrapper = $('.taskwrapper');
        function updateFields() {
            $taskwrapper.attr('data-item', currenttasknumber);
            $taskwrapper.find('.chosen-container').remove();
            // $taskwrapper.find('select').chosen('destroy').show().chosen();
            if (currenttasknumber != 1)
                $taskwrapper.find('.remove').show();
            $taskwrapper.find('.stores').each(function (index, element) {
                var name = $(element).attr('id');
                var id=name+currenttasknumber;
                $(element).attr('id', id);
                $(element).css('display','none');
            });
            $taskwrapper.find('.assets').each(function (index, element) {
                var name = $(element).attr('id');
                var id=name+currenttasknumber;
                $(element).attr('id', id);
                $(element).css('display','none');
            });
            $taskwrapper.find('.task_field').each(function (index, element) {
                var name = $(element).attr('data-name');
                var field_name = "tasks[" + currenttasknumber + "][" + name + "]";
                $(element).attr('name', field_name);
                var id=name+currenttasknumber;
                $(element).attr('id', id);
                $(element).val('');
                if(name=='StartDate' || name=='EndDate')
                {
                $('#'+id).attr('min',today);
                $('#'+id).attr('max','');
                }
            });

            $("select").next().animate({"width": "400px"});
        }
        
        });
</script>