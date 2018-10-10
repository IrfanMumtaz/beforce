@extends('admin/layouts/default')

@section('title')
Attendances
@parent
@stop

@section('header_styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/gmaps/css/examples.css') }}"/>
    <link href="{{ asset('assets/css/pages/googlemaps_custom.css') }}" rel="stylesheet">
@stop

{{-- Page content --}}
@section('content')
<section class="content-header">
    <h1>Daily Attendances Report</h1>
    Showing attendance report of <a href="#" class="btn btn-info">  {!! date('D M d, Y',strtotime(date('Y/m/d'))) !!}</a>

	
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('admin.dashboard') }}"> <i class="livicon" data-name="home" data-size="16" data-color="#000"></i>
                Dashboard
            </a>
        </li>
        <li>Attendances</li>
        <li class="active">Attendances List</li>
    </ol>
</section>

<section class="content paddingleft_right15">
    <div class="row">
     @include('flash::message')

        <div class="panel panel-primary ">
            <div class="panel-heading clearfix"></div>

{!! Form::open(['route' => 'admin.employees.store']) !!}

<div class="form-group col-sm-4"> 
    {!! Form::date('DOB', null, ['class' => 'form-control']) !!}
</div>

{{-- <div class="form-group col-sm-4">
    {!! Form::select('SelectBrand',[]  ,[null => '-- Select Brand--'], ['class' => 'form-control','id'=>'SelectBrand']) !!}
</div> --}}

<!-- ShopCity Field -->
<div class="form-group col-sm-4">
    {!! Form::select('ShopCity', [] , [null => '-- Select City --'], ['class' => 'form-control' , 'id'=> 'shopcity']) !!}
</div>

<div class="form-group col-sm-4">
    {!! Form::select('Designation', [null => '-- Designation--','supervisor' => 'Supervisor', 'manager' => 'Manager', 'merchandiser' => 'Merchandiser', 'damage_verification' => 'Damage_verification' , 'BA' => 'Brand Ambassador'], null, ['class' => 'form-control']) !!}
</div>

<!-- Department Field -->
<div class="form-group col-sm-4">
    {!! Form::select('Department', [null => '-- Designation--','Corporate' => 'Corporate', 'Field' => 'Field'], null, ['class' => 'form-control']) !!}
</div>

<div class="form-group col-sm-4 text-center">
    {!! Form::submit(' Filter ', ['class' => 'btn btn-primary']) !!}
</div>
        {!! Form::close() !!}

</div>

</div>

        <div class="panel panel-primary ">
            <div class="panel-heading clearfix">
                <h4 class="panel-title pull-left"> <i class="livicon" data-name="list-ul" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
                    Attendances List
                </h4>
          
      <div class="pull-right">
                </div>
	</div>

	            <br />

            <div class="panel-body table-responsive">
                 @include('admin.attendances.table')
                 
            </div>

                <!-- Basic charts strats here-->
                <div class="panel panel-warning">
                    <div class="panel-heading">
                        <h4 class="panel-title">Markers</h4>
                                <span class="pull-right">
                                    <i class="glyphicon glyphicon-chevron-up showhide clickable"></i>
                                    <i class="glyphicon glyphicon-remove removepanel clickable"></i>
                                </span>
                    </div>
                    <div class="panel-body" style="padding:10px !important;">
                        <div id="gmap-markers" class="gmap"></div>
                    </div>
                </div>
            </div>
        


        </div>


 </div>
</section>
@stop

{{-- page level scripts --}}

@section('footer_scripts')
    <script type="text/javascript" src="{{ asset('assets/js/pages/maps_api.js') }}"></script>
    <script type="text/javascript"
            src="http://maps.google.com/maps/api/js?key=AIzaSyADWjiTRjsycXf3Lo0ahdc7dDxcQb475qw&libraries=places"></script>
    <script type="text/javascript" src="{{ asset('assets/vendors/gmaps/js/gmaps.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/pages/custommaps.js') }}"></script>

@stop
