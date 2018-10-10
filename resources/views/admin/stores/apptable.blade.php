<table class="table table-responsive" id="stores-table" width="100%">
    <thead>
     <tr>
        <th>Sno.</th>
        <th>Ba Name</th>
        <th>Name</th>
        <th>Contact Person</th>
        <th>Owner</th>
        <th>Contact</th>
	    <th>Latitude</th>
        <th>Longitude</th>
	    <th>Created </th> 
	    <th>Action</th>
     </tr>
    </thead>
    <tbody>
@php ($i=1)
    @foreach($stores as $stores)
        <tr>
            <td>{!! $i++ !!}</td>
	    <td> {!! $stores->ba !!}</td>
            <td>{!! $stores->shopname !!}</td>
	    <td>{!! $stores->contactperson !!}</td>
            <td>{!! $stores->owner !!}</td> 
            <td>{!! $stores->contactnumber !!}</td> 
	    <td>{!! $stores->lat !!}</td>
            <td>{!! $stores->lng !!}</td>
	    <td>{!! date('D M d, Y, g:i a',strtotime($stores->created_at)) !!}</td>

        
<!-- 	    <td>




{!! Form::select('Designation', [null => 'Select Action', 'Add Target' => 'Add Target','View Target'=>'View Target' ], null, ['class' => 'form-control', 'id'=>'target']) !!} </td> -->
            
	   <td>
                 <a href="{{ route('admin.stores.appedit', collect($stores)->first() ) }}">
                     <i class="livicon" data-name="edit" data-size="18" data-loop="true" data-c="#428BCA" data-hc="#428BCA" title="edit stores"></i>
                 </a>
                 <a href="{{ route('admin.stores.confirm-delete', collect($stores)->first() ) }}" data-toggle="modal" data-target="#delete_confirm">
                     <i class="livicon" data-name="remove-alt" data-size="18" data-loop="true" data-c="#f56954" data-hc="#f56954" title="delete stores"></i>
                 </a>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
@section('footer_scripts')

    <div class="modal fade" id="delete_confirm" tabindex="-1" role="dialog" aria-labelledby="user_delete_confirm_title" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            </div>
        </div>
    </div>
    {!! Form::open(['route' => 'admin.shops.appdaterange', 'id'=>'date_range_form']) !!}
    {!! Form::hidden('dateFrom', '', ['class' => 'form-control', 'id'=>'dateFrom']) !!}
    {!! Form::hidden('dateTo', '', ['class' => 'form-control', 'id'=>'dateTo']) !!}
    {!! Form::close() !!}
    <script>$(function () {$('body').on('hidden.bs.modal', '.modal', function () {$(this).removeData('bs.modal');});});</script>
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/buttons.bootstrap.css') }}"/>
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/dataTables.bootstrap.css') }}"/>
 <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/buttons.bootstrap.css') }}">
<script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/jquery.dataTables.js') }}" ></script>
 <script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/dataTables.bootstrap.js') }}" ></script>

    <script>
        
        $('#stores-table').DataTable({
                      responsive: true,
                      pageLength: 10
                  });
                  $('#stores-table').on( 'page.dt', function () {
                     setTimeout(function(){
                           $('.livicon').updateLivicon();
                     },500);
                  } );

        $('.target').change(function () {
            let val = $(this).val();
            let id = $(this).data("id");
            console.log(id);
            if(val=="Add Target"){
                window.location.href = "{{url('/admin/skutargets/create/')}}/"+id;

            }
            else if(val=="View Target"){
                window.location.href = "{{url('/admin/skutargets/')}}/"+id;                
            }
        });
        $('#stores-table_wrapper .row:first-child .col-sm-6:nth-child(1)').attr('class','col-sm-3');
        $('#stores-table_wrapper .row:first-child .col-sm-6:nth-child(2)').attr('class','col-sm-9');
        $('.input-sm').attr('placeholder','Search');
        $('#stores-table_filter').prepend("<label for='from'>From:</label><input class='form-control' name='from' type='date' id='from' value='<?php if(isset($from)) { echo $from; } else { echo '';} ?>'><label for='to'>To:</label><input class='form-control' name='to' type='date' id='to' value='<?php if(isset($to)) { echo $to; } else { echo '';} ?>'> <button type='btn' class='btn-primary' id='fitersearch'>Search</button>");
        $('#stores-table_filter label').css('margin-left', '1em');
        $('#fitersearch').on('click',function(){
            var from=$('#from').val();
            var to=$('#to').val();
            if((from!='' && to!='') || (from=='' && to==''))
            {
                var da=$('#dateFrom').val(from);
                $('#dateTo').val(to);
                $('#date_range_form').submit();
            }
        });

       </script>

@stop