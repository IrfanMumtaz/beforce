<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\CreateTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Repositories\TaskRepository;
use App\Http\Controllers\AppBaseController as InfyOmBaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Flash;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;
use App\Employee;
use App\Shop;
use App\Task;
use App\Task_shop;
use App\city;
use App\Asset;
use URL;
use App\Brand;
use Sentinel;
class TaskController extends InfyOmBaseController
{
    /** @var  TaskRepository */
    private $taskRepository;

    public function __construct(TaskRepository $taskRepo)
    {
    }

    /**
     * Display a listing of the Task.
     *
     * @param Request $request
     * @return Response
     */
    public function index()
    {
        $tasks = Task::all();
        $shop_id='';
        $shop_name='';
        foreach ($tasks as $key => $value) {
            $employee=Employee::find($value->emp_id);
            if($employee)
            {
            $tasks[$key]->SelectEmployee.=$employee->EmployeeName;
            }
            if($tasks[$key]->Tasktype=='Visit Shop'){
            $task_shop=Task_shop::where('task_id',$tasks[$key]->id)->get();
            foreach ($task_shop as $key1 => $value1) {
                $shop_id=$value1->shop_id;
                $shop=Shop::find($shop_id);
                if(isset($shop->name)) {
                $tasks[$key]->SelectStore=$shop->name;
                }
            }
            }
            else if($tasks[$key]->Tasktype=='Scan QR')
            {
                $asset=Asset::find($tasks[$key]->asset_id);
                if(!empty($asset->shop_id)) {
                $shop=Shop::find($asset->shop_id);
                $tasks[$key]->SelectStore=$shop->name;
                }
            }
            if($value->Status==0) {
            $tasks[$key]->Status='Pending';
            }
            else if($value->Status==1) {
            $tasks[$key]->Status='Completed';
            }
            
        }
        return view('admin.tasks.index')
            ->with('tasks', $tasks);
    }

    /**
     * Show the form for creating a new Task.
     *
     * @return Response
     */
    public function create($employeeId)
    {
    $emp=Employee::find($employeeId);
    $city=$emp->ShopCity;
    $city_result=city::where('id',$city)->get();
    $city_id='';
    foreach ($city_result as $key => $value) {
        $city_id=$value->id;
    }
    $shops_result=Shop::where([['city_id',$city_id],['brand_id',$emp->SelectBrand]])->get();
    $shops[null]='--Select Shop--';       
    $assets[null]='--Select Asset--';       
    foreach ($shops_result as $key => $value) {
        $shops[$value->id]=$value->name;
        $asset=Asset::where('shop_id',$value->id)->get();
        foreach ($asset as $key1 => $value1) {
            $assets[$value1->id]=$value1->AssetName;
        }
    }
    $tasks = Task::where('emp_id',$employeeId)->get();
        $shop_id='';
        $shop_name='';
        foreach ($tasks as $key => $value) {
            $employee=Employee::find($value->emp_id);
            $tasks[$key]->SelectEmployee.=$employee->EmployeeName;
            if($tasks[$key]->Tasktype=='Visit Shop'){
            $task_shop=Task_shop::where('task_id',$tasks[$key]->id)->get();
            foreach ($task_shop as $key1 => $value1) {
                $shop_id=$value1->shop_id;
                $shop=Shop::find($shop_id);
                if(isset($shop->name)) {
                $tasks[$key]->SelectStore=$shop->name; 
                    
                }
            }
            }
            else if($tasks[$key]->Tasktype=='Scan QR')
            {
                $asset=Asset::find($tasks[$key]->asset_id);
                $tasks[$key]->AssetName=$asset->AssetName;
                if(!empty($asset->shop_id)) {
                $shop=Shop::find($asset->shop_id);
                $tasks[$key]->SelectStore=$shop->name;
                }
            }
        }
	return view('admin.tasks.create')->with('employeeId',$employeeId)->with('assets', $assets)->with('stores', $shops)->with('tasks', $tasks);

    }

    /**
     * Store a newly created Task in storage.
     *
     * @param CreateTaskRequest $request
     *
     * @return Response
     */
    public function store(Request $request)
    
    {
        $firstname=Sentinel::getUser()->first_name;
        $lastname=Sentinel::getUser()->last_name;
        $fullname=$firstname.' '.$lastname;
        for($i=1; $i<=count($request->tasks); $i++) {
        if(!empty($request->tasks[$i]['SelectAsset'])){
        $input['asset_id']=$request->tasks[$i]['SelectAsset'];
        }
        $input['emp_id']=$request->EmployeeId;
        $input['assign_by']=$fullname;
        $input['Tasktype']=$request->tasks[$i]['Tasktype'];
        $input['StartDate']=$request->tasks[$i]['StartDate'];
        $input['EndDate']=$request->tasks[$i]['EndDate'];
        if(!empty($request->tasks[$i]['TaskDetails'])) {
        $input['description']=$request->tasks[$i]['TaskDetails'];
        }
        else {
        $input['description']='';
        }
        $task=Task::create($input);
        if(!empty($request->tasks[$i]['SelectStore'])){
        $data['task_id']=$task->id;
        $data['shop_id']=$request->tasks[$i]['SelectStore'];
        $task_shop=Task_shop::create($data);
        }
        }
        Flash::success('Task saved successfully.');
        $url=URL::to('/admin/tasks/create/').'/'.$request->EmployeeId;
        return redirect($url);
    }
    public function ajaxstore($data,$id)
    
    {
        $newdata=json_decode($data);
        $firstname=Sentinel::getUser()->first_name;
        $lastname=Sentinel::getUser()->last_name;
        $fullname=$firstname.' '.$lastname;
        for($i=0; $i<count($newdata); $i++) {
        if(!empty($newdata[$i]->asset)){
        $input['asset_id']=$newdata[$i]->asset;
        }
        $input['emp_id']=$id;
        $input['assign_by']=$fullname;
        $input['Tasktype']=$newdata[$i]->tasktype;
        $input['StartDate']=$newdata[$i]->startdate;
        $input['EndDate']=$newdata[$i]->enddate;
        $input['description']=$newdata[$i]->description;        
        $task=Task::create($input);
        if(!empty($newdata[$i]->shop)) {
        $shopdata['task_id']=$task->id;
        $shopdata['shop_id']=$newdata[$i]->shop;
        $task_shop=Task_shop::create($shopdata);
        }
        }
        // return 'Task Addedd successfully';
        Flash::success('Task saved successfully.');
        $url=URL::to('/admin/tasks/create/').'/'.$id;
        redirect($url);
    }

    /**
     * Display the specified Task.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $task = Task::find($id);
        $emp=Employee::find($task->emp_id);
        if(isset($emp->EmployeeName)) {
        $task->SelectEmployee.=$emp->EmployeeName;
        }
        if($task->Tasktype=='Visit Shop')
        {
        $asset_type=Task_shop::where('task_id',$task->id)->get();
        foreach ($asset_type as $asset_type_key => $asset_type_value) {
            $selected_shop_id=$asset_type_value->shop_id;
            $shop=Shop::find($selected_shop_id);
            $task->SelectStore.=$shop->name;
        }
        }
        else if($task->Tasktype=='Scan QR')
        {
        $selected_asset_id=$task->asset_id;
        $asset=Asset::find($selected_asset_id);
        $task->AssetName.=$asset->AssetName;
        $task->QRCode.=URL::to('/storage/QrImage/').'/'.$asset->QRCode;
        $shop=Shop::find($asset->shop_id);
        $task->SelectStore.=$shop->name;
        }

        if (empty($task)) {
            Flash::error('Task not found');

            return redirect(route('admin.tasks.index'));
        }


        return view('admin.tasks.show')->with('task', $task);
    }

    /**
     * Show the form for editing the specified Task.
     *
     * @paramre  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $task = Task::find($id);
        if (empty($task)) {
            Flash::error('Task not found');
            return redirect(route('admin.tasks.index'));
        }
        $emp_status='';
        $emp=Employee::find($task->emp_id);
        if(!empty($emp)) {
        $city=$emp->ShopCity;
        $city_result=city::where('id',$city)->get();
        $city_id='';
        foreach ($city_result as $key => $value) {
            $city_id=$value->id;
        }
        $shops_result=Shop::where('city_id',$city_id)->get();
        $shops[]='--Select Shop--';       
        $assets[]='--Select Asset--';       
        foreach ($shops_result as $key => $value) {
            $shops[$value->id]=$value->name;
            $asset=Asset::where('shop_id',$value->id)->get();
            foreach ($asset as $key1 => $value1) {
                $assets[$value1->id]=$value1->AssetName;
            }
        }
        $selected_asset_id='';
        $selected_shop_id='';
        if($task->Tasktype=='Visit Shop')
        {
        $asset_type=Task_shop::where('task_id',$id)->get();
        foreach ($asset_type as $asset_type_key => $asset_type_value) {
            $selected_shop_id=$asset_type_value->shop_id;
        }
        }
        else if($task->Tasktype=='Scan QR')
        {
        $selected_asset_id=$task->asset_id;
        }
        $emp_status=1;
        return view('admin.tasks.edit')->with('task',$task)->with('employeeId',$task->emp_id)->with('assets', $assets)->with('stores', $shops)->with('selected_shop_id', $selected_shop_id)->with('selected_asset_id', $selected_asset_id)->with('selected_task_type', $task->Tasktype)->with('description', $task->description)->with('emp_status', $emp_status);
        }
        else
        {
            $emp_status=0;
            return view('admin.tasks.edit')->with('task',$task)->with('employeeId','')->with('emp_status', $emp_status);
        }
        
        
        
    }

    /**
     * Update the specified Task in storage.
     *
     * @param  int              $id
     * @param UpdateTaskRequest $request
     *
     * @return Response
     */
    public function update($id, Request $request)
    {
        $task = Task::find($id);
        if(!empty($request->SelectAsset)){
        $input['asset_id']=$request->SelectAsset;
        }
        $input['emp_id']=$request->EmployeeId;
        $input['Tasktype']=$request->Tasktype;
        $input['StartDate']=$request->StartDate;
        $input['EndDate']=$request->EndDate;
        $input['description']=$request->TaskDetails;
        $task=Task::where('id',$id)->update($input);
        if(!empty($request->SelectStore)){
        $data['task_id']=$id;
        $data['shop_id']=$request->SelectStore;
        $task_shop=Task_shop::where('task_id',$id)->update($data);
        }

        if (empty($task)) {
            Flash::error('Task not found');

            return redirect(route('admin.tasks.index'));
        }

        Flash::success('Task updated successfully.');
        $edit='/admin/tasks/'.$id.'/edit/';
        $url=URL::to($edit);
        return redirect($url);
    }
    
    public function calendarupdated(Request $request)
    {
      
        $task = Task::find($request->task_id);
        if(!empty($request->modalSelectAsset)){
        $input['asset_id']=$request->modalSelectAsset;
        }
        $input['emp_id']=$request->modalEmployeeId;
        $input['Tasktype']=$request->modalTasktype;
        $input['StartDate']=$request->modalStartDate;
        $input['EndDate']=$request->modalEndDate;
        $input['description']=$request->modalTaskDetails;
        $task=Task::where('id',$request->task_id)->update($input);
        if(!empty($request->modalSelectStore)){
        $data['task_id']=$request->task_id;
        $data['shop_id']=$request->modalSelectStore;
        $task_shop=Task_shop::where('task_id',$request->task_id)->update($data);
        }

        if (empty($task)) {
            Flash::error('Task not found');

            return redirect(route('admin.tasks.index'));
        }


        Flash::success('Task updated successfully.');
        $url=URL::to('/admin/tasks/create/').'/'.$request->modalEmployeeId;
        return redirect($url);
    }

    /**
     * Remove the specified Task from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
      public function getModalDelete($id = null)
      {
          $error = '';
          $model = '';
          $confirm_route =  route('admin.tasks.delete',['id'=>$id]);
          return View('admin.layouts/modal_confirmation', compact('error','model', 'confirm_route'));

      }

       public function getDelete($id = null)
       {
           $sample = Task::destroy($id);

           // Redirect to the group management page
           return redirect(route('admin.tasks.index'))->with('success', Lang::get('message.success.delete'));

       }
       public function tasktDelete($id)
       {
           $sample = Task::destroy($id);

           echo "Task successfully deleted.";

       }
       public function usertasks(Request $request)
       {
        $tasks = Task::where('emp_id',$request->userid)->get();
        $shop_id='';
        $shop_name='';
        foreach ($tasks as $key => $value) {
            $employee=Employee::find($value->emp_id);
            $tasks[$key]->SelectEmployee.=$employee->EmployeeName;
            if($tasks[$key]->Tasktype=='Visit Shop'){
            $task_shop=Task_shop::where('task_id',$tasks[$key]->id)->get();
            foreach ($task_shop as $key1 => $value1) {
                $shop_id=$value1->shop_id;
                $shop=Shop::find($shop_id);
                $tasks[$key]->SelectStore=$shop->name;
            }
            }
            else if($tasks[$key]->Tasktype=='Scan QR')
            {
                $asset=Asset::find($tasks[$key]->asset_id);
                if(!empty($asset->shop_id)) {
                $shop=Shop::find($asset->shop_id);
                $tasks[$key]->SelectStore=$shop->name;
                }
            }
        }
        return view('admin.tasks.index')
            ->with('tasks', $tasks);
       }
       public function usertasksbyId($id)
       {
        $tasks = Task::where('emp_id',$id)->get();
        $shop_id='';
        $shop_name='';
        foreach ($tasks as $key => $value) {
            $employee=Employee::find($value->emp_id);
            $tasks[$key]->SelectEmployee.=$employee->EmployeeName;
            if($tasks[$key]->Tasktype=='Visit Shop'){
            $task_shop=Task_shop::where('task_id',$tasks[$key]->id)->get();
            foreach ($task_shop as $key1 => $value1) {
                $shop_id=$value1->shop_id;
                $shop=Shop::find($shop_id);
                $tasks[$key]->SelectStore=$shop->name;
                $tasks[$key]->shop_id.=$shop_id;
            }
            }
            else if($tasks[$key]->Tasktype=='Scan QR')
            {
                $asset=Asset::find($tasks[$key]->asset_id);
                $tasks[$key]->AssetName=$asset->AssetName;
                $tasks[$key]->AssetId.=$tasks[$key]->asset_id;
                if(!empty($asset->shop_id)) {
                $shop=Shop::find($asset->shop_id);
                $tasks[$key]->SelectStore=$shop->name;
                }
            }
        }
        echo json_encode($tasks);
       }
       public function getdata(Request $request) {
        $from=$request->dateFrom;
        $to=$request->dateTo;
        if(!empty($from) && !empty($to)) {
            $tasks = Task::whereBetween('created_at', [$from.' 00:00:00', $to.' 23:59:59'])->where('deleted_at',NULL)->get();
        $shop_id='';
        $shop_name='';
        foreach ($tasks as $key => $value) {
            $employee=Employee::find($value->emp_id);
            $tasks[$key]->SelectEmployee.=$employee->EmployeeName;
            if($tasks[$key]->Tasktype=='Visit Shop'){
            $task_shop=Task_shop::where('task_id',$tasks[$key]->id)->get();
            foreach ($task_shop as $key1 => $value1) {
                $shop_id=$value1->shop_id;
                $shop=Shop::find($shop_id);
                $tasks[$key]->SelectStore=$shop->name;
            }
            }
            else if($tasks[$key]->Tasktype=='Scan QR')
            {
                $asset=Asset::find($tasks[$key]->asset_id);
                if(!empty($asset->shop_id)) {
                $shop=Shop::find($asset->shop_id);
                $tasks[$key]->SelectStore=$shop->name;
                }
            }
        }
        }
        else {
            return redirect(route('admin.tasks.index'));
        }
       return view('admin.tasks.index')
            ->with('tasks', $tasks);
       }

}
