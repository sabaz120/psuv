<?php

namespace App\Http\Controllers\Api\MetasUBCH;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MetasUbch;

use Rap2hpoutre\FastExcel\FastExcel;

class MetasUBCHController extends Controller
{
    
    function download(Request $request){

        ini_set("memory_limit", -1);
    
        $data = null;
        /*if($request->selectedCentroVotacion != 0){
            $data = $this->selectedCentroVotacionREP($request->selectedCentroVotacion);
        }*/
        if($request->selectedParroquia != 0){
      
            $data = $this->selectedParroquiaMetas($request->selectedParroquia);
        }
        else if($request->selectedMunicipio != 0){
            $data = $this->selectedMunicipioMetas($request->selectedMunicipio);
        }else{
            $data = $this->allMetas();
        }  
        

        return  (new FastExcel($data))->download('MetasUBCH.xlsx', function ($meta) {
            return [
                'MUNICIPIO' => $meta->municipio->nombre,
                'PARROQUIA' => $meta->parroquia->nombre,
                'CENTRO VOTACION' => $meta->centroVotacion->nombre,
                'META' => $meta->meta,
            ];
        });

    }

    /*function selectedCentroVotacionREP($selectedCentroVotacion){

        return Elector::where("centro_votacion_id", $selectedCentroVotacion)->with("municipio", "parroquia","centroVotacion")->get();

    }*/

    function selectedParroquiaMetas($selectedParroquia){
        
        return MetasUBCH::where("parroquia_id", $selectedParroquia)->with("municipio", "parroquia","centroVotacion")->get();

    }

    function selectedMunicipioMetas($selectedMunicipio){

        return MetasUBCH::where("municipio_id", $selectedMunicipio)->with("municipio", "parroquia","centroVotacion")->get();
    
    }

    function allMetas(){

        return MetasUBCH::with("municipio", "parroquia","centroVotacion")->get();
    
    }

}
