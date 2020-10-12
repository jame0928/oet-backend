<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;
use App\Models\ThirdParty;

use App\Http\Requests\ThirdPartyStorageRequest;

class ThirdPartyController extends BaseController
{

  /**
   * Display a listing of the resource.
   *
	 * @param  Request  $request
   * @return \Illuminate\Http\Response
   */
  public function index(Request $request)
  {      
      $oThirdParties = new ThirdParty();      
     
      //Filter by applying name scope
      if ($request->get('name',false)) {
        $oThirdParties = $oThirdParties->filterName($request->name);       
      }

      //Filter by identification
      if ($request->get('identification',false)) {
        $oThirdParties = $oThirdParties->where('identification', 'like', '%'.$request->identification.'%');
      }

      //Load relationship
      $oThirdParties = $oThirdParties->with('city');

      //Order collection action
      if(isset($request->sortActive)){
        switch ($request->sortActive) {
          //Order by full name 
          case 'full_name':
            $oThirdParties = $oThirdParties->orderBy('first_name',$request->sortDirection);
            $oThirdParties = $oThirdParties->orderBy('second_name',$request->sortDirection);
            $oThirdParties = $oThirdParties->orderBy('last_name',$request->sortDirection);
          break;
          
          default:
          {
            $oThirdParties = $oThirdParties->orderBy($request->sortActive,$request->sortDirection);
          }
          break;
        }
        
      }

      //Paginate collection
      $response = $this->paginate(array(
          "oCollection" => $oThirdParties
      ),$request);
      
      return $response;
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create(Request $request)
  {
      //
      return $this->store($request);

  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(ThirdPartyStorageRequest $request)
  {
      //
      $model = new ThirdParty();
      $data = $request->input();
      $model->fill($data);

      if ($model->save()) {
        return $model;
      }

      return $this->responseError();
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function show($id)
  {
  	$model = ThirdParty::find($id)
    ->load('city');    
    return $model; 
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function edit( $id)
  {
      return $this->show( $id);

  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function update(ThirdPartyStorageRequest $request, $id)
  {
      
      $model = ThirdParty::find($id);
      $model->fill($request->input());
      if ($model->save()) {
          return $model;
      }

      return $this->responseError();
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy( $id)
  {
      $oThirdParty = ThirdParty::find($id);
      
      if($oThirdParty->delete()){
          return \Response::json(true);
      }else{
          return $this->responseError(['cod'=>555]);
      }
  }

  /**
   *
   * Implement Remove resource
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
  */

  public function delete($id){
      return $this->destroy($id);
  }

  
}
