<table class="table table-responsive" id="interceptions-table" width="100%">
    <thead>
     <tr>
        <th>Sno.</th>
        <th>BA</th>
	      <th>Interceptions</th>
        <th>Date</th>
     </tr>
    </thead>
    <tbody>
@php ($i=1)
    @foreach($interceptions as $interceptions)
        <tr>
            <td>{!! $i++ !!}</td>
	        <td>{!! $interceptions->BA !!}</td>    
            <td>{!! $interceptions->noofinterception !!}</td>
            <td>{!! date('D M d, Y, g:i a',strtotime($interceptions->created_at)) !!}</td>
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
    {!! Form::open(['route' => 'admin.interceptions.daterange', 'id'=>'date_range_form']) !!}
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
        $('#interceptions-table').DataTable({
                      responsive: true,
                      pageLength: 10
                  });
                  $('#interceptions-table').on( 'page.dt', function () {
                     setTimeout(function(){
                           $('.livicon').updateLivicon();
                     },500);
                  } );

        $('#interceptions-table_wrapper .row:first-child .col-sm-6:nth-child(1)').attr('class','col-sm-3');
        $('#interceptions-table_wrapper .row:first-child .col-sm-6:nth-child(2)').attr('class','col-sm-9');
        $('.input-sm').attr('placeholder','Search');
        $('#interceptions-table_filter').prepend("<label for='from'>From:</label><input class='form-control' name='from' type='date' id='from' value='<?php if(isset($from)) { echo $from; } else { echo '';} ?>'><label for='to'>To:</label><input class='form-control' name='to' type='date' id='to' value='<?php if(isset($to)) { echo $to; } else { echo '';} ?>'>");
        $('#interceptions-table_filter label').css('margin-left', '1em');
        $('#from').on('change',function(){
            var from=$(this).val();
            var to=$('#to').val();
            if(to!='')
            {
                var da=$('#dateFrom').val(from);
                $('#dateTo').val(to);
                $('#date_range_form').submit();
            }
        });
        $('#to').on('change',function(){
            var to=$(this).val();
            var from=$('#from').val();
            if(from!='')
            {
                $('#dateFrom').val(from);
                $('#dateTo').val(to);
                $('#date_range_form').submit();
            }
        }); 

       </script>

@stop