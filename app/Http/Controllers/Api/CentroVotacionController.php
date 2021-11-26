<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CentroVotacion;
use App\Models\CentroVotacion as Model;

class CentroVotacionController extends Controller
{
    
    function centroVotacionByParroquia($parroquia_id){

        return response()->json(CentroVotacion::where("parroquia_id", $parroquia_id)->get());

    }

    function centroVotacionByParroquiaNombre($parroquia_nombre){

        return response()->json(CentroVotacion::whereHas("parroquia",function($query) use($parroquia_nombre){
            $query->where("nombre", $parroquia_nombre);
        })->get());

    }

    public function index( Request $request)
    {
        try {
            //Filters
            $start_date = $request->input('start_date');
            $end_date = $request->input('end_date');
            $search = $request->input('search');
            $municipio_id = $request->input('municipio_id');
            $mesasCount = $request->input('mesasCount');
            $electoresCount = $request->input('electoresCount');
            $includes= $request->input('includes') ? $request->input('includes') : [];
            //Init query
            $query=Model::query();
            //Includes
            $query->with($includes);
            //Filters
            if ($start_date) {
                $query->whereDate('created_at', '>=', $start_date);
            }
            if ($end_date) {
                $query->whereDate('created_at', '<=', $end_date);
            }
            if ($search) {
                $query->where("nombre", "LIKE", "%".$search."%");
            }
            if($mesasCount){
                $query->withCount('mesas');
            }
            if($electoresCount){
                $query->withCount('electores');
            }
            if($municipio_id){
                $query->whereHas('parroquia',function($query) use($municipio_id){
                    $query->where('municipio_id',$municipio_id);
                });
            }
            $query->orderBy("created_at","DESC");
            $query=$query->paginate(15);
            return response()->json($query);
            // $this->addFilters($request, $query);

            $response = $this->getSuccessResponse(
               $query,
                'Listado de candidatos',
                $request->input('page')
            );
        } catch (\Exception $e) {
            $code = $this->getCleanCode($e);
            $response= $this->getErrorResponse($e, 'Error al Listar los Candidatos');
        }
        return $this->response($response, $code ?? 200);
    }//index()

}
