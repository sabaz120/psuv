<?php

namespace App\Http\Controllers\Api\Votaciones;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Votacion;
use App\Models\CentroVotacion;
use App\Models\ReporteVoto;
use Carbon\Carbon;
use App\Models\PersonalPuntoRojo;
use Rap2hpoutre\FastExcel\FastExcel;

class CentroVotacionController extends Controller
{
    
    function getCentrosVotacion(Request $request){

        if($request->municipio_id == 0){
            $centros = CentroVotacion::withCount("electores")->withCount(['votaciones' => function($query) { 
                    $query->where('ejercio_voto', true); // without `order_id`
                }
            ])->with("parroquia", "parroquia.municipio", "jefeUbchs", "jefeUbchs.personalCaracterizacion")->with("metasUbchs")->paginate(20);
        
        }else{

            $municipio_id = $request->municipio_id;
            $centros = CentroVotacion::withCount("electores")->withCount(['votaciones' => function($query) { 
                    $query->where('ejercio_voto', true); // without `order_id`
                }
            ])->with("parroquia", "parroquia.municipio", "jefeUbchs", "jefeUbchs.personalCaracterizacion")->whereHas("parroquia", function($q) use($municipio_id){

                $q->where("municipio_id", $municipio_id);

            })->with("metasUbchs")->paginate(10);

        }
        

        return response()->json($centros);
    }

    function searchCentrosVotacion(Request $request){

        if($request->municipio_id == 0){
            $centros = CentroVotacion::withCount("electores")->withCount(['votaciones' => function($query) { 
                    $query->where('ejercio_voto', true); // without `order_id`
                }
            ])->where("nombre", "like","%".strtoupper($request->search)."%")->with("parroquia", "parroquia.municipio")->with("metasUbchs")->paginate(20);
        
        }else{

            $municipio_id = $request->municipio_id;
            $centros = CentroVotacion::withCount("electores")->withCount(['votaciones' => function($query) { 
                    $query->where('ejercio_voto', true); // without `order_id`
                }
            ])->where("nombre", "like","%".strtoupper($request->search)."%")->with("parroquia", "parroquia.municipio")->whereHas("parroquia", function($q) use($municipio_id){

                $q->where("municipio_id", $municipio_id);

            })->with("metasUbchs")->paginate(10);

        }
        

        return response()->json($centros);

    }

    function searchByCodigoCuadernillo(Request $request){

        $elector = Votacion::where("codigo_cuadernillo", $request->codigoCuadernillo)->where("centro_votacion_id", $request->centroVotacionId)->with("elector")->first();
        if($elector){
            return response()->json(["success" => true, "elector" => $elector]);
        }else{  
            return response()->json(["success" => false, "msg" => "Elector no encontrado"]);
        }
    
    }

    function searchByCedula(Request $request){

        $cedula = $request->cedula;
        $elector = Votacion::whereHas("elector", function($q) use($cedula){

            $q->where("cedula", $cedula);

        })->where("centro_votacion_id", $request->centroVotacionId)->with("elector")->first();
        
        if($elector){
            return response()->json(["success" => true, "elector" => $elector]);
        }else{  
            return response()->json(["success" => false, "msg" => "Elector no encontrado"]);
        }
    
    }

    function updateEjercioVoto(Request $request){

        $votacion = Votacion::where("codigo_cuadernillo", $request->codigoCuadernillo)->where("centro_votacion_id", $request->centroVotacionId)->first();
        
        if($votacion){
            if($votacion->ejercio_voto == false){
                $votacion->ejercio_voto = true;
                $votacion->hora = Carbon::now()->format('H:i');
                $votacion->update();
            }

            return response()->json(["success" => true, "msg" => "Voto registrado"]);

        }else{  
            return response()->json(["success" => false, "msg" => "Elector no encontrado"]);
        }

    }

    function updateEjercioVotoInstitucion(Request $request){    

        $cedula = $request->cedula;
        
        $votacion = Votacion::whereHas("elector", function($q) use($cedula){

            $q->where("cedula", $cedula);

        })->where("centro_votacion_id", $request->centroVotacionId)->first();
        
        if($votacion){

            if($votacion->ejercio_voto == false){
                $votacion->ejercio_voto = true;
                $votacion->hora = Carbon::now()->format('H:i');
                $votacion->update();
            }
            

            ReporteVoto::updateOrCreate(
                [
                    "votacion_id" => $votacion->id,
                    "reporta" => $request->institucion
                ],[
                    "votacion_id" => $votacion->id,
                    "reporta" => $request->institucion
                ]
            );

            return response()->json(["success" => true, "msg" => "Voto registrado"]);

        }else{  
            return response()->json(["success" => false, "msg" => "Elector no encontrado"]);
        }

    }

    function getVotantesByCentroVotacion(Request $request){

        $votantes = Votacion::where("centro_votacion_id", $request->centroVotacionId)->where("ejercio_voto", true)->with("elector","elector.centroVotacion")->orderBy("updated_at", "desc")->paginate(20);
        return response()->json($votantes);

    }

    function searchVotantesByCentroVotacion(Request $request){

        if($request->searchText == ""){

            $votantes = Votacion::where("centro_votacion_id", $request->centroVotacionId)->where("ejercio_voto", true)->with("elector","elector.centroVotacion")->paginate(20);

        }else{

            $votantes = Votacion::where("codigo_cuadernillo", $request->searchText)->where("centro_votacion_id", $request->centroVotacionId)->where("ejercio_voto", true)->with("elector","elector.centroVotacion")->paginate(20);

        }

        
        return response()->json($votantes);

    }

    function deleteVoto(Request $request){

        if(ReporteVoto::where("votacion_id", $request->id)->count() > 0){

            $reportes = ReporteVoto::where("votacion_id", $request->id)->get();
            foreach($reportes as $reporte){
                $reporte->delete();
            }

        }

        $votacion = Votacion::where("id", $request->id)->first();
        $votacion->ejercio_voto = false;
        $votacion->update();
        
        return response()->json(["success" => true, "msg" => "Voto eliminado"]);


    }

    function getPersonalPuntoRojo(Request $request){

        $personalPuntoRojo = PersonalPuntoRojo::where("centro_votacion_id", $request->centro_votacion_id)->with("centroVotacion")->paginate(20);
        return response()->json($personalPuntoRojo);

    }

    function searchPersonalPuntoRojo(Request $request){

        $personalPuntoRojo = PersonalPuntoRojo::where("centro_votacion_id", $request->centro_votacion_id)->where("nombre", "like", "%".strtoupper($request->search)."%")->with("centroVotacion")->paginate(20);
        return response()->json($personalPuntoRojo);

    }

    function exportPersonalPuntoRojo($centroVotacionId){

        $data = PersonalPuntoRojo::where("centro_votacion_id", $centroVotacionId)->with("centroVotacion")->get();

        return  (new FastExcel($data))->download('personalPuntoRojo.xlsx', function ($personal) {
            
            //dd($personal);
            return [
                'CV' => $personal->centroVotacion->nombre,
                'NOMBRE' => $personal->fullName,
                'TELÉFONO PRINCIPAL' => $personal->telefono_principal,
                'TELÉFONO SECUNDARIO' => $personal->telefono_secundario
            ];
        });


    }


}
