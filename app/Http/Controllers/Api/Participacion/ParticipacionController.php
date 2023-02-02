<?php

namespace App\Http\Controllers\Api\Participacion;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\PersonalCaracterizacionTrait;
use App\Models\PersonalCaracterizacion;
use App\Models\Elector;
use App\Models\{
    ParticipacionCalleRol,
    ParticipacionComunidadRol,
    ParticipacionUbchRol
};

use DB;
class ParticipacionController extends Controller
{
    use PersonalCaracterizacionTrait;
    
    public function index(Request $request){
        $tipo=$request->input("tipo");
        $estado_id=$request->input("estado_id");
        $municipio_id=$request->input("municipio_id");
        $parroquia_id=$request->input("parroquia_id");
        $centro_votacion_id=$request->input("centro_votacion_id");
        $parroquia_id=$request->input("parroquia_id");
        $calle_id=$request->input("calle_id");
        $comunidad_id=$request->input("comunidad_id");
        $query=null;
        if($tipo=="Comunidad"){
            $query=ParticipacionComunidadRol::query();
            if($estado_id){
                $query->whereHas("comunidad.centroVotacion.parroquia.municipio",function($q)use($estado_id){
                    $q->where("estado_id",$estado_id);
                });
            }
            if($municipio_id){
                $query->whereHas("comunidad.centroVotacion.parroquia",function($q)use($municipio_id){
                    $q->where("municipio_id",$municipio_id);
                });
            }
            if($parroquia_id){
                $query->whereHas("comunidad.centroVotacion",function($q)use($parroquia_id){
                    $q->where("parroquia_id",$parroquia_id);
                });
            }
            if($centro_votacion_id){
                $query->whereHas("comunidad",function($q)use($centro_votacion_id){
                    $q->where("centro_votacion_id",$centro_votacion_id);
                });
            }
            if($comunidad_id){
                $query->where("comunidad_id",$comunidad_id);
            }

        }else if($tipo=="UBCH"){
            $query=ParticipacionUbchRol::query();
            if($estado_id){
                $query->whereHas("centroVotacion.parroquia.municipio",function($q)use($estado_id){
                    $q->where("estado_id",$estado_id);
                });
            }
            if($municipio_id){
                $query->whereHas("centroVotacion.parroquia",function($q)use($municipio_id){
                    $q->where("municipio_id",$municipio_id);
                });
            }
            if($parroquia_id){
                $query->whereHas("centroVotacion",function($q)use($parroquia_id){
                    $q->where("parroquia_id",$parroquia_id);
                });
            }
            if($centro_votacion_id){
                $query->where("centro_votacion_id",$centro_votacion_id);
            }
        }else{
            $query=ParticipacionCalleRol::query();
            if($estado_id){
                $query->whereHas("calle.comunidad.centroVotacion.parroquia.municipio",function($q)use($estado_id){
                    $q->where("estado_id",$estado_id);
                });
            }
            if($municipio_id){
                $query->whereHas("calle.comunidad.centroVotacion.parroquia",function($q)use($municipio_id){
                    $q->where("municipio_id",$municipio_id);
                });
            }
            if($parroquia_id){
                $query->whereHas("calle.comunidad.centroVotacion",function($q)use($parroquia_id){
                    $q->where("parroquia_id",$parroquia_id);
                });
            }
            if($centro_votacion_id){
                $query->whereHas("calle.comunidad",function($q)use($centro_votacion_id){
                    $q->where("centro_votacion_id",$centro_votacion_id);
                });
            }
            if($comunidad_id){
                $query->whereHas("calle",function($q)use($comunidad_id){
                    $q->where("comunidad_id",$comunidad_id);
                });
            }
            if($calle_id){
                $query->where("calle_id",$calle_id);
            }
        }
        $query->with("personalCaracterizacion.centroVotacion");
        $result=$query->get();
        $response = $this->getSuccessResponse($result);
        return $this->response($response, 200);        
    }

    function store(Request $request){

        try {
            DB::beginTransaction();
            //Get data
            $data=$request->all();
            if(!isset($data['fecha_participacion'])){
                $data['fecha_participacion']=\Carbon\Carbon::now()->format("Y-m-d");
            }
            //Operations
            $elector=PersonalCaracterizacion::whereCedula($data['cedula'])->first();
            if(!$elector){
                $elector=Elector::whereCedula($data['cedula'])->first();
                //Create
                $elector=PersonalCaracterizacion::create([
                    "cedula"=>$elector->cedula??$data["cedula"],
                    "nacionalidad"=>$elector->nacionalidad??$data["nacionalidad"],
                    "primer_apellido"=>$elector->primer_apellido??$data["primer_apellido"],
                    "segundo_apellido"=>$elector->segundo_apellido??"",
                    "primer_nombre"=>$elector->primer_nombre??$data["primer_nombre"],
                    "segundo_nombre"=>$elector->segundo_nombre??"",
                    "sexo"=>$elector->sexo??$data["sexo"],
                    "fecha_nacimiento"=>$elector->fecha_nacimiento??\Carbon\Carbon::now()->format("Y-m-d"),
                    "estado_id"=>$elector->estado_id??$data["estado_id"],
                    "municipio_id"=>$elector->municipio_id??$data["municipio_id"],
                    "parroquia_id"=>$elector->parroquia_id??$data["parroquia_id"],
                    "centro_votacion_id"=>$elector->centro_votacion_id??$data["elector_centro_votacion_id"],
                    "telefono_principal"=>$request->telefono_principal,
                    "telefono_secundario"=>$request->telefono_secundario??"",
                    "tipo_voto"=>$request->tipo_voto,
                    "partido_politico_id"=>$request->partido_politico_id,
                    "movilizacion_id"=>$request->movilizacion_id,
                ]);
                $data['personal_caracterizacion_id']=$elector->id;
            }else{
                $data['personal_caracterizacion_id']=$elector->id;
                $elector->update([
                    "telefono_principal"=>$request->telefono_principal,
                    "telefono_secundario"=>$request->telefono_secundario??"",
                    "movilizacion_id"=>$request->movilizacion_id,
                    "tipo_voto"=>$request->tipo_voto,
                ]);
            }
            if($request->tipo=="UBCH"){
                $exist=ParticipacionUbchRol::where("personal_caracterizacion_id",$data['personal_caracterizacion_id'])
                ->where("centro_votacion_id",$data['centro_votacion_id'])
                ->where("fecha_participacion",$data['fecha_participacion'])
                ->first();
                if($exist){
                    throw new \Exception('Este elector ya participó en esta fecha en la UBCH seleccionada', 400);
                }
                $entity=ParticipacionUbchRol::create($data);
            }else if($request->tipo=="Comunidad"){
                $exist=ParticipacionComunidadRol::where("personal_caracterizacion_id",$data['personal_caracterizacion_id'])
                ->where("fecha_participacion",$data['fecha_participacion'])
                ->where("comunidad_id",$data['comunidad_id'])
                ->first();
                if($exist){
                    throw new \Exception('Este elector ya participo en esta fecha en la comunidad seleccionada', 400);
                }
                $entity=ParticipacionComunidadRol::create($data);
            }else{
                $exist=ParticipacionCalleRol::where("personal_caracterizacion_id",$data['personal_caracterizacion_id'])
                ->where("fecha_participacion",$data['fecha_participacion'])
                ->where("calle_id",$data['calle_id'])
                ->first();
                if($exist){
                    throw new \Exception('Este elector ya participo en esta fecha en la calle seleccionada', 400);
                }
                $entity=ParticipacionCalleRol::create($data);
            }
            DB::commit();
            $entity->load("personalCaracterizacion.centroVotacion");
            $response = $this->getSuccessResponse($entity,"Registro exitoso");
        } catch (\Exception $e) {
            DB::rollBack();
            $code = $this->getCleanCode($e);
            $response= $this->getErrorResponse($e, 'Registro no exitoso');

        }//catch
        return $this->response($response, $code ?? 200);        

    }

    function delete(Request $request){

        try {
            DB::beginTransaction();
            $tipo=$request->input("tipo");
            $id_participacion=$request->input("id_participacion");
            //Find entity
            if($tipo=="UBCH"){
                $entity=ParticipacionUbchRol::find($id_participacion);
            }else if($tipo=="Comunidad"){
                $entity=ParticipacionComunidadRol::find($id_participacion);
            }else{
                $entity=ParticipacionCalleRol::find($id_participacion);
            }
            if (!$entity) {
                throw new \Exception('Registro no encontrado', 404);
            }
            $entity->delete();
            DB::commit();
            $response = $this->getSuccessResponse($entity,"Registro eliminado exitosamente");
        } catch (\Exception $e) {
            DB::rollBack();
            $code = $this->getCleanCode($e);
            $response= $this->getErrorResponse($e, 'Eliminación no exitosa');

        }//catch
        return $this->response($response, $code ?? 200);        

    }

}
