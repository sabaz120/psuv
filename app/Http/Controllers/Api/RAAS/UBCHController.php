<?php

namespace App\Http\Controllers\Api\RAAS;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JefeUbch;
use App\Models\PersonalCaracterizacion;
use App\Models\JefeComunidad;

use App\Http\Requests\RAAS\UBCH\UBCHStoreRequest;
use App\Http\Requests\RAAS\UBCH\UBCHUpdateRequest;
use App\Http\Requests\RAAS\UBCH\UBCHCedulaSearchRequest;

use App\Traits\PersonalCaracterizacionTrait;
use App\Traits\ElectorTrait;

class UBCHController extends Controller
{
    
    use PersonalCaracterizacionTrait;
    use ElectorTrait;

    function searchByCedula(Request $request){

        if($this->verificarDuplicidadCedula($request->cedula) > 0){
            return response()->json(["success" => false, "msg" => "Esta cédula ya pertenece a un Jefe de UBCH"]);
        }

        $elector = $this->searchPersonalCaracterizacionByCedula($request->cedula);
        
        if($elector){
            return response()->json(["success" => true, "elector" => $elector]);
        }

        $elector = $this->searchElectorByCedula($request->cedula);
        if($elector){
            return response()->json(["success" => true, "elector" => $elector]);
        }

        return response()->json(["success" => false, "msg" => "Elector no encontrado"]);
        
    }

    function store(UBCHStoreRequest $request){

        try{

            if($this->verificarDuplicidadCedula($request->cedula) > 0){
                return response()->json(["success" => false, "msg" => "Esta cédula ya pertenece a un Jefe de UBCH"]);
            }

            if($this->verificarUnSoloCentroVotacion($request->centro_votacion_id) > 0){
                return response()->json(["success" => false, "msg" => "Ya existe otro jefe para ésta UBCH"]);
            }

            $personalCaracterizacion = PersonalCaracterizacion::where("cedula", $request->cedula)->first();
            
            if($personalCaracterizacion == null){
                $personalCaracterizacion = $this->storePersonalCaracterizacion($request);
            }
        
            $jefeUbch = new JefeUbch;
            $jefeUbch->personal_caracterizacion_id = $personalCaracterizacion->id;
            $jefeUbch->centro_votacion_id = $request->centro_votacion_id;
            $jefeUbch->save();

            $this->updatePersonalCaracterizacion($jefeUbch->personal_caracterizacion_id, $request);

            return response()->json(["success" => true, "msg" => "Jefe de UBCH creado"]);
        }catch(\Exception $e){

            return response()->json(["success" => false, "msg" => "Ha ocurrido un problema"]);

        }
        

    }

    function verificarDuplicidadCedula($cedula){

        return JefeUbch::whereHas('personalCaracterizacion', function($q) use($cedula){
            $q->where('cedula', $cedula);
        })->count();

    }   

    function verificarUnSoloCentroVotacion($centro_votacion){

        return  JefeUbch::where("centro_votacion_id", $centro_votacion)->count();

    }

    function jefeUbchByCedula(UBCHCedulaSearchRequest $request){
        $cedula = $request->cedulaJefe;
        $jefeUbch = JefeUbch::whereHas('personalCaracterizacion', function($q) use($cedula){
            $q->where('cedula', $cedula);
        })->with("personalCaracterizacion")->first();

        if($jefeUbch == null){
            return response()->json(["success" => false, "msg" => "Jefe de UBCH no encontrado"]);
        }

        return response()->json($jefeUbch);

    } 


    function update(UBCHUpdateRequest $request){

        try{
            
            if($this->verificarUnSoloCentroVotacionUpdate($request->centro_votacion_id, $request->id) > 0){
                return response()->json(["success" => false, "msg" => "Ya existe otro jefe para ésta UBCH"]);
            }

            $jefeUbch = JefeUbch::find($request->id);

            $personalCaracterizacion = PersonalCaracterizacion::where("cedula", $request->cedula)->first();
            
            if($personalCaracterizacion == null){
                $personalCaracterizacion = $this->storePersonalCaracterizacion($request);
            }

            
            $jefeUbch->personal_caracterizacion_id = $personalCaracterizacion->id;

            $personalCaracterizacion = $this->updatePersonalCaracterizacion($jefeUbch->personal_caracterizacion_id, $request);
            $jefeUbch->centro_votacion_id = $request->centro_votacion_id;
            $jefeUbch->update();

            return response()->json(["success" => true, "msg" => "Jefe de UBCH actualizado"]);

        }
        catch(\Exception $e){

            return response()->json(["success" => false, "msg" => "Ha ocurrido un problema", "err" => $e->getMessage(), "ln" => $e->getLine()]);

        }
        

    }

    function verificarUnSoloCentroVotacionUpdate($centro_votacion, $jefeUbchId){

        return  JefeUbch::where("centro_votacion_id", $centro_votacion)->where("id", "<>", $jefeUbchId)->count();

    }

    function suspend(Request $request){

        try{

            $jefeComunidadCount = JefeComunidad::where("jefe_ubch_id", $request->id)->count();
            
            if($jefeComunidadCount > 0){
                return response()->json(["success" => false, "msg" => "No se pudo eliminar el jefe de UBCH ya que tiene jefes de comunidad asociados"]);
            }

            $jefeUbch = JefeUbch::find($request->id);
            $jefeUbch->delete();

            return response()->json(["success" => true, "msg" => "Jefe de UBCH eliminado"]);

        }
        catch(\Exception $e){

            return response()->json(["success" => false, "msg" => "Ha ocurrido un problema", "err" => $e->getMessage(), "ln" => $e->getLine()]);

        }
        

    }

    function fetch(Request $request){

        $jefeUbch = JefeUbch::with("personalCaracterizacion", "personalCaracterizacion.municipio", "personalCaracterizacion.parroquia", "personalCaracterizacion.centroVotacion", "personalCaracterizacion.partidoPolitico", "personalCaracterizacion.movilizacion")->orderBy("id", "desc")->paginate(15);
        
        return response()->json($jefeUbch);

    }

    function search(Request $request){

        if(!isset($request->cedula)){
            
            $jefeUbch = JefeUbch::with("personalCaracterizacion", "personalCaracterizacion.municipio", "personalCaracterizacion.parroquia", "personalCaracterizacion.centroVotacion", "personalCaracterizacion.partidoPolitico", "personalCaracterizacion.movilizacion")->orderBy("id", "desc")->paginate(15);
        
            return response()->json($jefeUbch);

        }


        $cedula = $request->cedula;
        $jefeUbch = JefeUbch::with("personalCaracterizacion", "personalCaracterizacion.municipio", "personalCaracterizacion.parroquia", "personalCaracterizacion.centroVotacion", "personalCaracterizacion.partidoPolitico", "personalCaracterizacion.movilizacion")->whereHas('personalCaracterizacion', function($q) use($cedula){
            $q->where('cedula', $cedula);
        })->orderBy("id", "desc")->paginate(15);

        return response()->json($jefeUbch);

    }



}
