<table class="table table-responsive" id="tasks-table" width="100%">
    <thead>
     <tr>
        <th>Sno.</th>
        <th>Employee</th>
        <th>Assign By</th>
        <th>Tasktype</th>
        <th>Shop</th>
        <th>Task Status</th>
        <th>Startdate</th>
        <th>Enddate</th>
        <th>Created At</th>
        <th >Action</th>
     </tr>
    </thead>
    <tbody>
    @php ($i=1)
    @foreach($tasks as $task)
        <tr>
            <td>{!! $i++ !!}</td>
            <td>{!! $task->SelectEmployee !!}</td>
            <td>{!! $task->assign_by !!}</td>
            <td>{!! $task->Tasktype !!}</td>
            <td>{!! $task->SelectStore !!}</td>
            <td>{!! $task->Status !!}</td>
            <td>{!! $task->StartDate !!}</td>
            <td>{!! $task->EndDate !!}</td>
            <td>{!! $task->created_at !!}</td>
            <td>
                 <a href="{{ route('admin.tasks.show', collect($task)->first() ) }}">
                     <i class="livicon" data-name="info" data-size="18" data-loop="true" data-c="#428BCA" data-hc="#428BCA" title="view task"></i>
                 </a>
                 <a href="{{ route('admin.tasks.edit', collect($task)->first() ) }}">
                     <i class="livicon" data-name="edit" data-size="18" data-loop="true" data-c="#428BCA" data-hc="#428BCA" title="edit task"></i>
                 </a>
                 <a href="{{ route('admin.tasks.confirm-delete', collect($task)->first() ) }}" data-toggle="modal" data-target="#delete_confirm">
                     <i class="livicon" data-name="remove-alt" data-size="18" data-loop="true" data-c="#f56954" data-hc="#f56954" title="delete task"></i>
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
    {!! Form::open(['route' => 'admin.tasks.daterange', 'id'=>'date_range_form']) !!}
    {!! Form::hidden('dateFrom', '', ['class' => 'form-control', 'id'=>'dateFrom']) !!}
    {!! Form::hidden('dateTo', '', ['class' => 'form-control', 'id'=>'dateTo']) !!}
    {!! Form::close() !!}
    {!! Form::open(['route' => 'admin.tasks.user', 'id'=>'tasks_user_form', 'method'=>'POST ']) !!}
    {!! Form::hidden('userid', '', ['class' => 'form-control', 'id'=>'userid']) !!}
    {!! Form::close() !!}
    <script>$(function () {$('body').on('hidden.bs.modal', '.modal', function () {$(this).removeData('bs.modal');});});</script>
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/buttons.bootstrap.css') }}"/>
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/dataTables.bootstrap.css') }}"/>
 <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/buttons.bootstrap.css') }}">
<script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/jquery.dataTables.js') }}" ></script>
 <script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/dataTables.bootstrap.js') }}" ></script>

    <script>
        $('#tasks-table').DataTable({
                      responsive: true,
                      pageLength: 10
                  });
                  $('#tasks-table').on( 'page.dt', function () {
                     setTimeout(function(){
                           $('.livicon').updateLivicon();
                     },500);
                  } );
                  $('#tasks-table_wrapper .row:first-child .col-sm-6:nth-child(1)').attr('class','col-sm-3');
        $('#tasks-table_wrapper .row:first-child .col-sm-6:nth-child(2)').attr('class','col-sm-9');
        $('.input-sm').attr('placeholder','Search');
        $('#tasks-table_filter').prepend("<label for='from'>From:</label><input class='form-control' name='from' type='date' id='from' value='<?php if(isset($from)) { echo $from; } else { echo '';} ?>'><label for='to'>To:</label><input class='form-control' name='to' type='date' id='to' value='<?php if(isset($to)) { echo $to; } else { echo '';} ?>'> <button type='btn' class='btn-primary' id='fitersearch'>Search</button>");
        $('#tasks-table_filter label').css('margin-left', '1em');
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