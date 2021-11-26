<?php

namespace App\Http\Controllers\Api\Reportes;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JefeUbch;
use DB;
use Rap2hpoutre\FastExcel\FastExcel;

class ListadoController extends Controller
{
    
    function download(Request $request){

        $data = null;
        $name = "";

        if($request->type == "1"){
            
            $data = $this->jefeUbchType($request);
            $name = "ListadoJefeUBCH";

            return  (new FastExcel($data))->download($name.'.xlsx', function ($jefe) {
            
                return [
                    'MUNICIPIO' => $jefe->municipio,
                    'PARROQUIA' => $jefe->parroquia,
                    'UBCH' => $jefe->nombre_ubch,
                    'CEDULA' => $jefe->cedula,
                    'NOMBRE' => $jefe->jefe_ubch,
                    'TELEFONO PRINCIPAL' => $jefe->telefono1_jefe_ubch,
                    
                ];
            });
        }

        else if($request->type == "2"){
            
            $data = $this->jefeComunidadType($request);
            $name = "ListadoJefeComunidad";

            return  (new FastExcel($data))->download($name.'.xlsx', function ($jefe) {
            
                return [
                    'MUNICIPIO' => $jefe->municipio,
                    'PARROQUIA' => $jefe->parroquia,
                    'COMUNIDAD' => $jefe->comunidad,
                    'CEDULA' => $jefe->cedula_jefe_comunidad,
                    'NOMBRE' => $jefe->jefe_comunidad,
                    'TELEFONO PRINCIPAL' => $jefe->telefono1_jefe_comunidad,
                    
                ];
            });
        }

        else if($request->type == "3"){
            
            $data = $this->jefeCalleType($request);
            $name = "ListadoJefeCalle";

            return  (new FastExcel($data))->download($name.'.xlsx', function ($jefe) {
            
                return [
                    'MUNICIPIO' => $jefe->municipio,
                    'PARROQUIA' => $jefe->parroquia,
                    'COMUNIDAD' => $jefe->comunidad,
                    'CALLE' => $jefe->calle,
                    'CEDULA' => $jefe->cedula_jefe_calle,
                    'NOMBRE' => $jefe->jefe_calle,
                    'TELEFONO PRINCIPAL' => $jefe->telefono1_jefe_calle,
                    
                ];
            });
        }

    }

    function jefeUbchType($request){

        $condition = "";

        if($request->parroquia != "0"){
        
            $condition = ' AND pa.id='.$request->parroquia;
        }
        
        if($request->municipio != "0"){

            $condition .= ' AND mu.id='.$request->municipio;

        }  
       
        $data = DB::select("SELECT mu.nombre municipio, pa.nombre parroquia, cv.nombre as nombre_ubch, pc.cedula, (pc.primer_apellido||' '||primer_nombre) as jefe_ubch, telefono_principal telefono1_jefe_ubch
        FROM public.centro_votacion cv
        join public.jefe_ubch ju on cv.id=ju.centro_votacion_id
        join public.personal_caracterizacion pc on ju.personal_caracterizacion_id=pc.id
        join public.parroquia pa on pa.id=CV.parroquia_id
        join public.municipio mu on mu.id=pa.municipio_id
        WHERE ju.deleted_at::text is null ".$condition."
        order by mu.nombre, pa.nombre, cv.nombre;");

        return $data;
    }


    function jefeComunidadType($request){

        $condition = "";

        if($request->parroquia != "0"){
        
            $condition = ' AND pa.id='.$request->parroquia;
        }
        
        if($request->municipio != "0"){

            $condition .= ' AND mu.id='.$request->municipio;

        }  

        if($request->comunidad != "0"){
    
            $condition .= ' AND co.id='.$request->comunidad;

        }  

        
       
        $data = DB::select("SELECT mu.nombre municipio, pa.nombre parroquia, co.nombre comunidad, 
        (select cedula from public.personal_caracterizacion where id=jc.personal_caracterizacion_id) cedula_jefe_comunidad,
        (select (primer_apellido||' '||primer_nombre)from public.personal_caracterizacion where id=jc.personal_caracterizacion_id) jefe_comunidad, 
        (select telefono_principal from public.personal_caracterizacion where id=jc.personal_caracterizacion_id) telefono1_jefe_comunidad
          FROM public.centro_votacion cv
          join public.jefe_ubch ju on cv.id=ju.centro_votacion_id
          join public.personal_caracterizacion pc on ju.personal_caracterizacion_id=pc.id
          join public.jefe_comunidad jc on jc.jefe_ubch_id=ju.id
          join public.comunidad co on co.id=jc.comunidad_id
          join public.parroquia pa on pa.id=co.parroquia_id
          join public.municipio mu on mu.id=pa.municipio_id
          WHERE jc.deleted_at::text is null and ju.deleted_at::text is null ".$condition." 
          order by mu.nombre, pa.nombre, co.nombre;");
        return $data;
       

    }

    function jefeCalleType($request){

        $condition = "";

        if($request->parroquia != "0"){
        
            $condition = ' AND pa.id='.$request->parroquia;
        }
        
        if($request->municipio != "0"){

            $condition .= ' AND mu.id='.$request->municipio;

        }  

        if($request->comunidad != "0"){

            $condition .= ' AND co.id='.$request->comunidad;

        }  
       
        $data = DB::select("SELECT mu.nombre municipio, pa.nombre parroquia, co.nombre comunidad, ca.nombre calle, 
        (select cedula from public.personal_caracterizacion where id=jca.personal_caraterizacion_id) cedula_jefe_calle,
        (select (primer_apellido||' '||primer_nombre)from public.personal_caracterizacion where id=jca.personal_caraterizacion_id) jefe_calle, 
        (select telefono_principal from public.personal_caracterizacion where id=jca.personal_caraterizacion_id) telefono1_jefe_calle
          FROM public.centro_votacion cv
          join public.jefe_ubch ju on cv.id=ju.centro_votacion_id
          join public.personal_caracterizacion pc on ju.personal_caracterizacion_id=pc.id
          join public.jefe_comunidad jc on jc.jefe_ubch_id=ju.id
          join public.comunidad co on co.id=jc.comunidad_id
          join public.jefe_calle jca on jca.jefe_comunidad_id=jc.id
          join public.parroquia pa on pa.id=co.parroquia_id
          join public.calle ca on ca.id=jca.calle_id
          join public.municipio mu on mu.id=pa.municipio_id
          WHERE jc.deleted_at::text is null and ju.deleted_at::text is null ".$condition."
          order by mu.nombre, pa.nombre, co.nombre, ca.nombre;");

        return $data;
       

    }


}
