<?php

namespace App\Http\Controllers\api\RAAS;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JefeUbch;
use App\Models\JefeCalle;
use App\Models\JefeComunidad;
use App\Models\PersonalCaracterizacion;
use App\Http\Requests\RAAS\JefeComunidad\JefeComunidadStoreRequest;
use App\Http\Requests\RAAS\JefeComunidad\JefeComunidadUpdateRequest;
//use App\Http\Requests\RAAS\UBCH\UBCHCedulaSearchRequest;
use App\Traits\PersonalCaracterizacionTrait;
use App\Traits\ElectorTrait;

class JefeComunidadController extends Controller
{
    use PersonalCaracterizacionTrait;
    use ElectorTrait;

    public function searchByCedulaField( Request $request)
    {
        try {
            $cedula = $request->input('cedula');
            //Init query
            $query=JefeComunidad::query();
            //includes
            $query->with('personalCaracterizacion');
            //Filters
            if ($cedula) {
                $query->whereHas('personalCaracterizacion', function($q) use($cedula){
                    $q->where('cedula', $cedula);
                });
            }
            $entity=$query->first();
            if (!$entity) {
                throw new \Exception('Jefe de Comunidad no encontrado', 404);
            }

            if(\Auth::user()->municipio_id != null){

                if($entity->personalCaracterizacion->municipio_id != \Auth::user()->municipio_id){
                    return response()->json(["success" => false, "msg" => "Éste jefe de comunidad no pertenece a tu municipio"]);
                }

            }
            $comunidades=JefeComunidad::where('personal_caracterizacion_id',$entity->personal_caracterizacion_id)
            ->with('comunidad')->get();
            $entity->comunidades=$comunidades;

            $response = $this->getSuccessResponse($entity);
        } catch (\Exception $e) {
            $code = $this->getCleanCode($e);
            $response= $this->getErrorResponse($e, 'Error al Listar los registros');
        }
        return $this->response($response, $code ?? 200);
    }//index()

    function searchByCedula(Request $request){

        /*if($this->verificarDuplicidadCedula($request->cedula) > 0){
            return response()->json(["success" => false, "msg" => "Esta cédula ya pertenece a un Jefe de Comunidad"]);
        }*/

        $response = $this->searchPersonalCaracterizacionOrElector($request->cedula, $request->municipio_id);
        
        return response()->json($response);
        
    }

    function store(JefeComunidadStoreRequest $request){

        try{

            if($this->verificarExistenciaOtrosJefesComunidad($request->comunidad) > 0){
                return response()->json(["success" => false, "msg" => "Esta comunidad ya posee un Jefe"]);
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

    function verificarExistenciaOtrosJefesComunidad($comunidad_id){

        return JefeComunidad::where("comunidad_id", $comunidad_id)->count();

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

            $personalCaracterizacion = PersonalCaracterizacion::where("cedula", $request->cedula)->first();
            
            if($personalCaracterizacion == null){
                $personalCaracterizacion = $this->storePersonalCaracterizacion($request);
            }

            $jefeComunidad->personal_caracterizacion_id = $personalCaracterizacion->id;
            $jefeComunidad->update();
       
            $personalCaracterizacion = $this->updatePersonalCaracterizacion($jefeComunidad->personal_caracterizacion_id, $request);

            return response()->json(["success" => true, "msg" => "Jefe de Comunidad actualizado"]);

        }
        catch(\Exception $e){

            return response()->json(["success" => false, "msg" => "Ha ocurrido un problema", "err" => $e->getMessage(), "ln" => $e->getLine()]);

        }
        

    }

    function suspend(Request $request){

        try{

            $jefeComunidadCount = JefeCalle::where("jefe_comunidad_id", $request->id)->count();
            
            if($jefeComunidadCount > 0){
                return response()->json(["success" => false, "msg" => "No se pudo eliminar el jefe de Comunidad ya que tiene jefes de calle asociados"]);
            }

            $jefeComunidad = JefeComunidad::find($request->id);
            $jefeComunidad->delete();

            return response()->json(["success" => true, "msg" => "Jefe de Comunidad eliminado"]);

        }
        catch(\Exception $e){

            return response()->json(["success" => false, "msg" => "Ha ocurrido un problema", "err" => $e->getMessage(), "ln" => $e->getLine()]);

        }
        

    }

    function fetch(Request $request){

        $query = JefeComunidad::with("personalCaracterizacion", "personalCaracterizacion.municipio", "personalCaracterizacion.parroquia", "personalCaracterizacion.centroVotacion", "personalCaracterizacion.partidoPolitico", "personalCaracterizacion.movilizacion", "comunidad", "jefeUbch", "jefeUbch.personalCaracterizacion", "jefeUbch.personalCaracterizacion.centroVotacion", "jefeUbch.centroVotacion");
        
        if($request->municipio_id != null){
            $municipio_id = $request->municipio_id;
            $query->whereHas("personalCaracterizacion", function($q) use($municipio_id){
                $q->where('municipio_id', $municipio_id);
            });
        }

        $jefeComunidad = $query->orderBy("id", "desc")->paginate(15);
        
        return response()->json($jefeComunidad);

    }

    function search(Request $request){

     
        $cedula = $request->cedula;
        $query = JefeComunidad::with("personalCaracterizacion", "personalCaracterizacion.municipio", "personalCaracterizacion.parroquia", "personalCaracterizacion.centroVotacion", "personalCaracterizacion.partidoPolitico", "personalCaracterizacion.movilizacion", "comunidad", "jefeUbch", "jefeUbch.personalCaracterizacion", "jefeUbch.personalCaracterizacion.centroVotacion", "jefeUbch.centroVotacion");
        
        if($request->municipio_id != null){
 
            $municipio_id = $request->municipio_id;
            $query->whereHas("personalCaracterizacion", function($q) use($municipio_id){
                $q->where('municipio_id', $municipio_id);
            });
        }

        if($cedula){
         
            $jefeUbch = $query->whereHas('personalCaracterizacion', function($q) use($cedula){
                $q->where('cedula', $cedula);
            })->orderBy("id", "desc")->paginate(15);
        
        }else{

            $jefeUbch = $query->orderBy("id", "desc")->paginate(15);

        }
        

        return response()->json($jefeUbch);

    }

}
