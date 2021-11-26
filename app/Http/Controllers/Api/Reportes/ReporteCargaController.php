<?php

namespace App\Http\Controllers\Api\Reportes;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CentroVotacion;
use App\Models\MetasUbch;
use App\Models\PersonalCaracterizacion;
use App\Models\Municipio;
use App\Models\Parroquia;
use Rap2hpoutre\FastExcel\FastExcel;

class ReporteCargaController extends Controller
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

    function selectedCentroVotacion($centroVotacion){

        $metas = MetasUbch::where("centro_votacion_id", $centroVotacion)->sum("meta");
        $personalCaracterizacion = PersonalCaracterizacion::where("centro_votacion_id", $centroVotacion)->count();

        $centroVotacionMetas = CentroVotacion::where("id", $centroVotacion)->with("metasUbchs", "personalCaracterizacions", "parroquia", "parroquia.municipio")->orderBy("nombre")->paginate(20);

        return ["metas" => $metas, "personalCaracterizacion" => $personalCaracterizacion, "centroVotacionMetas" => $centroVotacionMetas];

    }

    function selectedParroquia($parroquia){
        
        $metas = MetasUbch::where("parroquia_id", $parroquia)->sum("meta");
        $personalCaracterizacion = PersonalCaracterizacion::where("parroquia_id", $parroquia)->count();

        $centroVotacionMetas = CentroVotacion::where("parroquia_id", $parroquia)->with("metasUbchs", "personalCaracterizacions", "parroquia", "parroquia.municipio")->orderBy("nombre")->paginate(20);

        return ["metas" => $metas, "personalCaracterizacion" => $personalCaracterizacion, "centroVotacionMetas" => $centroVotacionMetas];

    }

    function selectedMunicipio($municipio){

        $metas = MetasUbch::where("municipio_id", $municipio)->sum("meta");
        $personalCaracterizacion = PersonalCaracterizacion::where("municipio_id", $municipio)->count();

        $centroVotacionMetas = CentroVotacion::with("metasUbchs", "personalCaracterizacions", "parroquia", "parroquia.municipio")->whereHas("parroquia", function($q) use($municipio){
            $q->where("municipio_id", $municipio);  
        })->orderBy("nombre")->paginate(20);

        return ["metas" => $metas, "personalCaracterizacion" => $personalCaracterizacion, "centroVotacionMetas" => $centroVotacionMetas];
    
    }

    function selectedAll(){

        $metas = MetasUbch::sum("meta");
        $personalCaracterizacion = PersonalCaracterizacion::count();

        $centroVotacionMetas = CentroVotacion::with("metasUbchs", "personalCaracterizacions",  "parroquia", "parroquia.municipio")->orderBy("nombre")->paginate(20);

        return ["metas" => $metas, "personalCaracterizacion" => $personalCaracterizacion, "centroVotacionMetas" => $centroVotacionMetas];
    
    }

    function download(Request $request){

        ini_set("memory_limit", -1);

        $data = null;

        if($request->centroVotacion != "0"){
     
            $data = $this->selectedCentroVotacionDownload($request->centroVotacion);
        }
        else if($request->parroquia != "0"){
            
            $data = $this->selectedParroquiaDownload($request->parroquia);
        }
        else if($request->municipio != "0"){
      
            $data = $this->selectedMunicipioDownload($request->municipio);
        }  
        else if($request->municipio == "0"){
            
            $data = $this->selectedAllDownload();
        }  

        return  (new FastExcel($data))->download('ReporteCarga.xlsx', function ($meta) {
            
            return [
                'MUNICIPIO' => $meta->parroquia->municipio->nombre,
                'PARROQUIA' => $meta->parroquia->nombre,
                'CENTRO VOTACION' => $meta->nombre,
                'META' => $meta->metasUbchs[0]->meta,
                'CARGA' => count($meta->personalCaracterizacions),
                'PENDIENTE' => $meta->metasUbchs[0]->meta - count($meta->personalCaracterizacions)
            ];
        });

    }

    function selectedCentroVotacionDownload($centroVotacion){

        $centroVotacionMetas = CentroVotacion::where("id", $centroVotacion)->with("metasUbchs", "personalCaracterizacions", "parroquia", "parroquia.municipio")->orderBy("nombre")->get();

        return$centroVotacionMetas;

    }

    function selectedParroquiaDownload($parroquia){
        

        $centroVotacionMetas = CentroVotacion::where("parroquia_id", $parroquia)->with("metasUbchs", "personalCaracterizacions", "parroquia", "parroquia.municipio")->orderBy("nombre")->get();

        return $centroVotacionMetas;

    }

    function selectedMunicipioDownload($municipio){

        $centroVotacionMetas = CentroVotacion::with("metasUbchs", "personalCaracterizacions", "parroquia", "parroquia.municipio")->whereHas("parroquia", function($q) use($municipio){
            $q->where("municipio_id", $municipio);  
        })->orderBy("nombre")->get();

        return $centroVotacionMetas;
    
    }

    function selectedAllDownload(){

        $centroVotacionMetas = CentroVotacion::with("metasUbchs", "personalCaracterizacions",  "parroquia", "parroquia.municipio")->orderBy("nombre")->get();

        return $centroVotacionMetas;
    
    }

    function todosMunicipiosGraficas(){

        $estadisticasMunicipio = [];

        foreach(Municipio::all() as $municipio){    

            $metasPorMunicipio = MetasUbch::where("municipio_id", $municipio->id)->sum("meta");
            $cargadasPorMunicipio = PersonalCaracterizacion::where("municipio_id", $municipio->id)->count();
            $estadisticasMunicipio[] = [
                "nombre" => $municipio->nombre,
                "meta" => $metasPorMunicipio,
                "cargados" => $cargadasPorMunicipio
            ];

        }

        return $estadisticasMunicipio;

    }

    function todasParroquiasGraficas($municipio_id){

        $estadisticasMunicipio = [];

        foreach(Parroquia::where("municipio_id", $municipio_id)->get() as $parroquia){    

            $metasPorParroquia = MetasUbch::where("parroquia_id", $parroquia->id)->sum("meta");
            $cargadasPorParroquia = PersonalCaracterizacion::where("parroquia_id", $parroquia->id)->count();
            $estadisticasParroquia[] = [
                "nombre" => $parroquia->nombre,
                "meta" => $metasPorParroquia,
                "cargados" => $cargadasPorParroquia
            ];

        }

        return $estadisticasParroquia;

    }

    function todosCentroVotacionGraficas($parroquia_id){

        $estadisticasCentroVotacion = [];

        foreach(CentroVotacion::where("parroquia_id", $parroquia_id)->get() as $centroVotacion){    

            $metasPorCentroVotacion = MetasUbch::where("centro_votacion_id", $centroVotacion->id)->sum("meta");
            $cargadasPorCentroVotacion = PersonalCaracterizacion::where("centro_votacion_id", $centroVotacion->id)->count();
            $estadisticasCentroVotacion[] = [
                "nombre" => $centroVotacion->nombre,
                "meta" => $metasPorCentroVotacion,
                "cargados" => $cargadasPorCentroVotacion
            ];

        }

        return $estadisticasCentroVotacion;

    }

    function search(Request $request){

        $query = null;

        if($request->centroVotacion != "0"){
     
            $query->where("municipio_id", $request->municipio_id)->where("parroquia_id", $request->parroquia_id)->where("id", $request->centroVotacion);
        }
        else if($request->parroquia != "0"){
            
            $query->where("municipio_id", $request->municipio_id)->where("parroquia_id", $request->parroquia_id);
        }
        else if($request->municipio != "0"){
      
            $data = $this->selectedMunicipioDownload($request->municipio);
        }  
        else if($request->municipio == "0"){
            
            $data = $this->selectedAllDownload();
        }  

    }

}
