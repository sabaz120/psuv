<?php

namespace App\Http\Controllers\Api\Reportes;

use App\Models\PersonalCaracterizacion as Model;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\RaasStructure;
use App\Exports\RaasVoterMobilization;
class RaasController extends Controller
{
    public function structure(Request $request){
        //Reporte estructura raas
        $municipio_nombre = $request->input('municipio_nombre');
        $parroquia_nombre = $request->input('parroquia_nombre');
        $now=\Carbon\Carbon::now()->format('d-m-Y H:i:s');
        $excelName='Reporte estructura RAAS_'.$now.'.xlsx';
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

}
