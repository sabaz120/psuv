<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\DB;
use App\Models\User as Model;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Users\UserRequest as EntityRequest;
use App\Http\Resources\UserCollection;

class UsersController extends Controller
{
   public function index( Request $request)
    {
        try {
            //Filters
            $start_date = $request->input('start_date');
            $end_date = $request->input('end_date');
            $search = $request->input('search');
            $roles = $request->input('roles');
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
            if($roles){
                is_array($roles) ? true : $roles = [$roles];
                $query->role($roles);
            }
            if ($search) {
                $query->where("name", "LIKE", "%".$search."%")
                ->orWhere("last_name", "LIKE", "%".$search."%");
            }

            $query->orderBy("created_at","DESC");
            $query=$query->paginate(15);
            return response()->json($query);
            // $this->addFilters($request, $query);

            $response = $this->getSuccessResponse(
               UserCollection::collection($query),
                'Listado de usuarios',
                $request->input('page')
            );
        } catch (\Exception $e) {
            $code = $this->getCleanCode($e);
            $response= $this->getErrorResponse($e, 'Error al Listar los documentos');
        }
        return $this->response($response, $code ?? 200);
    }//index()

    public function show($id)
    {
        try {
            $entity=Model::where('id' , $id)->first();
            if (!$entity) {
                throw new \Exception('Usuario no encontrado', 404);
            }
            $response = $this->getSuccessResponse(new UserCollection($entity));
        } catch (\Exception $e) {
            $code = $this->getCleanCode($e);
            $response= $this->getErrorResponse($e, 'Error al consultar el usuario');
        }//catch
        return $this->response($response, $code ?? 200);
    }//show()

    public function store(EntityRequest $request)
    {
        try {
            DB::beginTransaction();
            //Get data
            $data=$request->all();
            //Bcrypt pass
            $data['password']=bcrypt($data['password']);
            //Create entity
            $entity=Model::create($data);
            //Assign role to user entity
            $entity->assignRole($data['role_id']);
            //isset instituciones
            if(isset($data['instituciones'])){
                foreach($data['instituciones'] as $institucion){
                    $entity->instituciones()->create([
                        "institucion_id"=>$institucion
                    ]);
                }
            }
            //isset instituciones
            if(isset($data['movimientos'])){
                foreach($data['movimientos'] as $movimiento){
                    $entity->movimientos()->create([
                        "movimiento_id"=>$movimiento
                    ]);
                }
            }
            DB::commit();
            $response = $this->getSuccessResponse($entity,"Registro de usuario exitoso");
        } catch (\Exception $e) {
            DB::rollBack();
            $code = $this->getCleanCode($e);
            $response= $this->getErrorResponse($e, 'Registro de usuario no exitoso');

        }//catch
        return $this->response($response, $code ?? 200);
    }//

    public function update($id, EntityRequest $request)
    {
        try {
            DB::beginTransaction();
            //Get data
            $data=$request->all();
            //Bcrypt pass
            if(isset($data['password']) && $request->input('password'))
            $data['password']=bcrypt($data['password']);
            if(!isset($data['municipio_id'])){
                $data['municipio_id']=null;
            }
            //Find entity
            $entity=Model::find($id);
            if (!$entity) {
                throw new \Exception('Usuario no encontrado', 404);
            }
            //Update data
            $entity->update($data);
            //Sync role
            if(isset($data['role_id'])){
               //\Spatie\Permission\Models\Role
               $entity->syncRoles([$data['role_id']]);
            }
            //isset instituciones
            if(isset($data['instituciones'])){
                $entity->instituciones()->delete();
                foreach($data['instituciones'] as $institucion){
                    $entity->instituciones()->create([
                        "institucion_id"=>$institucion
                    ]);
                }
            }else{
                $entity->instituciones()->delete();
            }
            //isset instituciones
            if(isset($data['movimientos'])){
                $entity->movimientos()->delete();
                foreach($data['movimientos'] as $movimiento){
                    $entity->movimientos()->create([
                        "movimiento_id"=>$movimiento
                    ]);
                }
            }else{
                $entity->movimientos()->delete();
            }
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
            $this->validModel($entity, 'Usuario no encontrado');
            $entity->delete();
            DB::commit();
            $response = $this->getSuccessResponse('', "Eliminación exitosa" );
        } catch (\Exception $e) {
            DB::rollBack();
            $code = $this->getCleanCode($e);
            $msg = "Ha ocurrido un error al intentar borrar el usuario";
            $response= $this->getErrorResponse($e, $msg);
        }//catch
        return $this->response($response, $code ?? 200);
    }//destroy()
}
