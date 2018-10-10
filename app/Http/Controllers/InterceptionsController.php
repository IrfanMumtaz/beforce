<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\role;
class InterceptionsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $Brand_Ambassador_result=Role::where('slug','Brand Ambassador')->orWhere('name','Brand Ambassador')->get();
        foreach ($Brand_Ambassador_result as $key => $value) {
         $Brand_Ambassador_role_id=$value->id;
        }
        $Brand_Ambassador_data=Role::findOrFail($Brand_Ambassador_role_id)->employees;
        foreach ($Brand_Ambassador_data as $key => $value) {
            $Brand_Ambassador_data[$key]->BA.=$value->EmployeeName;
            $Brand_Ambassador_data[$key]->noofinterception.=$value->id;
        }
        return view('admin.interceptions.index')
            ->with('interceptions', $Brand_Ambassador_data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
