<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PersonalComandoParroquial as Model;
use App\Models\PersonalCaracterizacion;
use App\Models\Elector;
use Illuminate\Http\Request as StoreRequest;
use Illuminate\Http\Request as UpdateRequest;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\Comandos\Parroquial as exportClassFile;
use DB;
class PersonalComandoParroquialController extends Controller
{

    public function index( Request $request)
    {
        try {
            $search = $request->input('search');
            $includes= $request->input('includes') ? $request->input('includes') : [
                "personalCaracterizacion",
            ];
            //Init query
            $query=Model::query();
            //Includes
            $query->with($includes);
            //Filters
            if ($search) {
                $query->whereHas('comisionTrabajo',function($query) use($search){
                    $query->where("nombre_comision","LIKE","%{$search}%");
                });
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
                    throw new \Exception('Elector no encontrado.', 404);
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
            ->first();
            if($exist){
                throw new \Exception('Este elector ya ha sido registrado para una comisión de trabajo.', 404);
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
            ->where("id","!=",$id)
            ->first();
            if($exist){
                throw new \Exception('Este elector ya ha sido registrado en una comisión de trabajo.', 404);
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

    public function excel(Request $request){
        $now=\Carbon\Carbon::now()->format('d-m-Y H:i:s');
        $excelName='Reporte_'.$now.'.xlsx';
        return Excel::download(new exportClassFile(), $excelName);
    }//
}
