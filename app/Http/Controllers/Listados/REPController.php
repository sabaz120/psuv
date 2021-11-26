<?php

namespace App\Http\Controllers\Listados;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Elector;
use App\Models\ExportJob;

use Rap2hpoutre\FastExcel\FastExcel;

class REPController extends Controller
{
    function download(Request $request){

        ini_set("memory_limit", -1);
    
        $data = null;
        if($request->selectedCentroVotacion != 0){
            $data = $this->selectedCentroVotacionREP($request->selectedCentroVotacion);
        }
        else if($request->selectedParroquia != 0){
      
            $data = $this->selectedParroquiaREP($request->selectedParroquia);
        }
        else if($request->selectedMunicipio != 0){
            $data = $this->selectedMunicipioREP($request->selectedMunicipio);
        }  
        

        return  (new FastExcel($data))->download('REP.xlsx', function ($user) {
            return [
                'NACIONALIDAD' => $user->nacionalidad,
                'CEDULA' => $user->cedula,
                'PRIMER APELLIDO' => $user->primer_apellido,
                'SEGUNDO APELLIDO' => $user->segundo_apellido,
                'PRIMER NOMBRE' => $user->primer_nombre,
                'SEGUNDO NOMBRE' => $user->segundo_nombre,
                'SEXO' => $user->fn,
                'ESTADO' => "FALCÓN",
                'MUNICIPIO' => $user->municipio->nombre,
                'PARROQUIA' => $user->parroquia->nombre,
                'CENTRO VOTACION' => $user->centroVotacion->nombre,
            ];
        });

    }

    function selectedCentroVotacionREP($selectedCentroVotacion){

        return Elector::where("centro_votacion_id", $selectedCentroVotacion)->with("municipio", "parroquia","centroVotacion")->get();

    }

    function selectedParroquiaREP($selectedParroquia){
        
        return Elector::where("parroquia_id", $selectedParroquia)->with("municipio", "parroquia","centroVotacion")->get();

    }

    function selectedMunicipioREP($selectedMunicipio){

        return Elector::where("municipio_id", $selectedMunicipio)->with("municipio", "parroquia","centroVotacion")->get();
    
    }

    function storeExportJob(Request $request){

        $job = new ExportJob;
        $job->entity = $request->entity;
        $job->entity_id = $request->entity_id;
        $job->pid = uniqid();
        $job->email = $request->email;
        $job->save();

        return response()->json(["success" => true, "msg" => "Proceso iniciado, le enviaremos un correo electrónico al finalizar"]);

    }
    

}
