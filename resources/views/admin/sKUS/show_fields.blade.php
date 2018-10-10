<!-- Id Field -->
<!--<div class="form-group">-->
<!--    {!! Form::label('id', 'Id:') !!}-->
<!--    <p>{!! $sKU->id !!}</p>-->
<!--    <hr>-->
<!--</div>-->

<!-- Undefined Field -->
<!-- <div class="form-group">
    {!! Form::label('undefined', 'Undefined:') !!}
    <p>{!! $sKU->undefined !!}</p>
    <hr>
</div> -->

<!-- Skucaategory Field -->
<div class="form-group">
    {!! Form::label('SKUCaategory', 'Skucaategory:') !!}
    <p>{!! $sKU->SKUCaategory !!}</p>
    <hr>
</div>

<!-- Name Field -->
<div class="form-group">
    {!! Form::label('name', 'Name:') !!}
    <p>{!! $sKU->name !!}</p>
    <hr>
</div>

<!-- Price Field -->
<div class="form-group">
    {!! Form::label('Price', 'Price:') !!}
    <p>{!! $sKU->Price !!}</p>
    <hr>
</div>

<!-- Itempercarton Field -->
<div class="form-group">
    {!! Form::label('ItemPerCarton', 'Itempercarton:') !!}
    <p>{!! $sKU->ItemPerCarton !!}</p>
    <hr>
</div>

<!-- Skuimage Field -->
<div class="form-group">
    {!! Form::label('SKUImage', 'Skuimage:') !!}
    <p><img class="img-responsive" height="100" width="100"  src="{!! $sKU->SKUImage !!}"></p>
    <!-- <p>{!! $sKU->SKUImage !!}</p> -->
    <hr>
</div>

