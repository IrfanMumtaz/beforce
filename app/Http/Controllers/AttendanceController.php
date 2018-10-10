<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Requests\CreateAttendanceRequest;
use App\Http\Requests\UpdateAttendanceRequest;
use App\Repositories\AttendanceRepository;
use App\Http\Controllers\AppBaseController as InfyOmBaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use App\Models\Attendance;
use Flash;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

class AttendanceController extends InfyOmBaseController
{
    /** @var  AttendanceRepository */
    private $attendanceRepository;

    public function __construct(AttendanceRepository $attendanceRepo)
    {
        $this->attendanceRepository = $attendanceRepo;
    }

    /**
     * Display a listing of the Attendance.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {

        $this->attendanceRepository->pushCriteria(new RequestCriteria($request));
        $attendances = $this->attendanceRepository->all();
        
	foreach($attendances as $att)
	{
	$empDesig= \DB::table('employees')->where('id', $att->empid)->pluck('Designation');
	$shopid= \DB::table('employees')->where('id', $att->empid)->pluck('Shop');
	$empCity= \DB::table('employees')->where('id', $att->empid)->pluck('ShopCity');

	}
	foreach($shopid as $sid)
	{
	        $empLoc= \DB::table('shops')->where('id', $sid)->pluck('name');
	}

	return view('admin.attendances.index')->with('attendances', $attendances)
	->with('empDesig',$empDesig)
	->with('empLoc',$empLoc)
	->with('empCity',$empCity);

    }
    

    /**
     * Show the form for creating a new Attendance.
     *
     * @return Response
     */
    public function create()
    {
        return view('admin.attendances.create');
    }

    /**
     * Store a newly created Attendance in storage.
     *
     * @param CreateAttendanceRequest $request
     *
     * @return Response
     */
    public function store(CreateAttendanceRequest $request)
    {
        $input = $request->all();

        $attendance = $this->attendanceRepository->create($input);

        Flash::success('Attendance saved successfully.');

        return redirect(route('admin.attendances.index'));
    }

    /**
     * Display the specified Attendance.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $attendance = $this->attendanceRepository->findWithoutFail($id);

        if (empty($attendance)) {
            Flash::error('Attendance not found');

            return redirect(route('attendances.index'));
        }

        return view('admin.attendances.show')->with('attendance', $attendance);
    }

    /**
     * Show the form for editing the specified Attendance.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $attendance = $this->attendanceRepository->findWithoutFail($id);

        if (empty($attendance)) {
            Flash::error('Attendance not found');

            return redirect(route('attendances.index'));
        }

        return view('admin.attendances.edit')->with('attendance', $attendance);
    }

    /**
     * Update the specified Attendance in storage.
     *
     * @param  int              $id
     * @param UpdateAttendanceRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateAttendanceRequest $request)
    {
        $attendance = $this->attendanceRepository->findWithoutFail($id);

        

        if (empty($attendance)) {
            Flash::error('Attendance not found');

            return redirect(route('attendances.index'));
        }

        $attendance = $this->attendanceRepository->update($request->all(), $id);

        Flash::success('Attendance updated successfully.');

        return redirect(route('admin.attendances.index'));
    }

    /**
     * Remove the specified Attendance from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
      public function getModalDelete($id = null)
      {
          $error = '';
          $model = '';
          $confirm_route =  route('admin.attendances.delete',['id'=>$id]);
          return View('admin.layouts/modal_confirmation', compact('error','model', 'confirm_route'));

      }

       public function getDelete($id = null)
       {
           $sample = Attendance::destroy($id);

           // Redirect to the group management page
           return redirect(route('admin.attendances.index'))->with('success', Lang::get('message.success.delete'));

       }

}
