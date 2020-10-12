<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;
use App\Models\Vehicle;

use App\Http\Requests\VehicleStorageRequest;

class VehicleController extends BaseController
{

  /**
   * Display a listing of the resource.
   *
	 * @param  Request  $request
   * @return \Illuminate\Http\Response
   */
  public function index(Request $request)
  {      
      $oVehicles = new Vehicle();      
     

      //Filter by plate
      if ($request->get('plate',false)) {
        $oVehicles = $oVehicles->where('plate', 'like', '%'.$request->plate.'%');
      }

      //Filter by color
      if ($request->get('color',false)) {
        $oVehicles = $oVehicles->where('color', 'like', '%'.$request->color.'%');
      }

      //Filter by plate brand
      if ($request->get('brand',false)) {
        $oVehicles = $oVehicles->where('brand', 'like', '%'.$request->brand.'%');
      }

      //Filter by vehicle owner
      if ($request->get('third_party_owner',false)) {
        $oVehicles = $oVehicles->whereHas('thirdPartyOwner', function($query) use($request){
            $query
            ->filterName($request->third_party_owner)
            ->orWhere('identification', 'like', '%'.$request->third_party_owner.'%');
        });
      }

      //Filter by vehicle driver
      if ($request->get('third_party_driver',false)) {
        $oVehicles = $oVehicles->whereHas('thirdPartyDriver', function($query) use($request){
            $query
            ->filterName($request->third_party_owner)
            ->orWhere('identification', 'like', '%'.$request->third_party_owner.'%');
        });
      }

      
      //Load relationship
      $oVehicles = $oVehicles->with('vehicleType','thirdPartyOwner','thirdPartyDriver');

      //Order collection action
      if(isset($request->sortActive)){
        $oVehicles = $oVehicles->orderBy($request->sortActive,$request->sortDirection);        
      }else{
        $oVehicles = $oVehicles->orderBy('plate');
      }

      //Paginate collection
      $response = $this->paginate(array(
          "oCollection" => $oVehicles
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
  public function store(VehicleStorageRequest $request)
  {
      //
      $model = new Vehicle();
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
  	$model = Vehicle::find($id)
    ->load('vehicleType','thirdPartyOwner','thirdPartyDriver');    
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
  public function update(VehicleStorageRequest $request, $id)
  {
      
      $model = Vehicle::find($id);
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
      $oVehicle = Vehicle::find($id);
      
      if($oVehicle->delete()){
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
