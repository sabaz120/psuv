<?php

namespace App\Http\Controllers\Api\Votaciones;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CentroVotacion;
use App\Models\PersonalCaracterizacion;
use App\Models\Votacion;
use App\Models\Eleccion;
use App\Models\Elector;
use App\Models\JefeUbch;
use App\Models\CuadernilloExportJob;
use App\Models\DescargaCuadernillo;
use DB;
use PDF;

class CuadernilloController extends Controller
{
    
    function centrosVotacion(Request $request){

        $centros = CentroVotacion::where("parroquia_id", $request->parroquia_id)->with("descargaCuadernillo")->with("parroquia", "parroquia.municipio")->get();
        return response()->json(["centros" => $centros]);

    }

    function generatePDF($centro_votacion_id){

        $this->cargarElectoresEnVotacion($centro_votacion_id);

        $electores = Votacion::where("centro_votacion_id", $centro_votacion_id)->with("elector")->get();
        $votaciones = $this->organizar($electores);
        $jefeUbch = JefeUbch::where("centro_votacion_id", $centro_votacion_id)->with("personalCaracterizacion")->first();
        $centroVotacion = CentroVotacion::with("parroquia", "parroquia.municipio")->find($centro_votacion_id);

        $descargaCuadernillo = new DescargaCuadernillo;
        $descargaCuadernillo->eleccion_id = $eleccion = Eleccion::orderBy("id", "desc")->first()->id;
        $descargaCuadernillo->centro_votacion_id = $centro_votacion_id;
        $descargaCuadernillo->descargado = true;
        $descargaCuadernillo->save();
        
        $pdf = PDF::loadView('pdf.cuadernillo.cuadernillo', ["votaciones" => $votaciones, "jefeUbch" => $jefeUbch, "centroVotacion" => $centroVotacion]);
        return $pdf->download('cuadernillo.pdf');

    }
    
    function generateUBCHPDF(Request $request){

        //dd($request->all());

        $data = DB::select(DB::raw(
            "SELECT cv.nombre as nombre_ubch, (select (primer_apellido||' '||primer_nombre)from public.personal_caracterizacion
            where id=ju.personal_caracterizacion_id) jefe_ubch, (select telefono_principal from public.personal_caracterizacion
            where id=ju.personal_caracterizacion_id) telefono_jefe_ubch, co.nombre comunidad, (select (primer_apellido||'
            '||primer_nombre)from public.personal_caracterizacion where id=jco.personal_caracterizacion_id) jefe_comunidad,
            calle.nombre calle, (select (primer_apellido||' '||primer_nombre)from public.personal_caracterizacion where
            id=jca.personal_caraterizacion_id) jefe_calle, (select (primer_apellido||' '||primer_nombre)from
            public.personal_caracterizacion where id=jfa.personal_caraterizacion_id) jefe_familia, pc.cedula cedula_familiar,
            (pc.primer_apellido||' '||pc.primer_nombre) as familiar FROM public.personal_caracterizacion pc join
            public.jefe_familia jfa on jfa.id=pc.jefe_familia_id join public.jefe_calle jca on jca.id=jfa.jefe_calle_id join
            public.calle on calle.id=jca.calle_id join public.jefe_comunidad jco on jco.id=jca.jefe_comunidad_id join
            public.comunidad co on co.id=jco.comunidad_id join public.jefe_ubch ju on ju.id=jco.jefe_ubch_id join
            public.centro_votacion cv on cv.id=ju.centro_votacion_id join public.parroquia pa on pa.id=cv.parroquia_id join
            public.municipio mu on mu.id=pa.municipio_id where mu.id='".$request->municipio_id."' and pa.id='".$request->parroquia_id."' and cv.id='".$request->centro_votacion_id."' order by comunidad,
            jefe_comunidad, calle, jefe_calle, jefe_familia, cedula_familiar;"
        ));

        $jefeUbch = JefeUbch::where("centro_votacion_id", $request->centro_votacion_id)->with("personalCaracterizacion")->first();
        $centroVotacion = CentroVotacion::with("parroquia", "parroquia.municipio")->find($request->centro_votacion_id);
        
        $pdf = PDF::loadView('pdf.cuadernillo.cuadernilloUBCH', ["data" => $data, "jefeUbch" => $jefeUbch, "centroVotacion" => $centroVotacion]);
        return $pdf->download('cuadernillo.pdf');

    }

    function cargarElectoresEnVotacion($centroVotacionId){

        if(Votacion::where("centro_votacion_id", $centroVotacionId)->count() > 0){
            return;
        }

        $eleccion = Eleccion::orderBy("id", "desc")->first();
        $electores = Elector::where("centro_votacion_id", $centroVotacionId)->orderBy("cedula", "asc")->get();

        $index = 1;
        foreach($electores as $elector){

            if(Votacion::where("elector_id", $elector->id)->count() == 0){

                $votacion = new Votacion;
                $votacion->codigo_cuadernillo = $index;
                $votacion->eleccion_id = $eleccion->id;
                $votacion->elector_id = $elector->id;
                $votacion->centro_votacion_id = $elector->centro_votacion_id;
                $votacion->save();

                $index++;
            }

        }

    }

    function organizar($electores){

        $votaciones = [];

        foreach($electores as $elector){

            $votaciones[] = [

                "codigo_cuadernillo" => $elector->codigo_cuadernillo,
                "cedula" => $elector->elector->cedula,
                "nombre_completo" => $elector->elector->primer_nombre." ".$elector->elector->primer_apellido,
                "caracterizacion" => PersonalCaracterizacion::where("nacionalidad", $elector->elector->nacionalidad)->where("cedula", $elector->elector->cedula)->count()

            ];

        }

        return $votaciones;

    }

    function countElectores($centroVotacionId){

        $eleccion = Eleccion::orderBy("id", "desc")->first();
        $electoresCount = Elector::where("centro_votacion_id", $centroVotacionId)->orderBy("cedula", "asc")->count();
        $descargado = DescargaCuadernillo::where("centro_votacion_id", $centroVotacionId)->where("eleccion_id", $eleccion->id)->first();
        return response()->json(["amount" => $electoresCount, "descargado" => $descargado]);

    }

    function storeExportJob(Request $request){

        $cuadernillo = new CuadernilloExportJob;
        $cuadernillo->centro_votacion_id = $request->centroVotacion;
        $cuadernillo->pid = uniqid();
        $cuadernillo->email = $request->email;
        $cuadernillo->save();

        return response()->json(["success" => true, "msg" => "Proceso iniciado, le enviaremos un correo electrónico al finalizar"]);

    }

}
