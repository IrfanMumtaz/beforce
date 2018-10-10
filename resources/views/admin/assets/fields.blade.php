<!-- Undefined Field -->


<!-- Assetname Field -->
<div class="form-group col-sm-3">
    {!! Form::label('AssetName', 'Assetname:*') !!}
    {!! Form::text('AssetName', null, ['class' => 'form-control','required']) !!}
    {!! $errors->first('AssetName', '<p class="alert alert-danger">:message</p>') !!}

</div>

<!-- Assettype Field -->
<div class="form-group col-sm-3">
    {!! Form::label('AssetType', 'Assettype:*') !!}
    @if(!empty($asset_type))
    {!! Form::select('AssetType', [ null => 'SELECT ASSET TYPE','CHECK-IN/CHECL-OUT' => 'CHECK-IN/CHECL-OUT' ,'INSPECTION'=>'INSPECTION','TIME & ATTENDEANCE'=> 'TIME & ATTENDEANCE','SERVICE'=>'SERVICE','HEALTHCARE'=> 'HEALTHCARE'], $asset_type , ['class' => 'form-control','required']) !!}
    {!! $errors->first('AssetType', '<p class="alert alert-danger">:message</p>') !!}
    @else
    {!! Form::select('AssetType', [ null => 'SELECT ASSET TYPE','CHECK-IN/CHECL-OUT' => 'CHECK-IN/CHECL-OUT' ,'INSPECTION'=>'INSPECTION','TIME & ATTENDEANCE'=> 'TIME & ATTENDEANCE','SERVICE'=>'SERVICE','HEALTHCARE'=> 'HEALTHCARE'], null , ['class' => 'form-control','required']) !!}
    {!! $errors->first('AssetType', '<p class="alert alert-danger">:message</p>') !!}
    @endif
</div>

<!-- Selectshop Field -->
<div class="form-group col-sm-3">
    {!! Form::label('SelectShop', 'Selectshop:*') !!}
    @if(!empty($shop_id))
    {!! Form::Select('SelectShop', $shops, $shop_id, ['class' => 'form-control','required']) !!}
    {!! $errors->first('SelectShop', '<p class="alert alert-danger">:message</p>') !!}
    @else
    {!! Form::Select('SelectShop', $shops, null, ['class' => 'form-control','required']) !!}
    {!! $errors->first('SelectShop', '<p class="alert alert-danger">:message</p>') !!}
	@endif
</div>

<!-- Description Field -->
<div class="form-group col-sm-12">
    {!! Form::label('Description', 'Description:*') !!}
    {!! Form::textarea('Description', null, ['class' => 'form-control','required']) !!}
    {!! $errors->first('Description', '<p class="alert alert-danger">:message</p>') !!}
</div>


<!-- Submit Field -->
<div class="form-group col-sm-3 text-center">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('admin.assets.index') !!}" class="btn btn-default">Cancel</a>
</div>
