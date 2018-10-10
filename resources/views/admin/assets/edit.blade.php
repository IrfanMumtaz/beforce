@extends('admin/layouts/default')

@section('title')
Assets
@parent
@stop
@section('content')
    <section class="content-header">
     <h1>Assets Edit</h1>
     <ol class="breadcrumb">
         <li>
             <a href="{{ route('admin.dashboard') }}"> <i class="livicon" data-name="home" data-size="16" data-color="#000"></i>
                 Dashboard
             </a>
         </li>
         <li>Assets</li>
         <li class="active">Edit Asset </li>
     </ol>
    </section>
    <section class="content paddingleft_right15">
      <div class="row">
          @include('flash::message')
      <div class="panel panel-primary">
            <div class="panel-heading">
                <h4 class="panel-title"> <i class="livicon" data-name="user" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i>
                    Edit  Asset
                </h4></div>
            <br />
        <div class="panel-body">
        {!! Form::model($asset, ['route' => ['admin.assets.update', collect($asset)->first() ], 'method' => 'patch','autocomplete'=>'off']) !!}

        @include('admin.assets.fields')

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