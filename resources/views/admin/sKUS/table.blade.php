
<table class="table table-responsive" id="sKUS-table" width="100%">
    <thead>
     <tr>
        <th>Sno.</th>
        <th>Skucaategory</th>
        <th>Name</th>
        <th>Price</th>
        <th>Itempercarton</th>
        <th>Skuimage</th>
        <th>Created</th>
        <th >Action</th>
     </tr>
    </thead>
    <tbody>
    @php ($i=1)
    @foreach($sKUS as $sKU)
        <tr>
            <td>{!! $i++ !!}</td>
            <td>{!! $sKU->SKUCaategory !!}</td>
            <td>{!! $sKU->name !!}</td>
            <td>{!! $sKU->Price !!}</td>
            <td>{!! $sKU->ItemPerCarton !!}</td>
            <td><img  class="myImg" height="50" width="50"  src="{!! $sKU->SKUImage !!}"></td>
            <td>{!! date('D M d, Y, g:i a',strtotime($sKU->created_at)) !!}</td>
            <td>
                 <a href="{{ route('admin.sKUS.show', collect($sKU)->first() ) }}">
                     <i class="livicon" data-name="info" data-size="18" data-loop="true" data-c="#428BCA" data-hc="#428BCA" title="view sKU"></i>
                 </a>
                 <a href="{{ route('admin.sKUS.edit', collect($sKU)->first() ) }}">
                     <i class="livicon" data-name="edit" data-size="18" data-loop="true" data-c="#428BCA" data-hc="#428BCA" title="edit sKU"></i>
                 </a>
                 <a href="{{ route('admin.sKUS.confirm-delete', collect($sKU)->first() ) }}" data-toggle="modal" data-target="#delete_confirm">
                     <i class="livicon" data-name="remove-alt" data-size="18" data-loop="true" data-c="#f56954" data-hc="#f56954" title="delete sKU"></i>
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
    <!-- The Modal -->
    <div id="ZoomImageModal" class="ZoomImageModal modal">
      <span class="ZoomImageModal-close close">&times;</span>
      <img class="ZoomImageModal-modal-content modal-content"  id="img01">
      <div id="ZoomImageModal-caption"></div>
    </div>
    {!! Form::open(['route' => 'admin.sKUS.daterange', 'id'=>'date_range_form']) !!}
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
        $('#sKUS-table').DataTable({
                      responsive: true,
                      pageLength: 10
                  });
                  $('#sKUS-table').on( 'page.dt', function () {
                     setTimeout(function(){
                           $('.livicon').updateLivicon();
                     },500);
                  } );
        $('#sKUS-table_wrapper .row:first-child .col-sm-6:nth-child(1)').attr('class','col-sm-3');
        $('#sKUS-table_wrapper .row:first-child .col-sm-6:nth-child(2)').attr('class','col-sm-9');
        $('.input-sm').attr('placeholder','Search');
        $('#sKUS-table_filter').prepend("<label for='from'>From:</label><input class='form-control' name='from' type='date' id='from' value='<?php if(isset($from)) { echo $from; } else { echo '';} ?>'><label for='to'>To:</label><input class='form-control' name='to' type='date' id='to' value='<?php if(isset($to)) { echo $to; } else { echo '';} ?>'> <button type='btn' class='btn-primary' id='fitersearch'>Search</button>");
        $('#sKUS-table_filter label').css('margin-left', '1em');
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