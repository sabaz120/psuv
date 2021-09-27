<?php

namespace App\Http\Controllers\api\RAAS;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JefeUbch;
use App\Models\JefeComunidad;
use App\Models\PersonalCaracterizacion;
use App\Http\Requests\RAAS\JefeComunidad\JefeComunidadStoreRequest;
use App\Http\Requests\RAAS\JefeComunidad\JefeComunidadUpdateRequest;
//use App\Http\Requests\RAAS\UBCH\UBCHCedulaSearchRequest;
use App\Traits\PersonalCaracterizacionTrait;

class JefeComunidadController extends Controller
{
    use PersonalCaracterizacionTrait;

    function store(JefeComunidadStoreRequest $request){

        try{

            if($this->verificarDuplicidadCedula($request->cedula) > 0){
                return response()->json(["success" => false, "msg" => "Esta cédula ya pertenece a un Jefe de Comunidad"]);
            }

            if($this->verificarCedulaJefeUBCH($request->cedulaJefe) == 0){
                return response()->json(["success" => false, "msg" => "Esta cédula no le pertenece a un jefe de UBCH"]);
            }

            $personalCaracterizacion = PersonalCaracterizacion::where("cedula", $request->cedula)->first();
            
            if($personalCaracterizacion == null){
                $personalCaracterizacion = $this->storePersonalCaracterizacion($request);
            }
        
            $jefeComunidad = new JefeComunidad;
            $jefeComunidad->personal_caracterizacion_id = $personalCaracterizacion->id;
            $jefeComunidad->comunidad_id = $request->comunidad;

            $cedula = $request->cedulaJefe;
            $jefeComunidad->jefe_ubch_id = JefeUbch::whereHas('personalCaracterizacion', function($q) use($cedula){
                $q->where('cedula', $cedula);
            })->first()->id;
            $jefeComunidad->save();

            $this->updatePersonalCaracterizacion($jefeComunidad->personal_caracterizacion_id, $request);

            return response()->json(["success" => true, "msg" => "Jefe de Comunidad creado"]);
        }catch(\Exception $e){

            return response()->json(["success" => false, "msg" => "Ha ocurrido un problema", "err" => $e->getMessage(), "ln" => $e->getLine()]);

        }
        

    }

    function verificarDuplicidadCedula($cedula){

        return JefeComunidad::whereHas('personalCaracterizacion', function($q) use($cedula){
            $q->where('cedula', $cedula);
        })->count();

    }  

    function verificarCedulaJefeUBCH($cedula){

        return JefeUbch::whereHas('personalCaracterizacion', function($q) use($cedula){
            $q->where('cedula', $cedula);
        })->count();

    }   

    function jefeUbchByCedula(UBCHCedulaSearchRequest $request){
        $cedula = $request->cedula;
        $jefeUbch = JefeUbch::whereHas('personalCaracterizacion', function($q) use($cedula){
            $q->where('cedula', $cedula);
        })->with("personalCaracterizacion")->first();

        if($jefeUbch){
            return response()->json($jefeUbch);
        }else{

            return response()->json(["success" => false, "msg" => "Jefe de UBCH no encontrado"]);

        }

        

    } 


    function update(JefeComunidadUpdateRequest $request){

        try{

            $jefeComunidad = JefeComunidad::find($request->id);
       
            $personalCaracterizacion = $this->updatePersonalCaracterizacion($jefeComunidad->personal_caracterizacion_id, $request);

            return response()->json(["success" => true, "msg" => "Jefe de Comunidad actualizado"]);

        }
        catch(\Exception $e){

            return response()->json(["success" => false, "msg" => "Ha ocurrido un problema", "err" => $e->getMessage(), "ln" => $e->getLine()]);

        }
        

    }

    function suspend(JefeComunidadUpdateRequest $request){

        try{

            if($this->verificarDuplicidadCedula($request->cedula) > 0){
                return response()->json(["success" => false, "msg" => "Esta cédula ya pertenece a un Jefe de Comunidad"]);
            }

            $jefeComunidad = JefeComunidad::find($request->id);
            $jefeUbchId = $jefeComunidad->jefe_ubch_id;
            $comunidadId = $jefeComunidad->comunidad_id;
            $jefeComunidad->delete();

            $personalCaracterizacion = PersonalCaracterizacion::where("cedula", $request->cedula)->first();
            
            if($personalCaracterizacion == null){
                $personalCaracterizacion = $this->storePersonalCaracterizacion($request);
            }

            $jefeComunidad = new JefeComunidad;
            $jefeComunidad->personal_caracterizacion_id = $personalCaracterizacion->id;
            $jefeComunidad->comunidad_id = $comunidadId;
            $jefeComunidad->jefe_ubch_id = $jefeUbchId;
            $jefeComunidad->save();

            $personalCaracterizacion = $this->updatePersonalCaracterizacion($jefeComunidad->personal_caracterizacion_id, $request);

            return response()->json(["success" => true, "msg" => "Jefe de Comunidad suspendido y sustituido"]);

        }
        catch(\Exception $e){

            return response()->json(["success" => false, "msg" => "Ha ocurrido un problema", "err" => $e->getMessage(), "ln" => $e->getLine()]);

        }
        

    }

    function fetch(Request $request){

        $jefeComunidad = JefeComunidad::with("personalCaracterizacion", "personalCaracterizacion.municipio", "personalCaracterizacion.parroquia", "personalCaracterizacion.centroVotacion", "personalCaracterizacion.partidoPolitico", "personalCaracterizacion.movilizacion", "comunidad", "jefeUbch", "jefeUbch.personalCaracterizacion")->orderBy("id", "desc")->paginate(15);
        
        return response()->json($jefeComunidad);

    }
}
