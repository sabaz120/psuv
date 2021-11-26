<?php

namespace App\Http\Controllers\api\RAAS;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JefeCalle as Model;
use App\Http\Requests\RAAS\JefeCalle\StoreRequest as StoreRequest;
use App\Http\Requests\RAAS\JefeCalle\UpdateRequest as UpdateRequest;
use App\Traits\PersonalCaracterizacionTrait;
use App\Models\PersonalCaracterizacion;
use App\Models\Elector;
use DB;
class JefeCalleController extends Controller
{
    use PersonalCaracterizacionTrait;

    public function index( Request $request)
    {
        try {
            $search = $request->input('search');
            $calle_id = $request->input('calle_id');
            $personal_caracterizacion_id = $request->input('personal_caracterizacion_id');
            $jefe_comunidad_id = $request->input('jefe_comunidad_id');
            $includes= $request->input('includes') ? $request->input('includes') : [
                "personalCaracterizacion.movilizacion",
                "personalCaracterizacion.partidoPolitico",
                "jefeComunidad.personalCaracterizacion",
                "jefeComunidad.comunidad",
                "jefeComunidad.comunidades",
                "calle"
            ];
            //Init query
            $query=Model::query();
            //Includes
            $query->with($includes);
            //Filters
            if ($calle_id) {
                $query->where('calle_id', $calle_id);
            }
            if ($personal_caracterizacion_id) {
                $query->where('personal_caracterizacion_id', $personal_caracterizacion_id);
            }
            if ($jefe_comunidad_id) {
                $query->where('jefe_comunidad_id', $jefe_comunidad_id);
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
            $elector=PersonalCaracterizacion::whereCedula($data['personal_caraterizacion']['cedula'])->first();
            if(!$elector){
                $elector=Elector::whereCedula($data['personal_caraterizacion']['cedula'])->first();
                if(!$elector){
                    throw new \Exception('Elector jefe de calle no encontrado.', 404);
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
            //
            $exist=Model::where('personal_caraterizacion_id',$elector->id)
            ->where("calle_id",$data['calle_id'])->first();
            if($exist){
                throw new \Exception('Este elector ya ha sido registrado como jefe de esta calle.', 404);
            }
            //Cada calle solo puede tener un jefe de calle
            $exist=Model::where("calle_id",$data['calle_id'])->first();
            if($exist){
                throw new \Exception('Esta calle, ya posee un jefe de calle: '.$exist->personalCaracterizacion->full_name, 404);
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
                throw new \Exception('Jefe de calle no encontrado', 404);
            }
            //Preguntar validación: Si ya existe el mismo jefe de calle para esta calle.
            //Get data
            $data=$request->all();
            //Operations
            $data['personal_caraterizacion']=json_decode($data['personal_caraterizacion']);
            $elector=PersonalCaracterizacion::whereCedula($data['personal_caraterizacion']->cedula)->first();
            if(!$elector){
                $elector=Elector::whereCedula($data['personal_caraterizacion']->cedula)->first();
                if(!$elector){
                    throw new \Exception('Elector jefe de calle no encontrado.', 404);
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
                PersonalCaracterizacion::whereCedula($data['personal_caraterizacion']->cedula)->update([
                    "telefono_principal"=>$request->telefono_principal,
                    "telefono_secundario"=>$request->telefono_secundario,
                    "tipo_voto"=>$request->tipo_voto,
                    "partido_politico_id"=>$request->partido_politico_id,
                    "movilizacion_id"=>$request->movilizacion_id,
                ]);
            }//exist & update
            $exist=Model::where('personal_caraterizacion_id',$elector->id)
            ->where("calle_id",$data['calle_id'])->where("id","!=",$id)->first();
            if($exist){
                throw new \Exception('Este elector ya ha sido registrado como jefe de esta calle.', 404);
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
                throw new \Exception('Jefe de calle no encontrado', 404);
            }
            //Preguntar validación: Si ya existe el mismo jefe de calle para esta calle.
            if(count($entity->jefeFamilias)>0){
                throw new \Exception('Este jefe de calle posee 1 o más jefes de familia asignados, por favor reasignar los jefes de familia a otro jefe de calle, para proceder a eliminar este', 404);
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

    // function suspend(JefeComunidadUpdateRequest $request){

    //     try{

    //         if($this->verificarDuplicidadCedula($request->cedula) > 0){
    //             return response()->json(["success" => false, "msg" => "Esta cédula ya pertenece a un Jefe de Comunidad"]);
    //         }

    //         $jefeComunidad = JefeComunidad::find($request->id);
    //         $jefeUbchId = $jefeComunidad->jefe_ubch_id;
    //         $comunidadId = $jefeComunidad->comunidad_id;
    //         $jefeComunidad->delete();

    //         $personalCaracterizacion = PersonalCaracterizacion::where("cedula", $request->cedula)->first();
            
    //         if($personalCaracterizacion == null){
    //             $personalCaracterizacion = $this->storePersonalCaracterizacion($request);
    //         }

    //         $jefeComunidad = new JefeComunidad;
    //         $jefeComunidad->personal_caracterizacion_id = $personalCaracterizacion->id;
    //         $jefeComunidad->comunidad_id = $comunidadId;
    //         $jefeComunidad->jefe_ubch_id = $jefeUbchId;
    //         $jefeComunidad->save();

    //         $personalCaracterizacion = $this->updatePersonalCaracterizacion($jefeComunidad->personal_caracterizacion_id, $request);

    //         return response()->json(["success" => true, "msg" => "Jefe de Comunidad suspendido y sustituido"]);

    //     }
    //     catch(\Exception $e){

    //         return response()->json(["success" => false, "msg" => "Ha ocurrido un problema", "err" => $e->getMessage(), "ln" => $e->getLine()]);

    //     }
        

    // }//suspend

    public function searchByCedulaField($cedula){
        try {
            //Init query
            $query=Model::query();
            //includes
            $query->with('personalCaracterizacion',"calle","calles.calle");
            if ($cedula) {
                $query->whereHas('personalCaracterizacion', function($q) use($cedula){
                    $q->where('cedula', $cedula);
                });
            }//cedula
            $entity=$query->first();
            if (!$entity) {
                throw new \Exception('Jefe de Calle no encontrado', 404);
            }

            if(\Auth::user()->municipio_id != null){

                if($entity->personalCaracterizacion->municipio_id != \Auth::user()->municipio_id){
                    return response()->json(["success" => false, "msg" => "Éste jefe de comunidad no pertenece a tu municipio"]);
                }

            }

            $response = $this->getSuccessResponse($entity);
        } catch (\Exception $e) {
            $code = $this->getCleanCode($e);
            $response= $this->getErrorResponse($e, 'Error al Listar los registros');
        }
        return $this->response($response, $code ?? 200);
    }//searchByCedulaField()

}
