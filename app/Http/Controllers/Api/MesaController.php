<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Request as EntityRequest;
use App\Models\Mesa as Model;
use DB;
class MesaController extends Controller
{
    public function index( Request $request)
    {
        try {
            //Filters
            $start_date = $request->input('start_date');
            $end_date = $request->input('end_date');
            $search = $request->input('search');
            $centro_votacion_id = $request->input('centro_votacion_id');
            $last_election = $request->input('last_election');
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
                $query->where("numero_mesa", "LIKE", "%".$search."%");
            }
            if ($centro_votacion_id) {
                $query->where("centro_votacion_id", $centro_votacion_id);
            }
            if($last_election){
                //Get last eleccion
                $eleccion=\App\Models\Eleccion::orderBy('id','DESC')->first();
                $query->where("eleccion_id", $eleccion->id);
            }



            // $query->orderBy("created_at","DESC");
            // $query=$query->paginate(15);
            // return response()->json($query);
            $this->addFilters($request, $query);

            $response = $this->getSuccessResponse(
               $query,
                'Listado de mesas',
                $request->input('page')
            );
        } catch (\Exception $e) {
            $code = $this->getCleanCode($e);
            $response= $this->getErrorResponse($e, 'Error al Listar las mesas');
        }
        return $this->response($response, $code ?? 200);
    }//index()

    public function show($id)
    {
        try {
            $entity=Model::where('id' , $id)->first();
            if (!$entity) {
                throw new \Exception('Mesa no encontrado', 404);
            }
            $response = $this->getSuccessResponse($entity);
        } catch (\Exception $e) {
            $code = $this->getCleanCode($e);
            $response= $this->getErrorResponse($e, 'Error al consultar el Candidato');
        }//catch
        return $this->response($response, $code ?? 200);
    }//show()

    public function store(EntityRequest $request)
    {
        try {
            DB::beginTransaction();
            //Get data
            $data=$request->all();
            //Get last eleccion
            $eleccion=\App\Models\Eleccion::orderBy('id','DESC')->first();
            $data['eleccion_id']=$eleccion->id;
            //Create entity
            $entity=Model::create($data);
            DB::commit();
            $response = $this->getSuccessResponse($entity,"Registro de mesa exitoso");
        } catch (\Exception $e) {
            DB::rollBack();
            $code = $this->getCleanCode($e);
            $response= $this->getErrorResponse($e, 'Registro de mesa no exitoso');

        }//catch
        return $this->response($response, $code ?? 200);
    }//

    public function update($id, EntityRequest $request)
    {
        try {
            DB::beginTransaction();
            //Get data
            $data=$request->all();
            //Find entity
            $entity=Model::find($id);
            if (!$entity) {
                throw new \Exception('Mesa no encontrada', 404);
            }
            //Update data
            $entity->update($data);

            DB::commit();
            $response = $this->getSuccessResponse($entity , 'Actualización exitosa' );
        } catch (\Exception $e) {
            DB::rollBack();
            $code = $this->getCleanCode($e);
            $response= $this->getErrorResponse($e, "La actualización de datos ha fallado");
        }//catch
        return $this->response($response, $code ?? 200);
    }//update()

    public function delete($id)
    {
        try {
            DB::beginTransaction();
            $entity=Model::find($id);
            $this->validModel($entity, 'Mesa no encontrada');
            $entity->delete();
            DB::commit();
            $response = $this->getSuccessResponse('', "Eliminación exitosa" );
        } catch (\Exception $e) {
            DB::rollBack();
            $code = $this->getCleanCode($e);
            $msg = "Ha ocurrido un error al intentar borrar la mesa";
            $response= $this->getErrorResponse($e, $msg);
        }//catch
        return $this->response($response, $code ?? 200);
    }//destroy()


}
