<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\DB;
use App\Models\Comunidad as Model;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ComunidadesController extends Controller
{
   public function index( Request $request)
    {
        try {
            $parroquia_id = $request->input('parroquia_id');
            $municipio_id = $request->input('municipio_id');
            $centro_votacion_id = $request->input('centro_votacion_id');
            $auth = $request->input('auth');
            //Init query
            $query=Model::query();
            //Filters
            if ($centro_votacion_id) {
                $query->where('centro_votacion_id', $centro_votacion_id);
            }
            if ($parroquia_id) {
                $query->whereHas("centroVotacion", function($q) use($parroquia_id){
                    $q->where('parroquia_id', $parroquia_id);
                });
            }
            if($municipio_id){
                $query->whereHas('centroVotacion.parroquia',function($query) use($municipio_id){
                    $query->where("municipio_id",$municipio_id);
                });
            }
            $this->addFilters($request, $query);

            $response = $this->getSuccessResponse(
               $query,
                'Listado de comunidades',
                $request->input('page')
            );
        } catch (\Exception $e) {
            $code = $this->getCleanCode($e);
            $response= $this->getErrorResponse($e, 'Error al Listar los registros');
        }
        return $this->response($response, $code ?? 200);
    }//index()

}
