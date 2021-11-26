<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Request as EntityRequest;
use Spatie\Permission\Models\Role as Model;
use DB;
class RoleController extends Controller
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
            $query->orderBy("created_at","DESC");
            $query=$query->paginate(15);
            return response()->json($query);

            $this->addFilters($request, $query);

            $response = $this->getSuccessResponse(
               $query,
                'Listado de roles',
                $request->input('page')
            );
        } catch (\Exception $e) {
            $code = $this->getCleanCode($e);
            $response= $this->getErrorResponse($e, 'Error al Listar los roles');
        }
        return $this->response($response, $code ?? 200);
    }

    public function store(EntityRequest $request)
    {
        try {
            DB::beginTransaction();
            //Get data
            $data=$request->all();
            //Create entity
            $entity=Model::create($data);
            //Sync permissions
            $entity->syncPermissions($request->permissions);
            DB::commit();
            $response = $this->getSuccessResponse($entity,"Registro de rol exitoso");
        } catch (\Exception $e) {
            DB::rollBack();
            $code = $this->getCleanCode($e);
            $response= $this->getErrorResponse($e, 'Registro de rol no exitoso');

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
                throw new \Exception('Rol no encontrado', 404);
            }
            //Update data
            $entity->update($data);
            //Sync permissions
            $entity->syncPermissions($request->permissions);
            DB::commit();
            $response = $this->getSuccessResponse($entity , 'Actualización exitosa' );
        } catch (\Exception $e) {
            DB::rollBack();
            $code = $this->getCleanCode($e);
            $response= $this->getErrorResponse($e, "La actualización de datos ha fallado");
        }//catch
        return $this->response($response, $code ?? 200);
    }//update()

    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $entity=Model::find($id);
            $this->validModel($entity, 'Rol no encontrado');
            $users=\App\Models\User::role($entity->name)->count();
            if($users>0){
                throw new \Exception('No puedes eliminar este rol, existe(n) '.$users.' usuario(s) que posee(n) este rol', 400);
            }
            $entity->delete();
            DB::commit();
            $response = $this->getSuccessResponse('', "Eliminación exitosa" );
        } catch (\Exception $e) {
            DB::rollBack();
            $code = $this->getCleanCode($e);
            $msg = "Ha ocurrido un error al intentar borrar el rol";
            $response= $this->getErrorResponse($e, $msg);
        }//catch
        return $this->response($response, $code ?? 200);
    }//destroy()
}
