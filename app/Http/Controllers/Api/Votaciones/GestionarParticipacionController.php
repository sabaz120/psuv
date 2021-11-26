<?php

namespace App\Http\Controllers\Api\Votaciones;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Mesa\mesaStoreRequest;
use App\Models\Mesa;
use App\Models\ParticipacionCentroVotacion;
use App\Models\Eleccion;
use Carbon\Carbon;

class GestionarParticipacionController extends Controller
{
    
    function getMesas($centro_votacion_id){

        $mesas = Mesa::where("centro_votacion_id", $centro_votacion_id)->get();

        return response()->json($mesas);

    }

    function store(mesaStoreRequest $request){

        try{    

            $eleccionId =Eleccion::orderBy("id", "desc")->first()->id;

            if(!$this->validateStore($request, $eleccionId)){
                return response()->json(["success" => false, "msg" => "Ya existe una participación para esta mesa con la misma hora"]);
            }

            
            $participacion = new ParticipacionCentroVotacion;
            $participacion->hora = Carbon::createFromFormat('H:iA',  $request->hora.":".$request->minuto.$request->meridiano);
            $participacion->cantidad = intval($request->cantidad_votos);
            $participacion->mesa_id = $request->mesa;
            $participacion->eleccion_id = $eleccionId;
            $participacion->save();

            return response()->json(["success" => true, "msg" => "Participación creada"]);

        }catch(\Exception $e){

            return response()->json(["success" => false, "msg" => "Hubo un problema", "err" => $e->getMessage(), "ln" => $e->getLine()]);
        
        }

    }

    function getParticipaciones(Request $request){
        
        $centro_votacion_id = $request->centro_votacion_id;
        $eleccionId =Eleccion::orderBy("id", "desc")->first()->id;
        $participaciones = ParticipacionCentroVotacion::whereHas("mesa", function($q) use($centro_votacion_id){
            $q->where("centro_votacion_id", $centro_votacion_id);
        })->where("eleccion_id", $eleccionId)->with("mesa")->paginate(20);

        return response()->json($participaciones);
    }

    function validateStore($request, $eleccionId){

        if(ParticipacionCentroVotacion::where("hora", Carbon::createFromFormat('H:iA',  $request->hora.":".$request->minuto.$request->meridiano))->where("mesa_id", $request->mesa)->where("eleccion_id", $eleccionId)->count() > 0){

            return false;

        }

        return true;

    }

    function searchMesaNombre(Request $request){

        $centroVotacionId = $request->centro_votacion_id;
        $eleccionId =Eleccion::orderBy("id", "desc")->first()->id;
        if($request->search == ""){

            
            $mesas = ParticipacionCentroVotacion::whereHas("mesa", function($q) use($centroVotacionId){
                
                $q->where("centro_votacion_id", $centroVotacionId);
            })->where("eleccion_id", $eleccionId)->with("mesa")->paginate(20);

        }else{
         
            $searchText = $request->search;
            $mesas = ParticipacionCentroVotacion::whereHas("mesa", function($q) use($searchText, $centroVotacionId){
                $q->where("numero_mesa", "like", "%".$searchText."%");
                $q->where("centro_votacion_id", $centroVotacionId);
            })->where("eleccion_id", $eleccionId)->with("mesa")->paginate(20);

        }

        
        return response()->json($mesas);

    }

    function delete(Request $request){

        $participacion = ParticipacionCentroVotacion::find($request->id);
        $participacion->delete();

        return response()->json(["success" => true, "msg" => "Participacion eliminada"]);

    }

}
