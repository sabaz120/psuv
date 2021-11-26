<?php

namespace App\Http\Controllers\Api\Reportes;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\InstitucionList;
class InstitucionController extends Controller
{
    public function institutionList(Request $request){
        $municipio_nombre = $request->input('municipio_nombre');
        $parroquia_nombre = $request->input('parroquia_nombre');
        $centro_votacion_nombre = $request->input('centro_votacion_nombre');
        $institucion_nombre = $request->input('institucion_nombre');
        $movilizacion_nombre = $request->input('movilizacion_nombre');
        $personal = $request->input('personal');
        $voto = $request->input('voto');
        $now=\Carbon\Carbon::now()->format('d-m-Y H:i:s');
        $excelName='Reporte movilización electoral_'.$now.'.xlsx';
        return Excel::download(new InstitucionList(
            $municipio_nombre,
            $parroquia_nombre,
            $centro_votacion_nombre,
            $personal,
            $voto,
            $institucion_nombre,
            $movilizacion_nombre
        ), $excelName);
    }//

}
