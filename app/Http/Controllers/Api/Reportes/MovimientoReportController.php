<?php

namespace App\Http\Controllers\Api\Reportes;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\MovimientoList;
class MovimientoReportController extends Controller
{
    public function movementList(Request $request){
        $municipio_nombre = $request->input('municipio_nombre');
        $parroquia_nombre = $request->input('parroquia_nombre');
        $centro_votacion_nombre = $request->input('centro_votacion_nombre');
        $movimiento_nombre = $request->input('movimiento_nombre');
        $movilizacion_nombre = $request->input('movilizacion_nombre');
        $personal = $request->input('personal');
        $voto = $request->input('voto');
        $now=\Carbon\Carbon::now()->format('d-m-Y H:i:s');
        $excelName='Reporte movilización electoral_'.$now.'.xlsx';
        return Excel::download(new MovimientoList(
            $municipio_nombre,
            $parroquia_nombre,
            $centro_votacion_nombre,
            $personal,
            $voto,
            $movimiento_nombre,
            $movilizacion_nombre
        ), $excelName);
    }//

}
