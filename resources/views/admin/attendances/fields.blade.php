<!-- Undefined Field -->
<div class="form-group col-sm-12">
    {!! Form::label('undefined', 'Undefined:') !!}
    {!! Form::text('undefined', null, ['class' => 'form-control']) !!}
</div>

<!-- Date Field -->
<div class="form-group col-sm-12">
    {!! Form::label('date', 'Date:') !!}
    {!! Form::text('date', null, ['class' => 'form-control']) !!}
</div>

<!-- Empid Field -->
<div class="form-group col-sm-12">
    {!! Form::label('empid', 'Empid:') !!}
    {!! Form::text('empid', null, ['class' => 'form-control']) !!}
</div>

<!-- Starttime Field -->
<div class="form-group col-sm-12">
    {!! Form::label('startTime', 'Starttime:') !!}
    {!! Form::text('startTime', null, ['class' => 'form-control']) !!}
</div>

<!-- Startimage Field -->
<div class="form-group col-sm-12">
    {!! Form::label('StartImage', 'Startimage:') !!}
    {!! Form::text('StartImage', null, ['class' => 'form-control']) !!}
</div>

<!-- Endtime Field -->
<div class="form-group col-sm-12">
    {!! Form::label('EndTime', 'Endtime:') !!}
    {!! Form::text('EndTime', null, ['class' => 'form-control']) !!}
</div>

<!-- Endimage Field -->
<div class="form-group col-sm-12">
    {!! Form::label('EndImage', 'Endimage:') !!}
    {!! Form::text('EndImage', null, ['class' => 'form-control']) !!}
</div>

<!-- Break Field -->
<div class="form-group col-sm-12">
    {!! Form::label('break', 'Break:') !!}
    {!! Form::text('break', null, ['class' => 'form-control']) !!}
</div>

<!-- Namaz Field -->
<div class="form-group col-sm-12">
    {!! Form::label('namaz', 'Namaz:') !!}
    {!! Form::text('namaz', null, ['class' => 'form-control']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12 text-center">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('admin.attendances.index') !!}" class="btn btn-default">Cancel</a>
</div>
