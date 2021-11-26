<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CentroVotacion;
use App\Models\Votacion;
use App\Models\ParticipacionCentroVotacion;
use App\Models\PersonalCaracterizacion;
use App\Models\Municipio;
use App\Models\Parroquia;

class DashboardController extends Controller
{
    
    function generate(Request $request){

        ini_set('max_execution_time', 300);


        if($request->centroVotacion != "0"){
       
            $data = $this->selectedCentroVotacion($request->centroVotacion);
            $data = ["data" => $data, "entities" => [], "type" => " "];
        }
        else if($request->parroquia != "0"){
           
            $data = $this->selectedParroquia($request->parroquia);
            $todosCentroVotacion = $this->todosCentroVotacionGraficas($request->parroquia);

            $data = ["data" => $data, "entities" => $todosCentroVotacion, "type" => "Centros de votación de ".Parroquia::find($request->parroquia)->nombre];
        }
        else if($request->municipio != "0"){
            $data = $this->selectedMunicipio($request->municipio);
            $todasParroquias = $this->todasParroquiasGraficas($request->municipio);

            $data = ["data" => $data, "entities" => $todasParroquias, "type" => "Parroquias de ".Municipio::find($request->municipio)->nombre];
        }  
        else if($request->municipio == "0"){
            
            $data = $this->selectedAll();
            $todosMunicipios = $this->todosMunicipiosGraficas();

            $data = ["data" => $data, "entities" => $todosMunicipios, "type" => "Todos los municipios"];
        }  

        return response()->json($data);

    }

    function selectedCentroVotacion($centro_votacion_id){

        $movilizacion = Votacion::where("ejercio_voto", true)->where("centro_votacion_id", $centro_votacion_id)->count();

        $participacion = ParticipacionCentroVotacion::whereHas("mesa", function($q) use($centro_votacion_id){
            $q->where("centro_votacion_id", $centro_votacion_id);
        })->sum("cantidad");

        return ["movilizacion" => $movilizacion, "participacion" => $participacion];
    
    }

    function selectedParroquia($parroquia_id){

        $movilizacion = Votacion::where("ejercio_voto", true)->whereHas("centroVotacion", function($q) use($parroquia_id){

            $q->where("parroquia_id", $parroquia_id);

        })->count();

        $participacion = ParticipacionCentroVotacion::whereHas("mesa.centroVotacion", function($q) use($parroquia_id){
            $q->where("parroquia_id", $parroquia_id);
        })->sum("cantidad");

        return ["movilizacion" => $movilizacion, "participacion" => $participacion];
    
    }

    function todosCentroVotacionGraficas($parroquia_id){

        $estadisticas = [];
        $centros = CentroVotacion::where("parroquia_id", $parroquia_id)->get();

        foreach($centros as $centro){        

            $centroId = $centro->id;
            $movilizacion = Votacion::where("ejercio_voto", true)->where("centro_votacion_id", $centroId)->count();

            $participacion = ParticipacionCentroVotacion::whereHas("mesa", function($q) use($centroId){
                $q->where("centro_votacion_id", $centroId);
            })->sum("cantidad");

            $estadisticas[] = [
                "nombre" => $centro->nombre,
                "movilizacion" => $movilizacion,
                "participacion" => $participacion
            ];

        }

        return $estadisticas;

    }

    function selectedMunicipio($municipio_id){

        $movilizacion = Votacion::where("ejercio_voto", true)->whereHas("centroVotacion.parroquia", function($q) use($municipio_id){

            $q->where("municipio_id", $municipio_id);

        })->count();

        $participacion = ParticipacionCentroVotacion::whereHas("mesa.centroVotacion.parroquia", function($q) use($municipio_id){

            $q->where("municipio_id", $municipio_id);

        })->sum("cantidad");

        return ["movilizacion" => $movilizacion, "participacion" => $participacion];
    
    }

    function todasParroquiasGraficas($municipio_id){

        $estadisticas = [];
        $parroquias = Parroquia::where("municipio_id", $municipio_id)->get();

        foreach($parroquias as $parroquia){        

            $parroquia_id = $parroquia->id;
            $movilizacion = Votacion::where("ejercio_voto", true)->whereHas("centroVotacion", function($q) use($parroquia_id){

                $q->where("parroquia_id", $parroquia_id);

            })->count();

            $participacion = ParticipacionCentroVotacion::whereHas("mesa.centroVotacion", function($q) use($parroquia_id){
                $q->where("parroquia_id", $parroquia_id);
            })->sum("cantidad");

            $estadisticas[] = [
                "nombre" => $parroquia->nombre,
                "movilizacion" => $movilizacion,
                "participacion" => $participacion
            ];

        }

        return $estadisticas;

    }


    function selectedAll(){

        $movilizacion = Votacion::where("ejercio_voto", true)->count();
        $participacion = ParticipacionCentroVotacion::sum("cantidad");

        return ["movilizacion" => $movilizacion, "participacion" => $participacion];
    
    }


    

    function todosMunicipiosGraficas(){

        $estadisticasMunicipio = [];

        foreach(Municipio::all() as $municipio){        

            $municipio_id = $municipio->id;
            $movilizacion = Votacion::where("ejercio_voto", true)->whereHas("centroVotacion.parroquia", function($q) use($municipio_id){

                $q->where("municipio_id", $municipio_id);

            })->count();

            $participacion = ParticipacionCentroVotacion::whereHas("mesa.centroVotacion.parroquia", function($q) use($municipio_id){
                $q->where("municipio_id", $municipio_id);
            })->sum("cantidad");

            $estadisticasMunicipio[] = [
                "nombre" => $municipio->nombre,
                "movilizacion" => $movilizacion,
                "participacion" => $participacion
            ];

        }

        return $estadisticasMunicipio;

    }

}
