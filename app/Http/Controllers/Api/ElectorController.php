<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Elector;
use App\Models\JefeFamilia;
use App\Models\PersonalCaracterizacion;

class ElectorController extends Controller
{
    
    function searchByCedula(Request $request){
        $hasJefeFamilia=$request->input('has_jefe_familia');
        $response=[
            "success"=>true
        ];
        if($hasJefeFamilia){
            $response['hasJefeFamilia']=false;
        }
        if(\Auth::user()->municipio_id == null){
            $elector = $this->searchPersonalCaracterizacionByCedula($request->cedula);
            if($elector){
                $response["elector"]=$elector;
                if ($hasJefeFamilia) {
                    $jefeFamilia=JefeFamilia::where('personal_caraterizacion_id',$elector->id)->first();
                    $response['hasJefeFamilia']=$jefeFamilia ? true : false;
                }
                return response()->json($response);
            }

            $elector = $this->searchElectorByCedula($request->cedula);
            if($elector){
                $response["elector"]=$elector;
                return response()->json($response);
            }
            $response["success"]=false;
            $response["msg"]="Elector no encontrado";
            return response()->json($response);
        
        }else{
            
            $elector = $this->searchPersonalCaracterizacionByCedula($request->cedula, \Auth::user()->municipio_id);
            if($elector){
                
                if($elector->municipio_id != \Auth::user()->municipio_id){
                    $response["success"]=false;
                    $response["msg"]="Éste elector no pertence a tu municipio";
                    return response()->json($response);
                }
                if ($hasJefeFamilia) {
                    $jefeFamilia=JefeFamilia::where('personal_caraterizacion_id',$elector->id)->first();
                    $response['hasJefeFamilia']=$jefeFamilia ? true : false;
                }
                $response["elector"]=$elector;
                return response()->json($response);
            }

            $elector = $this->searchElectorByCedula($request->cedula, \Auth::user()->municipio_id);
            if($elector){

                if($elector->municipio_id != \Auth::user()->municipio_id){
                    $response["success"]=false;
                    $response["msg"]="Éste elector no pertence a tu municipio";
                    return response()->json($response);
                }
             
                $response["elector"]=$elector;
                return response()->json($response);
            }
            $response["success"]=false;
            $response["msg"]="Elector no encontrado";
            return response()->json($response);

        }

        
        
    }

    function searchPersonalCaracterizacionByCedula($cedula){

        $elector = PersonalCaracterizacion::where("cedula", $cedula)->first();
        return $elector;

    }

    function searchElectorByCedula($cedula){

        $elector = Elector::where("cedula", $cedula)->first();
        return $elector;

    }


}
