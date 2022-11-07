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
        // if($this->verificarDuplicidadCedula($request->cedula) > 0){
        //     return response()->json(["success" => false, "msg" => "Esta cédula ya pertenece a un Jefe de UBCH"]);
        // }
        
        $response = $this->searchPersonalCaracterizacionOrElector($request->cedula, $request->municipio_id);
        
        return response()->json($response);
        
    }

    function store(UBCHStoreRequest $request){

        try{

            // if($this->verificarDuplicidadCedula($request->cedula) > 0){
            //     return response()->json(["success" => false, "msg" => "Esta cédula ya pertenece a un Jefe de UBCH"]);
            // }

            $personalCaracterizacion = PersonalCaracterizacion::where("cedula", $request->cedula)->first();
            
            if($personalCaracterizacion == null){
                $personalCaracterizacion = $this->storePersonalCaracterizacion($request);
            }

            $rolEquipoPolitico=\App\Models\RolesEquipoPolitico::find($request->rol_equipo_politico_id);
            if(!$rolEquipoPolitico->rolNivelTerritorial){
                return response()->json(["success" => false, "msg" => "El rol seleccionado no posee un nivel territorial configurado"]);
            }

            if($this->verificarUnSoloRolPorCentroVotacion($request->centro_votacion_id,$rolEquipoPolitico->rolNivelTerritorial->id) > 0){
                return response()->json(["success" => false, "msg" => "Esta cédula ya está asignada a un equipo de UBCH"]);
            }

            $jefeUbch = new JefeUbch;
            $jefeUbch->personal_caracterizacion_id = $personalCaracterizacion->id;
            $jefeUbch->centro_votacion_id = $request->centro_votacion_id;
            $jefeUbch->roles_nivel_territorial_id=$rolEquipoPolitico->rolNivelTerritorial->id;
            $jefeUbch->save();

            $this->updatePersonalCaracterizacion($jefeUbch->personal_caracterizacion_id, $request);

            return response()->json(["success" => true, "msg" => "Jefe de UBCH creado"]);
        }catch(\Exception $e){

            return response()->json(["success" => false, "msg" => "Ha ocurrido un problema", "err" => $e->getMessage()]);

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

    function verificarUnSoloRolPorCentroVotacion($centro_votacion,$roles_nivel_territorial_id){
        return  JefeUbch::where("centro_votacion_id", $centro_votacion)
        ->where("roles_nivel_territorial_id",$roles_nivel_territorial_id)
        ->count();
    }
    

    function jefeUbchByCedula(UBCHCedulaSearchRequest $request){
        $cedula = $request->cedulaJefe;
        $jefeUbch = JefeUbch::whereHas('personalCaracterizacion', function($q) use($cedula){
            $q->where('cedula', $cedula);
        })->with("personalCaracterizacion", "centroVotacion")->first();

        if($jefeUbch == null){
            return response()->json(["success" => false, "msg" => "Jefe de UBCH no encontrado"]);
        }
        
        if($request->municipio_id != null){
            if($jefeUbch->personalCaracterizacion->municipio_id != $request->municipio_id){
                return response()->json(["success" => false, "msg" => "Este Jefe de UBCH no pertenece a tu municipio "]);
            }
        }

        return response()->json($jefeUbch);

    } 


    function update(UBCHUpdateRequest $request){

        try{
            
            /*if($this->verificarUnSoloCentroVotacionUpdate($request->centro_votacion_id, $request->id) > 0){
                return response()->json(["success" => false, "msg" => "Ya existe otro jefe para ésta UBCH"]);
            }*/

            $jefeUbch = JefeUbch::find($request->id);

            $personalCaracterizacion = PersonalCaracterizacion::where("cedula", $request->cedula)->first();
            
            if($personalCaracterizacion == null){
                $personalCaracterizacion = $this->storePersonalCaracterizacion($request);
            }
    
            $rolEquipoPolitico=\App\Models\RolesEquipoPolitico::find($request->rol_equipo_politico_id);
            if(!$rolEquipoPolitico->rolNivelTerritorial){
                return response()->json(["success" => false, "msg" => "El rol seleccionado no posee un nivel territorial configurado"]);
            }

            $jefeUbch->personal_caracterizacion_id = $personalCaracterizacion->id;
            $personalCaracterizacion = $this->updatePersonalCaracterizacion($jefeUbch->personal_caracterizacion_id, $request);
            $jefeUbch->roles_nivel_territorial_id=$rolEquipoPolitico->rolNivelTerritorial->id;
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

            $jefeUbch = JefeUbch::find($request->id);
            $jefeUbch->delete();

            return response()->json(["success" => true, "msg" => "Jefe de UBCH eliminado"]);

        }
        catch(\Exception $e){

            return response()->json(["success" => false, "msg" => "Ha ocurrido un problema", "err" => $e->getMessage(), "ln" => $e->getLine()]);

        }
        

    }

    function fetch(Request $request){

        $query = JefeUbch::with(
            "centroVotacion", 
            "centroVotacion.parroquia.municipio", 
            "personalCaracterizacion", 
            "personalCaracterizacion.municipio", 
            "personalCaracterizacion.parroquia", 
            "personalCaracterizacion.centroVotacion", 
            "personalCaracterizacion.partidoPolitico", 
            "personalCaracterizacion.movilizacion",
            "RolesNivelTerritorial.RolesEquipoPolitico"
        );

        if(\Auth::user()->municipio_id != null){
            $municipio_id = \Auth::user()->municipio_id;
            $query->whereHas("personalCaracterizacion", function($q) use($municipio_id){
                $q->where('municipio_id', $municipio_id);
            });
        }
        
        $jefeUbch = $query->orderBy("id", "desc")->paginate(15);
        return response()->json($jefeUbch);
        
    }

    function search(Request $request){

        $cedula = $request->search;
        $query = JefeUbch::with(
            "centroVotacion", 
            "centroVotacion.parroquia.municipio", 
            "personalCaracterizacion", 
            "personalCaracterizacion.municipio", 
            "personalCaracterizacion.parroquia", 
            "personalCaracterizacion.centroVotacion", 
            "personalCaracterizacion.partidoPolitico", 
            "personalCaracterizacion.movilizacion",
            "RolesNivelTerritorial.RolesEquipoPolitico"
        );
        

        if(\Auth::user()->municipio_id != null){
            $municipio_id = \Auth::user()->municipio_id;
            $query->whereHas("personalCaracterizacion", function($q) use($municipio_id){
                $q->where('municipio_id', $municipio_id);
            });
        }
        if($cedula){
            $query->whereHas('personalCaracterizacion', function($q) use($cedula){
                $q->where('cedula', $cedula);
            });
        }
        $jefeUbch = $query->orderBy("id", "desc")->paginate(15);

        return response()->json($jefeUbch);

    }



}
