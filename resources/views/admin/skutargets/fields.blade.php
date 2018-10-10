
	<div class="form-group col-sm-2"> {!! '<b>Sno.</b>' !!}</div>
	<div class="form-group col-sm-3"> {!! '<b>Category</b>' !!}</div>
        <div class="form-group col-sm-3">{!! '<b>Name</b>' !!}</div>
        <div class="form-group col-sm-4">{!! '<b>Target</b>' !!}</div>
        
@php ($i=1)
@if(count($skus)<=0)
<div class="container">
    <hr>
    <hr>
    <h4 class="text-center">No sku or category found</h4>
    <hr>
</div>
@else                
 @foreach($skus as $key => $skus)


<div class="form-group col-sm-2">{!! $i++ !!}</div>
<div class="form-group col-sm-3">{!! Form::label('brand', $skus->Category) !!}</div>
<div class="form-group col-sm-3"> 

{!! Form::label('SKUITEMS', $skus->name) !!}
{{-- <input type="hidden" value="" name[]={{$skus}}> --}}
</div>
	<div class="form-group col-sm-4">
        {!! Form::hidden( 'name['.$key.']',$skus , ['class' => 'form-control']) !!}
        {!! Form::hidden( 'shopid['.$key.']',$storeid , ['class' => 'form-control']) !!}
        {!! Form::hidden('skuids['.$key.']',$skuids[$key], ['class' => 'form-control']) !!}        {!! Form::hidden('skuids['.$key.']',$skuids[$key], ['class' => 'form-control']) !!}        {!! Form::hidden('skuids['.$key.']',$skuids[$key], ['class' => 'form-control']) !!}        {!! Form::hidden('skuids['.$key.']',$skuids[$key], ['class' => 'form-control']) !!}
        {!! Form::number('skutargets['.$key.']', ' ', ['class' => 'form-control','min' => 0, 'tabindex' => 1]) !!}
   
</div>   
<div style="clear: both;"></div>
@endforeach

<!-- Submit Field -->
<div class="form-group col-sm-12 text-center">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('admin.stores.index') !!}" class="btn btn-default">Cancel</a>
</div>
@endif
