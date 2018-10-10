<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Requests\CreateEBStagRequest;
use App\Http\Requests\UpdateEBStagRequest;
use App\Repositories\EBStagRepository;
use App\Http\Controllers\AppBaseController as InfyOmBaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use App\Models\EBStag;
use Flash;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

class EBStagController extends InfyOmBaseController
{
    /** @var  EBStagRepository */
    private $eBStagRepository;

    public function __construct(EBStagRepository $eBStagRepo)
    {
        $this->eBStagRepository = $eBStagRepo;
    }

    /**
     * Display a listing of the EBStag.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {

        $this->eBStagRepository->pushCriteria(new RequestCriteria($request));
        $eBStags = $this->eBStagRepository->all();
        return view('admin.eBStags.index')
            ->with('eBStags', $eBStags);
    }

    /**
     * Show the form for creating a new EBStag.
     *
     * @return Response
     */
    public function create()
    {
        return view('admin.eBStags.create');
    }

    /**
     * Store a newly created EBStag in storage.
     *
     * @param CreateEBStagRequest $request
     *
     * @return Response
     */
    public function store(CreateEBStagRequest $request)
    {
        $input = $request->all();

        $eBStag = $this->eBStagRepository->create($input);

        Flash::success('EBStag saved successfully.');

        return redirect(route('admin.eBStags.index'));
    }

    /**
     * Display the specified EBStag.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $eBStag = $this->eBStagRepository->findWithoutFail($id);

        if (empty($eBStag)) {
            Flash::error('EBStag not found');

            return redirect(route('eBStags.index'));
        }

        return view('admin.eBStags.show')->with('eBStag', $eBStag);
    }

    /**
     * Show the form for editing the specified EBStag.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $eBStag = $this->eBStagRepository->findWithoutFail($id);

        if (empty($eBStag)) {
            Flash::error('EBStag not found');

            return redirect(route('eBStags.index'));
        }

        return view('admin.eBStags.edit')->with('eBStag', $eBStag);
    }

    /**
     * Update the specified EBStag in storage.
     *
     * @param  int              $id
     * @param UpdateEBStagRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateEBStagRequest $request)
    {
        $eBStag = $this->eBStagRepository->findWithoutFail($id);

        

        if (empty($eBStag)) {
            Flash::error('EBStag not found');

            return redirect(route('eBStags.index'));
        }

        $eBStag = $this->eBStagRepository->update($request->all(), $id);

        Flash::success('EBStag updated successfully.');

        return redirect(route('admin.eBStags.index'));
    }

    /**
     * Remove the specified EBStag from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
      public function getModalDelete($id = null)
      {
          $error = '';
          $model = '';
          $confirm_route =  route('admin.eBStags.delete',['id'=>$id]);
          return View('admin.layouts/modal_confirmation', compact('error','model', 'confirm_route'));

      }

       public function getDelete($id = null)
       {
           $sample = EBStag::destroy($id);

           // Redirect to the group management page
           return redirect(route('admin.eBStags.index'))->with('success', Lang::get('message.success.delete'));

       }

}
