<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class BaseController extends Controller
{
    //
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        
    }

    public function responseError($aOptions = []){

    	$aDefaultOptions = array(
			"json" => true,
			"type" => "error",
			"cod" => 551,
            "message" => null
		);

		$aOptions = array_merge($aDefaultOptions,$aOptions);

		$aMessages =  [
          404 => "Recurso no encontado",
          551 => "No se pudo guardar el registro!",
          552 => "El registro ya existe!",
          553 => "No se pudo subir el archivo!",
          554 => "Consecutivo agotado. Por favor comuniquese con el administrador!",
          555 => "No se encontro el tercero!",
          556 => "Ya hay un consecutivo activo, cambie el estado antes de activar otro!",
          557 => "No hay consecutivos activos para este tipo de documento!",
          558 => "La referencia ya existe!",
          559 => "Posee un usuario asignado!",
          560 => "Este tercero tiene movimientos y no puede ser eliminado!"
        ];
        if(empty($aOptions['message'])){
            if(empty( $aMessages[$aOptions["cod"]]) ){
                $message = "Sin mensaje especifico";
            }else{
                $message = $aMessages[$aOptions["cod"]];
            }
        }else{
            $message = $aOptions['message'];
        }
		if($aOptions["json"]){

			return \Response::json(
                ["messages" => [
                      [
                        "message" => $message,
                        "type" => $aOptions["type"]
                      ]
                    ]
                ],
                $aOptions["cod"]
            );
		}

    	return [
            "message" => $aMessages[$aOptions["cod"]],
        		"type" => $aOptions["type"]
        ];
    }

    public function paginate($aOptions = [],$request){

        $aDefaultOptions = array(
            "oCollection" => null,
            "withTotal" => false,
            "perPage" => $request->input('perPage', ''),
            "currentPage" => $request->input('currentPage', ''),
        );

        $aOptions = array_merge($aDefaultOptions,$aOptions);

        if($aOptions['currentPage'] < 1){
            $aOptions['currentPage'] = 1;
        }
        
        if($aOptions['currentPage']!= '' && $aOptions['perPage']  != '' ){

            $total = $aOptions["oCollection"]->count();
            $offset = ($aOptions['currentPage']-1)*$aOptions['perPage'];
            $aOptions["oCollection"] = $aOptions["oCollection"]->skip($offset)->take($aOptions['perPage'])->get();

            $response["currentPage"] = $aOptions['currentPage'];
            $response["perPage"] = $aOptions['perPage'];
            $response["totalItems"] = $total;

        }else{
            if($aOptions["withTotal"]){
                $total = $oModels->count();
                $response["totalItems"] = $total;
            }        
                                    
            $aOptions["oCollection"] = $aOptions["oCollection"]->get();

        }

        $response["data"] = $aOptions["oCollection"];
       
        //sleep(2);

        return $response;
    }

    public function pathStorage() {
         $year = date("Y");
         $month = date("m");
         $day = date("d");
         $week = '';
         switch ($day) {
                 case $day > 0 && $day <= 7: {
                     $week .= 'week1';
                     break;
                 }
                 case $day > 7 && $day <= 14: {
                     $week .= 'week2';
                     break;
                 }
                 case $day > 14 && $day <= 21: {
                     $week .= 'week3';
                     break;
                 }
                 case $day > 21 && $day <= 31: {
                     $week .= 'week4';
                     break;
                 }
         }
         $path = "{$year}/{$month}/{$week}";

         return $path;

    }

}
