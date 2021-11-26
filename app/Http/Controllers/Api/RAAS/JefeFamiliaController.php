<?php

namespace App\Http\Controllers\api\RAAS;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JefeFamilia as Model;
use App\Models\PersonalCaracterizacion;
use App\Models\Elector;
use App\Http\Requests\RAAS\JefeFamilia\StoreRequest as StoreRequest;
use App\Http\Requests\RAAS\JefeFamilia\UpdateRequest as UpdateRequest;
use App\Traits\PersonalCaracterizacionTrait;

use DB;
class JefeFamiliaController extends Controller
{
    use PersonalCaracterizacionTrait;

    public function index( Request $request)
    {
        try {
            $search = $request->input('search');
            $personal_caracterizacion_id = $request->input('personal_caracterizacion_id');
            $jefe_calle_id = $request->input('jefe_calle_id');
            $jefe_calle_municipio_id = $request->input('jefe_calle_municipio_id');
            $includes= $request->input('includes') ? $request->input('includes') : [
                "JefeCalle.personalCaracterizacion",
                "JefeCalle.calles.calle",
                "personalCaracterizacion.movilizacion",
                "personalCaracterizacion.partidoPolitico"
            ];
            //Init query
            $query=Model::query();
            //Includes
            $query->with($includes);
            //Filters
            if ($personal_caracterizacion_id) {
                $query->where('personal_caracterizacion_id', $personal_caracterizacion_id);
            }
            if ($jefe_calle_id) {
                $query->where('jefe_calle_id', $jefe_calle_id);
            }
            if ($jefe_calle_municipio_id) {
                $query->whereHas('JefeCalle.JefeComunidad.comunidad.parroquia', function($query) use($jefe_calle_municipio_id){
                    $query->where('municipio_id',$jefe_calle_municipio_id);
                });
            }
            if ($search) {
                $query->whereHas('personalCaracterizacion',function($query) use($search){
                    $query->where("cedula","LIKE","%{$search}%")
                    ->orWhere("primer_nombre","LIKE","%{$search}%")
                    ->orWhere("primer_apellido","LIKE","%{$search}%")
                    ->orWhere("segundo_nombre","LIKE","%{$search}%")
                    ->orWhere("segundo_apellido","LIKE","%{$search}%");
                });
            }
            $query->withCount('familiares');
            // $this->addFilters($request, $query);
            
            $query->orderBy("created_at","DESC");
            $query=$query->paginate(15);
            return response()->json($query);

            // $response = $this->getSuccessResponse(
            //    $query,
            //     'Listado de calles',
            //     $request->input('page')
            // );
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
                    throw new \Exception('Elector jefe de familia no encontrado.', 404);
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
                    $data['personal_caraterizacion_id']=$elector->id;
                }   
            }else{
                $data['personal_caraterizacion_id']=$elector->id;
            }
            //validate exist
            $exist=Model::where('personal_caraterizacion_id',$elector->id)->first();
            if($exist){
                throw new \Exception('Este jefe de familia ya fue registrado en otra ocasión.', 400);
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
                throw new \Exception('Jefe de familia no encontrado', 404);
            }
            //Get data
            $data=$request->all();
            //Operations
            $data['personal_caracterizacion']=json_decode($data['personal_caracterizacion']);
            $elector=PersonalCaracterizacion::whereCedula($data['personal_caracterizacion']->cedula)->first();
            if(!$elector){
                $elector=Elector::whereCedula($data['personal_caracterizacion']->cedula)->first();
                if(!$elector){
                    throw new \Exception('Elector jefe de familia no encontrado.', 404);
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
                    $data['personal_caraterizacion_id']=$elector->id;
                }   
            }else{
                $data['personal_caraterizacion_id']=$elector->id;
                PersonalCaracterizacion::whereCedula($data['personal_caracterizacion']->cedula)->update([
                    "telefono_principal"=>$request->telefono_principal,
                    "telefono_secundario"=>$request->telefono_secundario,
                    "tipo_voto"=>$request->tipo_voto,
                    "partido_politico_id"=>$request->partido_politico_id,
                    "movilizacion_id"=>$request->movilizacion_id,
                ]);
            }//exist & update
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
                throw new \Exception('Jefe de familia no encontrado', 404);
            }
            //Preguntar validación: Si ya existe el mismo jefe de calle para esta calle.
            if(count($entity->familiares)>0){
                throw new \Exception('Este jefe de familia posee 1 o más familiares asignados, por favor reasignar o eliminar los familiares para proceder a eliminar este registro.', 404);
            }
            //Create entity
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
                throw new \Exception('Jefe de Calle no encontrado', 404);
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
            $jefe_familia_id = $request->input('jefe_familia_id');
            $includes= $request->input('includes') ? $request->input('includes') : [
                "movilizacion",
                "partidoPolitico"
            ];
            //Init query
            $query=PersonalCaracterizacion::query();
            //Includes
            $query->with($includes);
            //Filters
            if ($jefe_familia_id) {
                $query->where('jefe_familia_id', $jefe_familia_id);
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
            if(!isset($request->jefe_familia_id)){
                throw new \Exception('Debe indicar el jefe de familia.', 400);
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
                        "movilizacion_id"=>$request->movilizacion_id,
                        "jefe_familia_id"=>$request->jefe_familia_id
                    ]);
                }   
            }else{
                if($elector->jefe_familia_id){
                    throw new \Exception('Este familiar ya forma parte de un núcleo familiar.', 404);
                }
                $elector->update([
                    "telefono_principal"=>$request->telefono_principal,
                    "telefono_secundario"=>$request->telefono_secundario,
                    "tipo_voto"=>$request->tipo_voto,
                    "partido_politico_id"=>$request->partido_politico_id,
                    "movilizacion_id"=>$request->movilizacion_id,
                    "jefe_familia_id"=>$request->jefe_familia_id  
                ]);
            }
            DB::commit();
            $response = $this->getSuccessResponse($elector,"Registro exitoso");
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
            $elector=PersonalCaracterizacion::where("id",$familyId)->first();
            if(!$elector){
                throw new \Exception('Este familiar no se encuentra en base de datos.', 404);
            }else{
                $elector->update([
                    "telefono_principal"=>$request->telefono_principal,
                    "telefono_secundario"=>$request->telefono_secundario,
                    "tipo_voto"=>$request->tipo_voto,
                    "partido_politico_id"=>$request->partido_politico_id,
                    "movilizacion_id"=>$request->movilizacion_id 
                ]);
            }
            DB::commit();
            $response = $this->getSuccessResponse($elector,"Actualización exitosa");
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
            $entity=PersonalCaracterizacion::find($familyId);
            if (!$entity) {
                throw new \Exception('Familiar no encontrado en base de datos.', 404);
            }
            $entity->jefe_familia_id=null;
            $entity->update();
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
