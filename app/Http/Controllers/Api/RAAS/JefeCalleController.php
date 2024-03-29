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
            $municipio_id = $request->input('municipio_id');
            $personal_caracterizacion_id = $request->input('personal_caracterizacion_id');
            $jefe_comunidad_id = $request->input('jefe_comunidad_id');
            $includes= $request->input('includes') ? $request->input('includes') : [
                "personalCaracterizacion.movilizacion",
                "personalCaracterizacion.partidoPolitico",
                "jefeComunidad.personalCaracterizacion",
                "jefeComunidad.comunidad",
                "jefeComunidad.comunidades",
                "calle.comunidad.centroVotacion.parroquia.municipio",
                "RolesNivelTerritorial.RolesEquipoPolitico",
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
            if ($municipio_id) {
                $query->whereHas("calle.comunidad.centroVotacion.parroquia", function($q) use($municipio_id){
                    $q->where('municipio_id', $municipio_id);
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
                //Create
                $elector=PersonalCaracterizacion::create([
                    "cedula"=>$elector->cedula??$data['personal_caraterizacion']["cedula"],
                    "nacionalidad"=>$elector->nacionalidad??$data['personal_caraterizacion']["nacionalidad"],
                    "primer_apellido"=>$elector->primer_apellido??$data['personal_caraterizacion']["primer_apellido"],
                    "segundo_apellido"=>$elector->segundo_apellido??"",
                    "primer_nombre"=>$elector->primer_nombre??$data['personal_caraterizacion']["primer_nombre"],
                    "segundo_nombre"=>$elector->segundo_nombre??"",
                    "sexo"=>$elector->sexo??$data['personal_caraterizacion']["sexo"],
                    "fecha_nacimiento"=>$elector->fecha_nacimiento??\Carbon\Carbon::now()->format("Y-m-d"),
                    "estado_id"=>$elector->estado_id??$data['personal_caraterizacion']["estado_id"],
                    "municipio_id"=>$elector->municipio_id??$data['personal_caraterizacion']["municipio_id"],
                    "parroquia_id"=>$elector->parroquia_id??$data['personal_caraterizacion']["parroquia_id"],
                    "centro_votacion_id"=>$elector->centro_votacion_id??$data['personal_caraterizacion']["centro_votacion_id"],
                    "telefono_principal"=>$request->telefono_principal,
                    "telefono_secundario"=>$request->telefono_secundario,
                    "tipo_voto"=>$request->tipo_voto,
                    "partido_politico_id"=>$request->partido_politico_id,
                    "movilizacion_id"=>$request->movilizacion_id,
                ]);
                $data['personal_caraterizacion_id']=$elector->id;
            }else{
                $data['personal_caraterizacion_id']=$elector->id;
                $elector->update([
                    "telefono_principal"=>$request->telefono_principal,
                    "telefono_secundario"=>$request->telefono_secundario,
                    "movilizacion_id"=>$request->movilizacion_id,
                    "tipo_voto"=>$request->tipo_voto,
                ]);
            }
            $rolEquipoPolitico=\App\Models\RolesEquipoPolitico::find($request->rol_equipo_politico_id);
            if(!$rolEquipoPolitico->rolNivelTerritorial){
                return response()->json(["success" => false, "msg" => "El rol seleccionado no posee un nivel territorial configurado"]);
            }
            $data['roles_nivel_territorial_id']=$rolEquipoPolitico->rolNivelTerritorial->id;
            //Cada calle solo puede tener un jefe de calle
            $exist=Model::where("calle_id",$data['calle_id'])
            ->where("roles_nivel_territorial_id",$rolEquipoPolitico->rolNivelTerritorial->id)
            ->first();
            if($exist){
                throw new \Exception('Esta calle, ya posee un jefe de calle (con este rol): '.$exist->personalCaracterizacion->full_name, 404);
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
                //if(!$elector){
                 //   throw new \Exception('Elector jefe de calle no encontrado.', 404);
                //}else{
                    //Create
                    $elector=PersonalCaracterizacion::create([
                        "cedula"=>$elector->cedula??$data['personal_caraterizacion']->cedula,
                        "nacionalidad"=>$elector->nacionalidad??$data['personal_caraterizacion']->nacionalidad,
                        "primer_apellido"=>$elector->primer_apellido??$data['personal_caraterizacion']->primer_apellido,
                        "segundo_apellido"=>$elector->segundo_apellido??"",
                        "primer_nombre"=>$elector->primer_nombre??$data['personal_caraterizacion']->primer_nombre,
                        "segundo_nombre"=>$elector->segundo_nombre??"",
                        "sexo"=>$elector->sexo??$data['personal_caraterizacion']->sexo,
                        "fecha_nacimiento"=>$elector->fecha_nacimiento??\Carbon\Carbon::now()->format("Y-m-d"),
                        "estado_id"=>$elector->estado_id??$data['personal_caraterizacion']->estado_id,
                        "municipio_id"=>$elector->municipio_id??$data['personal_caraterizacion']->municipio_id,
                        "parroquia_id"=>$elector->parroquia_id??$data['personal_caraterizacion']->parroquia_id,
                        "centro_votacion_id"=>$elector->centro_votacion_id??$data['personal_caraterizacion']->centro_votacion_id,
                        "telefono_principal"=>$request->telefono_principal,
                        "telefono_secundario"=>$request->telefono_secundario,
                        "tipo_voto"=>$request->tipo_voto,
                        "partido_politico_id"=>$request->partido_politico_id,
                        "movilizacion_id"=>$request->movilizacion_id,
                    ]);
                    $data['personal_caraterizacion_id']=$elector->id;
                //}   
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
            // $exist=Model::where('personal_caraterizacion_id',$elector->id)
            // ->where("calle_id",$data['calle_id'])
            // ->where("id","!=",$id)
            // ->first();
            // if($exist){
            //     throw new \Exception('Este elector ya ha sido registrado como jefe de esta calle.', 404);
            // }
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
            $response = $this->getSuccessResponse($entity,"Personal de calle eliminado");
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
            $query->with(
                'personalCaracterizacion',
                "calle",
                "calles.calle",
                "personalCaracterizacion.jefeFamiliaOwner"
            );
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

    public function importacionJefeCalle(Request $request){
        $jefeCalles = \Maatwebsite\Excel\Facades\Excel::toArray(new \App\Imports\BaseImport, $request->file('file'));
        $data['datos'] = $jefeCalles[0];
        $validation = \Validator::make($data, [
            "datos.*.nombre_comunidad" => 'required|string',
            "datos.*.nombre_calle" => "required|string",
            "datos.*.cedula_jefe_comunidad" => "required|numeric",
            "datos.*.cedula_persona" => 'required|numeric',
       ]);

       if ($validation->fails()) {
            return response()->json([
                "message"=>'Algunos datos en el excel no son validos',
                "errors"=>$validation->errors()
            ],400);
       }
       $response=[
           "importados"=>0,
           "errores"=>[]
       ];
       $data['datos']=json_decode(json_encode($data['datos']));
       foreach($data["datos"] as $jefe){
            $comunidad=\App\Models\Comunidad::where("nombre",$jefe->nombre_comunidad)->first();
            if(!$comunidad){
                $response["errores"][]=[
                    "datos"=>$jefe,
                    "motivo"=>"La comunidad: ".$jefe->nombre_comunidad." no existe"
                ];
                break;
            }
            $calle=\App\Models\Calle::where("nombre",$jefe->nombre_calle)
            ->where("comunidad_id",$comunidad->id)
            ->first();
            if(!$calle){
                $response["errores"][]=[
                    "datos"=>$jefe,
                    "motivo"=>"La calle: ".$jefe->nombre_calle." no existe"
                ];
                break;
            }
            $jefeComunidad=\App\Models\JefeComunidad::whereHas("personalCaracterizacion",function($query)use($jefe){
                $query->where("cedula",$jefe->cedula_jefe_comunidad);
            })
            ->where("comunidad_id",$comunidad->id)
            ->first();
            if(!$jefeComunidad){
                $response["errores"][]=[
                    "datos"=>$jefe,
                    "motivo"=>"El jefe de comunidad con la cédula: ".$jefe->cedula_jefe_comunidad." no existe"
                ];
                break;
            }
            $jefeCalle=\App\Models\JefeComunidad::whereHas("personalCaracterizacion",function($query)use($jefe){
                $query->where("cedula",$jefe->cedula_persona);
            })
            ->first();
            if(!$jefeCalle){
                $response["errores"][]=[
                    "datos"=>$jefe,
                    "motivo"=>"No existe personal registrado con esta cédula: ".$jefe->cedula_persona
                ];
                break;
            }
            $dataJefe=\App\Models\JefeCalle::where("calle_id",$calle->id)
            ->where("personal_caraterizacion_id",$jefeCalle->id)
            ->where("jefe_comunidad_id",$jefeComunidad->id)
            ->first();
            if($dataJefe){
                $response["errores"][]=[
                    "datos"=>$jefe,
                    "motivo"=>"Ya este jefe de calle se encuentra registrado para esta calle: ".$calle->nombre
                ];
                break;
            }
            $dataJefe=\App\Models\JefeCalle::create([
                "personal_caraterizacion_id"=>$jefeCalle->id,
                "calle_id"=>$calle->id,
                "jefe_comunidad_id"=>$jefeComunidad->id,
            ]);
            $response["importados"]=$response["importados"]+1;
        }//jefes foreach
        return response()->json([
            "data"=>$response,
            "message"=>"Jefes de calle importados exitosamente: ".$response["importados"]
        ],200);
    }

}
