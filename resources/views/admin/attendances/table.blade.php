<table class="table table-responsive" id="attendances-table" width="100%">
    <thead>
     <tr>
        <th>Sno.</th>
        <th>Date</th>
        <th>Name</th>
        <th>Designation</th>
        <th>City</th>
        <th>Location</th>
        <th>Starttime</th>
        <th>Startimage</th>
        <th>Endtime</th>
        <th>Endimage</th>
     </tr>
    </thead>
    <tbody>
@php ($i=1)

@foreach($attendances as $attendance)
        <tr> 
            <td>{!! $i++ !!}</td>
	    <td>{!! date('D M d, Y',strtotime($attendance->created_at)) !!}</td>
            <td>{!! trim(\DB::table('employees')->where('id', $attendance->empid)->pluck('EmployeeName'),'[]"'); !!}</td>
            <td>{!! $empDesig[0] !!}</td>
            <td>{!! $empCity[0] !!}</td>
            <td>{!! $empLoc[0] !!}</td>
	    <td>{!! trim($attendance->startTime,'"'); !!}</td>
            <td><img src='{{ asset('uploadimages/'.$attendance->StartImage) }}' style="width:80px;height:40px;"></td>
            <td>{!! trim($attendance->endTime,'"'); !!}</td>
            <td><img src='{{ asset('uploadimages/'.$attendance->EndImage) }}' style="width:80px;height:40px;"></td>
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
    <script>$(function () {$('body').on('hidden.bs.modal', '.modal', function () {$(this).removeData('bs.modal');});});</script>
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/buttons.bootstrap.css') }}"/>
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/dataTables.bootstrap.css') }}"/>
 <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/datatables/css/buttons.bootstrap.css') }}">
<script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/jquery.dataTables.js') }}" ></script>
 <script type="text/javascript" src="{{ asset('assets/vendors/datatables/js/dataTables.bootstrap.js') }}" ></script>

    <script>
        $('#attendances-table').DataTable({
                      responsive: true,
                      pageLength: 10
                  });
                  $('#attendances-table').on( 'page.dt', function () {
                     setTimeout(function(){
                           $('.livicon').updateLivicon();
                     },500);
                  } );

       </script>

@stop