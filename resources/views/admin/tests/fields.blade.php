<!-- Undefined Field -->
<div class="form-group col-sm-12">
    {!! Form::label('undefined', 'Undefined:') !!}
    {!! Form::text('undefined', null, ['class' => 'form-control']) !!}
</div>

<!-- Test Field -->
<div class="form-group col-sm-12">
    {!! Form::label('test', 'Test:') !!}
    {!! Form::text('test', null, ['class' => 'form-control']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12 text-center">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('admin.tests.index') !!}" class="btn btn-default">Cancel</a>
</div>
