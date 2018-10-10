@extends('admin/layouts/default')

@section('title')
SKUS
@parent
@stop
@section('content')
    <section class="content-header">
     <h1>SKUS Edit</h1>
     <ol class="breadcrumb">
         <li>
             <a href="{{ route('admin.dashboard') }}"> <i class="livicon" data-name="home" data-size="16" data-color="#000"></i>
                 Dashboard
             </a>
         </li>
         <li>SKUS</li>
         <li class="active">Edit SKU </li>
     </ol>
    </section>
    <section class="content paddingleft_right15">
      <div class="row">
          @include('flash::message')
      <div class="panel panel-primary">
            <div class="panel-heading">
                <h4 class="panel-title"> <i class="livicon" data-name="user" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
                    Edit  SKU
                </h4></div>
            <br />
        <div class="panel-body">
        {!! Form::model($sKU, ['route' => ['admin.sKUS.update', collect($sKU)->first() ], 'method' => 'patch','files'=>true,'autocomplete'=>'off']) !!}

        @include('admin.sKUS.fields')

        {!! Form::close() !!}
        </div>
      </div>
    </div>
   </section>
 @stop
@section('footer_scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            $("form").submit(function() {
                $('input[type=submit]').attr('disabled', 'disabled');
                return true;
            });
        });
         
    </script>
@stop