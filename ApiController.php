<?php

namespace App\Http\Controllers;
use App\Models\Employee;
use App\Models\SKU;
use App\sales;
use App\Order;
use App\Attendance;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function login(Request $request){
        $username=$request->input('username');
        $password=$request->input('password');
        $user=Employee::where([['username',$username],['password',$password]])->first();
        if($user !=null){
            if($user->id !='' && $user->SelectBrand!=''){
                return response()->json(['status'=>1,'user_id'=>$user->id,'name'=>$user->EmployeeName,'brand'=>$user->SelectBrand]);
            }else{
                return response()->json(['status'=>0,'user_id'=>$user->id,'name'=>$user->EmployeeName,'brand'=>'']);
            }
        }else{
            return response()->json(['status'=>0,'user_id'=>'','brand'=>'']);
        }
        
    }

    public function sku($brand){
       $sku=SKU::where('Brand',$brand)->get();
       if(sizeOf($sku)>0){
           return response()->json(['status'=>1,'skus'=>$sku]);
       }else{
           return response()->json(['status'=>0,'skus'=>'']);
       }       
    }

    public function sales(Request $request){
        $data=$request->all();
        try{
           $sales=sales::create($data);
        }catch(\Exception $e){
            return response()->json(['status'=>0]);
        }
        
        return response()->json(['status'=>1,'sales_id'=>$sales->id]);
    }
    
    public function order(Request $request){
        $data=$request->all();
        try{
           $order=Order::create($data);
        }catch(\Exception $e){
            return response()->json(['status'=>0]);
        }
        
        return response()->json(['status'=>1,'order_id'=>$order->id]);
    }

    public function attendance(Request $request){
        $image = $request->file('StartImage');
        $input['imagename'] = time().'.'.$image->getClientOriginalExtension();
        $destinationPath = public_path('/uploadimages');
        $image->move($destinationPath, $input['imagename']);
        $data=$request->all();
        $data['StartImage']=$input['imagename'];
        try{
           $attendance=Attendance::create($data);            
        }catch(\Exception $e){
           return response()->json(['status'=>0]);
        }
        return response()->json(['status'=>1,'id'=>$attendance->id]);
    }
    
    public function endattendance(Request $request){
         $image = $request->file('EndImage');
        $input['imagename'] = time().'.'.$image->getClientOriginalExtension();
        $destinationPath = public_path('/uploadimages');
        $image->move($destinationPath, $input['imagename']);
        $data=$request->all();
        $data['EndImage']=$input['imagename'];        
        try{
           $attendance=Attendance::where('id',$data['id'])->update($data);            
        }catch(\Exception $e){
           return response()->json(['status'=>0]);
        }
        return response()->json(['status'=>1]);
    }
}
