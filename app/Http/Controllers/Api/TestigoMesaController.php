<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Request as EntityRequest;
use App\Models\TestigoMesa as Model;
use App\Models\{PersonalCaracterizacion,Elector};
use DB;
class TestigoMesaController extends Controller
{
    public function index( Request $request)
    {
        try {
            //Filters
            $start_date = $request->input('start_date');
            $end_date = $request->input('end_date');
            $search = $request->input('search');
            $centro_votacion_id = $request->input('centro_votacion_id');
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
                //Get last eleccion
                $eleccion=\App\Models\Eleccion::orderBy('id','DESC')->first();
                $query->whereHas('mesa',function($query) use($centro_votacion_id,$eleccion){
                    $query->where("centro_votacion_id", $centro_votacion_id)
                    ->where('eleccion_id',$eleccion->id);
                });
            }

            // $query->orderBy("created_at","DESC");
            // $query=$query->paginate(15);
            // return response()->json($query);
            $this->addFilters($request, $query);

            $response = $this->getSuccessResponse(
               $query,
                'Listado de testigos',
                $request->input('page')
            );
        } catch (\Exception $e) {
            $code = $this->getCleanCode($e);
            $response= $this->getErrorResponse($e, 'Error al Listar los testigos');
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
                                  "tipo_voto"=>$data['personal_caracterizacion']['tipo_voto'],
                                  "partido_politico_id"=>$data['personal_caracterizacion']['partido_politico_id'],
                                  "movilizacion_id"=>$data['personal_caracterizacion']['movilizacion_id'],
                              ]);
                              $data['personal_caracterizacion_id']=$elector->id;
                          }   
                      }else{
                          $data['personal_caracterizacion_id']=$elector->id;
                          $elector->update([
                              "telefono_principal"=>$request->telefono_principal,
                              "telefono_secundario"=>$request->telefono_secundario
                          ]);
                      }
            $testigoExist=Model::where('personal_caracterizacion_id',$data['personal_caracterizacion_id'])->first();
            if($testigoExist){
                throw new \Exception('Este testigo ya se encuentra registrado en otra mesa', 404);
            }
            //Create entity
            $entity=Model::create($data);
            DB::commit();
            $response = $this->getSuccessResponse($entity,"Registro de testigo exitoso");
        } catch (\Exception $e) {
            DB::rollBack();
            $code = $this->getCleanCode($e);
            $response= $this->getErrorResponse($e, 'Registro de testigo no exitoso');

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
                throw new \Exception('Testigo no encontrado', 404);
            }
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
                                  "tipo_voto"=>$data['personal_caracterizacion']->tipo_voto,
                                  "partido_politico_id"=>$data['personal_caracterizacion']->partido_politico_id,
                                  "movilizacion_id"=>$data['personal_caracterizacion']->movilizacion_id,
                              ]);
                              $data['personal_caracterizacion_id']=$elector->id;
                          }   
                      }else{
                          $data['personal_caracterizacion_id']=$elector->id;
                          PersonalCaracterizacion::whereCedula($data['personal_caracterizacion']->cedula)->update([
                              "telefono_principal"=>$request->telefono_principal,
                              "telefono_secundario"=>$request->telefono_secundario
                          ]);
                      }//exist & update
                      $exist=Model::where('personal_caracterizacion_id',$data['personal_caracterizacion_id'])
                      ->where("id","!=",$id)
                      ->first();
                      if($exist){
                          throw new \Exception('Este testigo ya se encuentra registrado en otra mesa.', 404);
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
            $this->validModel($entity, 'Testigo no encontrado');
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
