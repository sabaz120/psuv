<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Request as EntityRequest;
use Spatie\Permission\Models\Permission as Model;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            //Filters
            $search = $request->input('search');
            $roles = $request->input('roles');
            $includes= $request->input('includes') ? $request->input('includes') : [];
            //Init query
            $query=Model::query();
            //Includes
            $query->with($includes);
            if ($search) {
                $query->where("name", "LIKE", "%".$search."%");
            }

            $this->addFilters($request, $query);

            $response = $this->getSuccessResponse(
               $query,
                'Listado de permisos',
                $request->input('page')
            );
        } catch (\Exception $e) {
            $code = $this->getCleanCode($e);
            $response= $this->getErrorResponse($e, 'Error al Listar los permisos');
        }
        return $this->response($response, $code ?? 200);
    }
}
