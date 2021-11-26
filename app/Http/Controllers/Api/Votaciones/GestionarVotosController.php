<?php

namespace App\Http\Controllers\Api\Votaciones;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CentroVotacion;

class GestionarVotosController extends Controller
{
    
    function getCentrosVotacion(Request $request){

        if($request->municipio_id == 0){
            $centrosVotacion = CentroVotacion::with("parroquia", "parroquia.municipio")->paginate(20);
        }else{

            $municipio = $request->municipio_id;
            $centrosVotacion = CentroVotacion::whereHas("parroquia", function($q) use($municipio){
                $q->where("municipio_id", $municipio);
            })->with("parroquia", "parroquia.municipio")->paginate(20);

        }

        return response()->json($centrosVotacion);

    }

    function searchCentrosVotacion(Request $request){

        if($request->searchText == "" || $request->searchText == null){
            $this->getCentrosVotacion($request);
        }

        

        if($request->municipio_id == 0){
           
            $centrosVotacion = CentroVotacion::with("parroquia", "parroquia.municipio")->where("nombre", "like", '%'.strtoupper($request->searchText).'%')->paginate(20);
        }else{

            $municipio = $request->municipio_id;
            $centrosVotacion = CentroVotacion::whereHas("parroquia", function($q) use($municipio){
                $q->where("municipio_id", $municipio);
            })->with("parroquia", "parroquia.municipio")->where("nombre", "like", '%'.strtoupper($request->searchText).'%')->paginate(20);
            
        }

        return response()->json($centrosVotacion);

    }

}
