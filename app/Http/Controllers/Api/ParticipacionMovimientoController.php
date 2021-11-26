<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ParticipacionMovimiento as Model;
use App\Models\PersonalCaracterizacion;
use App\Models\Elector;
use App\Models\FamiliarMovimiento;
use App\Http\Requests\ParticipacionMovimiento\StoreRequest as StoreRequest;
use App\Http\Requests\ParticipacionMovimiento\UpdateRequest as UpdateRequest;

use DB;
class ParticipacionMovimientoController extends Controller
{

    public function index( Request $request)
    {
        try {
            $search = $request->input('search');
            $count_familiares = $request->input('count_familiares');
            $includes= $request->input('includes') ? $request->input('includes') : [
                "personalCaracterizacion.movilizacion",
            ];
            //Init query
            $query=Model::query();
            //Includes
            $query->with($includes);
            //Filters
            if ($search) {
                $query->whereHas('personalCaracterizacion',function($query) use($search){
                    $query->where("cedula","LIKE","%{$search}%");
                });
            }
            if($count_familiares){
                $query->withCount("familiares");
            }
            
            $query->orderBy("created_at","DESC");
            $query=$query->paginate(15);
            return response()->json($query);

        } catch (\Exception $e) {
            $code = $this->getCleanCode($e);
            $response= $this->getErrorResponse($e, 'Error al Listar los registros');
        }
        return $this->response($response, $code ?? 200);
    }//index()

    function store(StoreRequest $request){

        try {
            DB::beginTransaction();
            //Get data
            $data=$request->all();
            //Operations
            $elector=PersonalCaracterizacion::whereCedula($data['personal_caracterizacion']['cedula'])->first();
            if(!$elector){
                $elector=Elector::whereCedula($data['personal_caracterizacion']['cedula'])->first();
                if(!$elector){
                    throw new \Exception('Elector trabajador no encontrado.', 404);
                }else{
                    //Create
                    $elector=PersonalCaracterizacion::create([
                        "cedula"=>$elector->cedula,
                        "nacionalidad"=>$elector->nacionalidad,
                        "primer_apellido"=>$elector->primer_apellido,
                        "segundo_apellido"=>$elector->segundo_apellido,
                        "primer_nombre"=>$elector->primer_nombre,
                        "segundo_nombre"=>$elector->segundo_nombre,
                        "sexo"=>$elector->sexo,
                        "fecha_nacimiento"=>$elector->fecha_nacimiento,
                        "estado_id"=>$elector->estado_id,
                        "municipio_id"=>$elector->municipio_id,
                        "parroquia_id"=>$elector->parroquia_id,
                        "centro_votacion_id"=>$elector->centro_votacion_id,
                        "telefono_principal"=>$request->telefono_principal,
                        "telefono_secundario"=>$request->telefono_secundario,
                        "tipo_voto"=>$request->tipo_voto,
                        "partido_politico_id"=>$request->partido_politico_id,
                        "movilizacion_id"=>$request->movilizacion_id,
                    ]);
                    $data['personal_caracterizacion_id']=$elector->id;
                }   
            }else{
                $data['personal_caracterizacion_id']=$elector->id;
                $elector->update([
                    "telefono_principal"=>$request->telefono_principal,
                    "telefono_secundario"=>$request->telefono_secundario,
                    "tipo_voto"=>$request->tipo_voto,
                    "partido_politico_id"=>$request->partido_politico_id,
                    "movilizacion_id"=>$request->movilizacion_id,
                ]);
            }
            //
            $exist=Model::where('personal_caracterizacion_id',$data['personal_caracterizacion_id'])
            ->where('movimiento_id',$data['movimiento_id'])
            ->first();
            if($exist){
                throw new \Exception('Este elector ya ha sido registrado como trabajador de este movimiento.', 404);
            }
            //Create entity
            $entity=Model::create($data);
            DB::commit();
            $response = $this->getSuccessResponse($entity,"Registro exitoso");
        } catch (\Exception $e) {
            DB::rollBack();
            $code = $this->getCleanCode($e);
            $response= $this->getErrorResponse($e, 'Registro no exitoso');

        }//catch
        return $this->response($response, $code ?? 200);        

    }

    function update($id,UpdateRequest $request){

        try {
            DB::beginTransaction();
            //Find entity
            $entity=Model::find($id);
            if (!$entity) {
                throw new \Exception('Trabajador no encontrado', 404);
            }
            //Preguntar validación: Si ya existe el mismo jefe de calle para esta calle.
            //Get data
            $data=$request->all();
            //Operations
            $data['personal_caracterizacion']=json_decode($data['personal_caracterizacion']);
            $elector=PersonalCaracterizacion::whereCedula($data['personal_caracterizacion']->cedula)->first();
            if(!$elector){
                $elector=Elector::whereCedula($data['personal_caracterizacion']->cedula)->first();
                if(!$elector){
                    throw new \Exception('Elector trabajador no encontrado.', 404);
                }else{
                    //Create
                    $elector=PersonalCaracterizacion::create([
                        "cedula"=>$elector->cedula,
                        "nacionalidad"=>$elector->nacionalidad,
                        "primer_apellido"=>$elector->primer_apellido,
                        "segundo_apellido"=>$elector->segundo_apellido,
                        "primer_nombre"=>$elector->primer_nombre,
                        "segundo_nombre"=>$elector->segundo_nombre,
                        "sexo"=>$elector->sexo,
                        "fecha_nacimiento"=>$elector->fecha_nacimiento,
                        "estado_id"=>$elector->estado_id,
                        "municipio_id"=>$elector->municipio_id,
                        "parroquia_id"=>$elector->parroquia_id,
                        "centro_votacion_id"=>$elector->centro_votacion_id,
                        "telefono_principal"=>$request->telefono_principal,
                        "telefono_secundario"=>$request->telefono_secundario,
                        "tipo_voto"=>$request->tipo_voto,
                        "partido_politico_id"=>$request->partido_politico_id,
                        "movilizacion_id"=>$request->movilizacion_id,
                    ]);
                    $data['personal_caracterizacion_id']=$elector->id;
                }   
            }else{
                $data['personal_caracterizacion_id']=$elector->id;
                PersonalCaracterizacion::whereCedula($data['personal_caracterizacion']->cedula)->update([
                    "telefono_principal"=>$request->telefono_principal,
                    "telefono_secundario"=>$request->telefono_secundario,
                    "tipo_voto"=>$request->tipo_voto,
                    "partido_politico_id"=>$request->partido_politico_id,
                    "movilizacion_id"=>$request->movilizacion_id,
                ]);
            }//exist & update
            $exist=Model::where('personal_caracterizacion_id',$elector->id)
            ->where('movimiento_id',$data['movimiento_id'])
            ->where("id","!=",$id)
            ->first();
            if($exist){
                throw new \Exception('Este elector ya ha sido registrado como trabajador de este movimiento.', 404);
            }
            //Create entity
            $entity->update($data);
            DB::commit();
            $response = $this->getSuccessResponse($entity,"Actualización exitosa");
        } catch (\Exception $e) {
            DB::rollBack();
            $code = $this->getCleanCode($e);
            $response= $this->getErrorResponse($e, 'Actualización no exitosa');

        }//catch
        return $this->response($response, $code ?? 200);        

    }

    
    function delete($id,Request $request){

        try {
            DB::beginTransaction();
            //Find entity
            $entity=Model::find($id);
            if (!$entity) {
                throw new \Exception('Trabajador no encontrado', 404);
            }
            if(count($entity->familiares)>0){
                throw new \Exception('Este trabajador posee 1 o más familiares registrados, por favor eliminar los familiares para proceder a borrar este registro.', 404);
            }
            //Delete entity
            $entity->delete();
            DB::commit();
            $response = $this->getSuccessResponse($entity,"Eliminación exitosa");
        } catch (\Exception $e) {
            DB::rollBack();
            $code = $this->getCleanCode($e);
            $response= $this->getErrorResponse($e, 'Eliminación no exitosa');

        }//catch
        return $this->response($response, $code ?? 200);        

    }

    public function searchByCedulaField($cedula){
        try {
            //Init query
            $query=Model::query();
            //includes
            $query->with('personalCaracterizacion');
            if ($cedula) {
                $query->whereHas('personalCaracterizacion', function($q) use($cedula){
                    $q->where('cedula', $cedula);
                });
            }//cedula
            $entity=$query->first();
            if (!$entity) {
                throw new \Exception('Trabajador no encontrado', 404);
            }

            if(\Auth::user()->municipio_id != null){

                if($entity->personalCaracterizacion->municipio_id != \Auth::user()->municipio_id){
                    return response()->json(["success" => false, "msg" => "Éste trabajador no pertenece a tu municipio"]);
                }

            }

            $response = $this->getSuccessResponse($entity);
        } catch (\Exception $e) {
            $code = $this->getCleanCode($e);
            $response= $this->getErrorResponse($e, 'Error al Listar los registros');
        }
        return $this->response($response, $code ?? 200);
    }//searchByCedulaField()


    
    public function indexFamily( Request $request)
    {
        try {
            $personal_caracterizacion_id = $request->input('personal_caracterizacion_id');
            $participacion_movimiento_id = $request->input('participacion_movimiento_id');
            $includes= $request->input('includes') ? $request->input('includes') : [
                "personalCaracterizacion.movilizacion"
            ];
            //Init query
            $query=FamiliarMovimiento::query();
            //Includes
            $query->with($includes);
            //Filters
            if ($personal_caracterizacion_id) {
                $query->where('personal_caracterizacion_id', $personal_caracterizacion_id);
            }
            if ($participacion_movimiento_id) {
                $query->where('participacion_movimiento_id', $participacion_movimiento_id);
            }
            $this->addFilters($request, $query);
            
            $response = $this->getSuccessResponse(
               $query,
                'Listado de familiares',
                $request->input('page')
            );
        } catch (\Exception $e) {
            $code = $this->getCleanCode($e);
            $response= $this->getErrorResponse($e, 'Error al Listar los registros');
        }
        return $this->response($response, $code ?? 200);
    }//index()

    public function storeFamily(Request $request){
        try {
            DB::beginTransaction();
            $data=$request->all();
            if(!isset($request->participacion_movimiento_id)){
                throw new \Exception('Debe indicar el trabajador.', 400);
            }
            if(!isset($request->personal_caracterizacion)){
                throw new \Exception('Debe indicar el familiar a registrar.', 400);
            }
            //Operations
            $elector=PersonalCaracterizacion::whereCedula($data['personal_caracterizacion']['cedula'])->first();
            if(!$elector){
                $elector=Elector::whereCedula($data['personal_caracterizacion']['cedula'])->first();
                if(!$elector){
                    throw new \Exception('Datos del familiar no encontrados.', 404);
                }else{
                    //Create
                    $elector=PersonalCaracterizacion::create([
                        "cedula"=>$elector->cedula,
                        "nacionalidad"=>$elector->nacionalidad,
                        "primer_apellido"=>$elector->primer_apellido,
                        "segundo_apellido"=>$elector->segundo_apellido,
                        "primer_nombre"=>$elector->primer_nombre,
                        "segundo_nombre"=>$elector->segundo_nombre,
                        "sexo"=>$elector->sexo,
                        "fecha_nacimiento"=>$elector->fecha_nacimiento,
                        "estado_id"=>$elector->estado_id,
                        "municipio_id"=>$elector->municipio_id,
                        "parroquia_id"=>$elector->parroquia_id,
                        "centro_votacion_id"=>$elector->centro_votacion_id,
                        "telefono_principal"=>$request->telefono_principal,
                        "telefono_secundario"=>$request->telefono_secundario,
                        "tipo_voto"=>$request->tipo_voto,
                        "partido_politico_id"=>$request->partido_politico_id,
                        "movilizacion_id"=>$request->movilizacion_id
                    ]);
                }   
            }else{
                $elector->update([
                    "telefono_principal"=>$request->telefono_principal,
                    "telefono_secundario"=>$request->telefono_secundario,
                    "tipo_voto"=>$request->tipo_voto,
                    "partido_politico_id"=>$request->partido_politico_id,
                    "movilizacion_id"=>$request->movilizacion_id 
                ]);
            }
            $exist=FamiliarMovimiento::where('personal_caracterizacion_id',$elector->id)
            ->first();
            if($exist){
                throw new \Exception('Este elector ya es familiar de un trabajador.', 404);
            }
            $entity=FamiliarMovimiento::create([
                "participacion_movimiento_id"=>$request->participacion_movimiento_id,
                "personal_caracterizacion_id"=>$elector->id
            ]);

            DB::commit();
            $response = $this->getSuccessResponse($entity,"Registro exitoso");
        } catch (\Exception $e) {
            DB::rollBack();
            $code = $this->getCleanCode($e);
            $response= $this->getErrorResponse($e, 'Error al Listar los registros');
        }
        return $this->response($response, $code ?? 200);
    }//

    public function updateFamily($familyId,Request $request){
        try {
            DB::beginTransaction();
            $data=$request->all();
            //Operations
            $familiar=FamiliarMovimiento::where("id",$familyId)->first();
            if(!$familiar){
                throw new \Exception('Este familiar no se encuentra en base de datos.', 404);
            }else{
                $familiar->personalCaracterizacion()->update([
                    "telefono_principal"=>$request->telefono_principal,
                    "telefono_secundario"=>$request->telefono_secundario,
                    "tipo_voto"=>$request->tipo_voto,
                    "partido_politico_id"=>$request->partido_politico_id,
                    "movilizacion_id"=>$request->movilizacion_id 
                ]);
            }
            DB::commit();
            $response = $this->getSuccessResponse($familiar,"Actualización exitosa");
        } catch (\Exception $e) {
            DB::rollBack();
            $code = $this->getCleanCode($e);
            $response= $this->getErrorResponse($e, 'Error al actualizar');
        }
        return $this->response($response, $code ?? 200);
    }//

    function deleteFamily($familyId,Request $request){

        try {
            DB::beginTransaction();
            //Find entity
            $entity=FamiliarMovimiento::find($familyId);
            if (!$entity) {
                throw new \Exception('Familiar no encontrado en base de datos.', 404);
            }
            $entity->delete();
            DB::commit();
            $response = $this->getSuccessResponse($entity,"Eliminación exitosa");
        } catch (\Exception $e) {
            DB::rollBack();
            $code = $this->getCleanCode($e);
            $response= $this->getErrorResponse($e, 'Eliminación no exitosa');

        }//catch
        return $this->response($response, $code ?? 200);        

    }




}
