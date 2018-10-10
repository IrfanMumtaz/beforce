

<!-- Name Field -->
<div class="form-group col-sm-4">
    {!! Form::label('name', 'Name:*') !!}
    {!! Form::text('name', null, ['class' => 'form-control','required']) !!}
    {!! $errors->first('name', '<p class="alert alert-danger">:message</p>') !!}
</div>

<!-- Ownername Field -->
<div class="form-group col-sm-4">
    {!! Form::label('Ownername', 'Owner Name:') !!}
    {!! Form::text('Ownername', null, ['class' => 'form-control']) !!}
    {!! $errors->first('Ownername', '<p class="alert alert-danger">:message</p>') !!}
</div>

<!-- Contactperson Field -->
<div class="form-group col-sm-4">
    {!! Form::label('Contactperson', 'Contact Person:') !!}
    {!! Form::text('Contactperson', null, ['class' => 'form-control']) !!}
    {!! $errors->first('Contactperson', '<p class="alert alert-danger">:message</p>') !!}
</div>

<!-- Contactnumber Field -->
<div class="form-group col-sm-4">
    {!! Form::label('Contactnumber', 'Contact Number:') !!}
    {!! Form::text('Contactnumber', null, ['class' => 'form-control','required']) !!}
    {!! $errors->first('Contactnumber', '<p class="alert alert-danger">:message</p>') !!}
</div>

<!-- Latitude Field -->
<div class="form-group col-sm-4">
    {!! Form::label('latitude', 'Latitude:*') !!}
    {!! Form::number('latitude', null, ['class' => 'form-control','step' => 'any','required' ]) !!}
    {!! $errors->first('latitude', '<p class="alert alert-danger">:message</p>') !!}
</div>

<!-- Longitude Field -->
<div class="form-group col-sm-4">
    {!! Form::label('longitude', 'Longitude:*') !!}
    {!! Form::number('longitude', null, ['class' => 'form-control','step' => 'any','required']) !!}
    {!! $errors->first('longitude', '<p class="alert alert-danger">:message</p>') !!}
</div>

<!-- Storesize Field -->
<div class="form-group col-sm-4">
    {!! Form::label('Storesize', 'Store Size:*') !!}
    @if( !empty($selected_size))
    {!! Form::Select('Storesize',[null => '-- Select Store Size--','Departmental' => 'Departmental', 'Large' => 'Large', 'Medium' => 'Medium', 'Small' => 'Small' ] , $selected_size,['class' => 'form-control','required']) !!}
    @else
    {!! Form::Select('Storesize',[null => '-- Select Store Size--','Departmental' => 'Departmental', 'Large' => 'Large', 'Medium' => 'Medium', 'Small' => 'Small' ] , null,['class' => 'form-control','required']) !!}
    {!! $errors->first('Storesize', '<p class="alert alert-danger">:message</p>') !!}
    @endif
</div>

<!-- Region Field -->
<div class="form-group col-sm-4">
    {!! Form::label('Region', 'Region:*') !!}
    @if( !empty($selected_brand))
    {!! Form::Select('Region', ($brands), $selected_brand, ['class' => 'form-control','id' => 'region','required']) !!}
    @else
    {!! Form::Select('Region', ($brands), null, ['class' => 'form-control','id' => 'region','required']) !!}
    {!! $errors->first('Region', '<p class="alert alert-danger">:message</p>') !!}
    @endif

</div>

<!-- Storecity Field -->
<div class="form-group col-sm-4">
    {!! Form::label('Storecity', 'Storecity:*') !!}
    @if( !empty($select_City))
    {!! Form::select('Storecity', ($cities), $select_City, ['class' => 'form-control Storecity','id' => 'city','required']) !!}
    @else
    {!! Form::select('Storecity', ($cities), null, ['class' => 'form-control Storecity','id' => 'city','required']) !!}
    {!! $errors->first('Storecity', '<p class="alert alert-danger">:message</p>') !!}
    @endif
</div>

<!-- Submit Field -->
<div class="form-group col-sm-4 text-center">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('admin.stores.index') !!}" class="btn btn-default">Cancel</a>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script>
    $('#region').on('change',function () {
            $('#city').html('<option value="">Loading..</option>');
            let val = $(this).val();
            $.ajax({
            url: "{{url('/admin/brands/cities/')}}/"+val,
            type: 'GET',
            success: function(res) {
                $('#city').html(res);
            }
            });
        });
</script>