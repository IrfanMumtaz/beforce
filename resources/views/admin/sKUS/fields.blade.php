

<!-- Skucaategory Field -->
<div class="form-group col-sm-4">
    {!! Form::label('CateId', 'Sku Category:*') !!}
    @if(!empty($selected_category))
    {!! Form::Select('CateId', ($cate), ['3'], ['class' => 'form-control','required']) !!}
    {!! $errors->first('CateId', '<p class="alert alert-danger">:message</p>') !!}
    @else
    {!! Form::Select('CateId', ($cate), null, ['class' => 'form-control','required']) !!}
    {!! $errors->first('CateId', '<p class="alert alert-danger">:message</p>') !!}
    @endif
</div>

<!-- Name Field -->
<div class="form-group col-sm-4">
    {!! Form::label('name', 'Name:*') !!}
    {!! Form::text('name', null, ['class' => 'form-control','required']) !!}
    {!! $errors->first('name', '<p class="alert alert-danger">:message</p>') !!}
</div>

<!-- Price Field -->
<div class="form-group col-sm-4">
    {!! Form::label('Price', 'Price:*') !!}
    {!! Form::text('Price', null, ['class' => 'form-control','required']) !!}
    {!! $errors->first('Price', '<p class="alert alert-danger">:message</p>') !!}
</div>

<!-- Itempercarton Field -->
<div class="form-group col-sm-4">
    {!! Form::label('ItemPerCarton', 'Itempercarton:*') !!}
    {!! Form::number('ItemPerCarton', null, ['class' => 'form-control']) !!}
    {!! $errors->first('ItemPerCarton', '<p class="alert alert-danger">:message</p>') !!}
</div>

<!-- Skuimage Field -->
<div class="form-group col-sm-4">
    {!! Form::label('SKUImage', 'Skuimage:') !!}
    {!! Form::file('SKUImage',['class' => 'form-control','onchange' => 'readURL(this)', 'id'=>'image']) !!}
    @if(!empty($sKU->SKUImage))
    <img id="blah" src="{!! $sKU->SKUImage !!}" width="100" height="100" alt="your image" name="blah" />
    @else
    <img id="blah" src="#" width="100" height="100" alt="your image" name="blah" style="display: none;" />
    @endif
</div>
<div class="clearfix"></div>

<!-- Submit Field -->
<div class="form-group col-sm-4 text-center">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('admin.sKUS.index') !!}" class="btn btn-default">Cancel</a>
</div>
<script>
        function readURL(input) {
            $('#blah').show();
            if (input.files && input.files[0]) {

                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#blah').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>