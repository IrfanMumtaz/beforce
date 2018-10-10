<!-- Id Field -->
<!--<div class="form-group">-->
<!--    {!! Form::label('id', 'Id:') !!}-->
<!--    <p>{!! $task->id !!}</p>-->
<!--    <hr>-->
<!--</div>-->

<!-- Selectemployee Field -->
<div class="form-group">
    {!! Form::label('SelectEmployee', 'Selectemployee:') !!}
    <p>{!! $task->SelectEmployee !!}</p>
    <hr>
</div>

<!-- Tasktype Field -->
<div class="form-group">
    {!! Form::label('Tasktype', 'Tasktype:') !!}
    <p>{!! $task->Tasktype !!}</p>
    <hr>
</div>

<!-- Selectstore Field -->
<div class="form-group">
    {!! Form::label('SelectStore', 'Selectstore:') !!}
    <p>{!! $task->SelectStore !!}</p>
    <hr>
</div>

@if(!empty($task->AssetName))
<!-- Selectstore Field -->
<div class="form-group">
    {!! Form::label('SelectAsset', 'AssetName:') !!}
    <p>{!! $task->AssetName !!}</p>
    <hr>
</div>
@endif

@if(!empty($task->QRCode))
<div class="form-group">
    {!! Form::label('QRCode', 'QRCode:') !!}
    <p><img class="img-responsive" height="100" width="100"  src="{!! $task->QRCode !!}"></p>
    <hr>
</div>
@endif
<!-- Startdate Field -->
<div class="form-group">
    {!! Form::label('StartDate', 'Startdate:') !!}
    <p>{!! $task->StartDate !!}</p>
    <hr>
</div>

<!-- Enddate Field -->
<div class="form-group">
    {!! Form::label('EndDate', 'Enddate:') !!}
    <p>{!! $task->EndDate !!}</p>
    <hr>
</div>

