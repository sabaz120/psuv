<?php

namespace App\Http\Controllers\Api\Reportes;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\RaasVoterMobilization;
use App\Exports\RassBaseStructure\{
    RaasStructure,
};
use App\Exports\RaasParticipation\ConstructSheets;
    
class RaasController extends Controller
{
    public function structure(Request $request){
        //Reporte estructura raas
        $municipio_nombre = $request->input('municipio_nombre');
        $parroquia_nombre = $request->input('parroquia_nombre');
        $now=\Carbon\Carbon::now()->format('d-m-Y H:i:s');
        $excelName='Reporte estructura de base_'.$now.'.xlsx';
        return Excel::download(new RaasStructure($municipio_nombre,$parroquia_nombre), $excelName);
    }//
    public function voterMobilization(Request $request){
        $municipio_nombre = $request->input('municipio_nombre');
        $parroquia_nombre = $request->input('parroquia_nombre');
        $centro_votacion_nombre = $request->input('centro_votacion_nombre');
        $now=\Carbon\Carbon::now()->format('d-m-Y H:i:s');
        $excelName='Reporte movilización electoral_'.$now.'.xlsx';
        return Excel::download(new RaasVoterMobilization($municipio_nombre,$parroquia_nombre,$centro_votacion_nombre), $excelName);
    }//
    public function exportToExcel( Request $request)
    {
        try {
            $now=\Carbon\Carbon::now()->format('d-m-Y H:i:s');
            return Excel::download(new PersonalCaracterizacionExport($request),'Reporte RAAS '.$now.".xlsx");
        } catch (\Exception $e) {
            abort(404);
            // $code = $this->getCleanCode($e);
            // $response= $this->getErrorResponse($e, 'Error al listar los registros');
        }
    }//index()
    public function participation(Request $request){
        //Reporte estructura raas
        $type = $request->input('type');
        $municipio_id = $request->input('municipio_id');
        $parroquia_id = $request->input('parroquia_id');
        $comunidad_id = $request->input('comunidad_id');
        $calle_id = $request->input('calle_id');
        $now=\Carbon\Carbon::now()->format('d-m-Y H:i:s');
        $excelName='Reporte_participacion_'.$now.'.xlsx';
        return Excel::download(new ConstructSheets(
            $type,
            $municipio_id,
            $parroquia_id,
            $comunidad_id,
            $calle_id
        ), $excelName);
    }//
}
