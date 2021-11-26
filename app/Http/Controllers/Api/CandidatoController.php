<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\DB;
use App\Models\Candidato as Model;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request as EntityRequest;
use Illuminate\Support\Str;
use App\Helpers\LoadFileHelper as LoadFile;
use App\Exports\Candidato as exportClassFile;
use Maatwebsite\Excel\Facades\Excel;

class CandidatoController extends Controller
{
   public function index( Request $request)
    {
        try {
            //Filters
            $start_date = $request->input('start_date');
            $end_date = $request->input('end_date');
            $search = $request->input('search');
            $municipio_id = $request->input('municipio_id');
            $cargo_eleccion = $request->input('cargo_eleccion');
            $not_pagination = $request->input('not_pagination');
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
                $query->where("nombre", "LIKE", "%".$search."%")
                ->orWhere("apellido", "LIKE", "%".$search."%");
            }
            if($municipio_id){
                $query->where('municipio_id',$municipio_id);
            }
            if($cargo_eleccion){
                $query->where('cargo_eleccion',$cargo_eleccion);
            }
            $query->orderBy("created_at","DESC");
            if(!$not_pagination){
                $query=$query->paginate(15);
                return response()->json($query);
            }
            $this->addFilters($request, $query);

            $response = $this->getSuccessResponse(
               $query,
                'Listado de candidatos',
                $request->input('page')
            );
        } catch (\Exception $e) {
            $code = $this->getCleanCode($e);
            $response= $this->getErrorResponse($e, 'Error al Listar los Candidatos');
        }
        return $this->response($response, $code ?? 200);
    }//index()

    public function show($id)
    {
        try {
            $entity=Model::where('id' , $id)->first();
            if (!$entity) {
                throw new \Exception('Candidato no encontrado', 404);
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
            $random = Str::random(40);
            $image=$request->foto;
            if ($this->startsWith($request->foto, 'data:image')) {
                //base 64
                $default_size = [
                    'imagesize' => [
                        'width' => 1024,
                        'height' => 768,
                        'quality'=>80
                    ]
                ];
                $size = json_decode(json_encode($default_size));

                // 0. Make the image
                $image = \Image::make($image);
                // resize and prevent possible upsizing

                $image->resize($size->imagesize->width, $size->imagesize->height, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });

                $endFile = 'jpg';
                if(Str::startsWith($request->foto, 'data:image/png;'))
                  $endFile = 'png';
                $destination_path="candidatos/".$random.".".$endFile;
                \Storage::disk("publicmedia")->put($destination_path, $image->stream($endFile, $size->imagesize->quality));
          
                $data['foto'] = $destination_path;
            }
            //Get last eleccion
            $eleccion=\App\Models\Eleccion::orderBy('id','DESC')->first();
            $data['eleccion_id']=$eleccion->id;
            //Validate
            $exist=Model::where('nombre',$data['nombre'])->first();
            if($exist){
                throw new \Exception('Este candidato ya se encuentra registrado', 404);
            }
            //Create entity
            $entity=Model::create($data);
            //isset partidos_politicos
            if(isset($data['partidos_politicos'])){
                foreach($data['partidos_politicos'] as $partido_politico_id){
                    $entity->partidos()->create([
                        "partido_politico_id"=>$partido_politico_id
                    ]);
                }
            }
            DB::commit();
            $response = $this->getSuccessResponse($entity,"Registro de candidato exitoso");
        } catch (\Exception $e) {
            DB::rollBack();
            $code = $this->getCleanCode($e);
            $response= $this->getErrorResponse($e, 'Registro de candidato no exitoso');

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
                throw new \Exception('Candidato no encontrado', 404);
            }
            //validate
            $exist=Model::where('nombre',$data['nombre'])
            ->where("id","!=",$id)
            ->first();
            if($exist){
                throw new \Exception('Este candidato ya se encuentra registrado.', 404);
            }
            //Update data
            $entity->update($data);
            //isset partidos_politicos
            if(isset($data['partidos_politicos'])){
                $entity->partidos()->delete();
                foreach($data['partidos_politicos'] as $partido_politico_id){
                    $entity->partidos()->create([
                        "partido_politico_id"=>$partido_politico_id
                    ]);
                }
            }else{
                $entity->partidos()->delete();
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

    public function delete($id)
    {
        try {
            DB::beginTransaction();
            $entity=Model::find($id);
            $this->validModel($entity, 'Candidato no encontrado');
            $entity->delete();
            DB::commit();
            $response = $this->getSuccessResponse('', "Eliminación exitosa" );
        } catch (\Exception $e) {
            DB::rollBack();
            $code = $this->getCleanCode($e);
            $msg = "Ha ocurrido un error al intentar borrar el Candidato";
            $response= $this->getErrorResponse($e, $msg);
        }//catch
        return $this->response($response, $code ?? 200);
    }//destroy()

    public function updateImage($id,EntityRequest $request)
    {
        try {
            DB::beginTransaction();
            //Get data
            $data=$request->all();
            $random = Str::random(40);
            $image=$request->foto;
            if ($this->startsWith($request->foto, 'data:image')) {
                //base 64
                $default_size = [
                    'imagesize' => [
                        'width' => 1024,
                        'height' => 768,
                        'quality'=>80
                    ]
                ];
                $size = json_decode(json_encode($default_size));

                // 0. Make the image
                $image = \Image::make($image);
                // resize and prevent possible upsizing

                $image->resize($size->imagesize->width, $size->imagesize->height, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });

                $endFile = 'jpg';
                if(Str::startsWith($request->foto, 'data:image/png;'))
                  $endFile = 'png';
                $destination_path="candidatos/".$random.".".$endFile;
                \Storage::disk("publicmedia")->put($destination_path, $image->stream($endFile, $size->imagesize->quality));
          
                $data['foto'] = $destination_path;
            }
            $entity=Model::where('id',$id)->update($data);
            DB::commit();
            $response = $this->getSuccessResponse($entity,"Actualización de imagen exitosa");
        } catch (\Exception $e) {
            DB::rollBack();
            $code = $this->getCleanCode($e);
            $response= $this->getErrorResponse($e, 'Registro de usuario no exitoso');

        }//catch
        return $this->response($response, $code ?? 200);
    }//

    function startsWith ($string, $startString)
    {
      $len = strlen($startString);
      return (substr($string, 0, $len) === $startString);
    }

    public function excel(Request $request){
        $now=\Carbon\Carbon::now()->format('d-m-Y H:i:s');
        $excelName='Reporte_'.$now.'.xlsx';
        return Excel::download(new exportClassFile(), $excelName);
    }//

    public function cierreCandidato(Request $request){
        $candidato_id=$request->input("candidato_id");
        $municipio_nombre=$request->input("municipio_nombre");
        $parroquia_nombre=$request->input("parroquia_nombre");
        $centro_votacion_nombre=$request->input("centro_votacion_nombre");
        $cargo_eleccion=$request->input("cargo_eleccion");
        $eleccion=\App\Models\Eleccion::orderBy('id','DESC')->first();
        $condition="eleccion.id={$eleccion->id}";
        if($candidato_id){
            $condition.=" AND ccv.candidatos_id={$candidato_id}";
        }
        if($municipio_nombre){
            $condition.=" AND mu.nombre='{$municipio_nombre}'";
        }
        if($parroquia_nombre){
            $condition.=" AND pa.nombre='{$parroquia_nombre}'";
        }
        if($centro_votacion_nombre){
            $condition.=" AND cv.nombre='{$centro_votacion_nombre}'";
        }
        if($cargo_eleccion){
            $condition.=" AND cargo_eleccion='{$cargo_eleccion}'";
        }
        $raw=DB::select(DB::raw("SELECT (candidatos.nombre||' '||candidatos.apellido) candidato, candidatos.id candidato_id,
        cargo_eleccion, sum(cantidad_voto) total_votos
        FROM public.cierre_candidato_votacion ccv
        join public.mesa on mesa.id=ccv.mesa_id
        join public.candidatos on candidatos.id=ccv.candidatos_id
        join public.centro_votacion cv on cv.id=mesa.centro_votacion_id
        join public.parroquia pa on pa.id=cv.parroquia_id
        join public.municipio mu on mu.id=pa.municipio_id
        join public.eleccion on eleccion.id=ccv.eleccion_id
        where {$condition}
        group by candidato, cargo_eleccion,candidato_id
        order by cargo_eleccion desc, total_votos desc"
        ));
        return response()->json($raw,200);

    }//cierreCandidato

    public function cierreCandidatoDetalle(Request $request){
        $candidato_id=$request->input("candidato_id");
        $tipo_detalle=$request->input("tipo_detalle");
        $municipio_nombre=$request->input("municipio_nombre");
        $parroquia_nombre=$request->input("parroquia_nombre");
        $centro_votacion_nombre=$request->input("centro_votacion_nombre");
        $eleccion=\App\Models\Eleccion::orderBy('id','DESC')->first();
        $condition="eleccion.id={$eleccion->id}";
        if($candidato_id){
            $condition.=" AND ccv.candidatos_id={$candidato_id}";
        }
        if($municipio_nombre){
            $condition.=" AND mu.nombre='{$municipio_nombre}'";
        }
        if($parroquia_nombre){
            $condition.=" AND pa.nombre='{$parroquia_nombre}'";
        }
        if($centro_votacion_nombre){
            $condition.=" AND cv.nombre='{$centro_votacion_nombre}'";
        }
        $raw=null;
        if($tipo_detalle=="municipio"){
            $raw=DB::select(DB::raw("SELECT mu.nombre categoria, (candidatos.nombre||' '||candidatos.apellido) candidato, 
            cargo_eleccion, sum(cantidad_voto) total_votos
            FROM public.cierre_candidato_votacion ccv
            join public.mesa on mesa.id=ccv.mesa_id
            join public.candidatos on candidatos.id=ccv.candidatos_id
            join public.centro_votacion cv on cv.id=mesa.centro_votacion_id
            join public.parroquia pa on pa.id=cv.parroquia_id
            join public.municipio mu on mu.id=pa.municipio_id
            join public.eleccion on eleccion.id=ccv.eleccion_id
            where {$condition}
            group by mu.nombre, candidato, cargo_eleccion
            order by mu.nombre;"
            ));
        }elseif($tipo_detalle=="parroquia"){
            $raw=DB::select(DB::raw("SELECT mu.nombre municipio, pa.nombre categoria, (candidatos.nombre||' '||candidatos.apellido) candidato, 
            cargo_eleccion, sum(cantidad_voto) total_votos
            FROM public.cierre_candidato_votacion ccv
            join public.mesa on mesa.id=ccv.mesa_id
            join public.candidatos on candidatos.id=ccv.candidatos_id
            join public.centro_votacion cv on cv.id=mesa.centro_votacion_id
            join public.parroquia pa on pa.id=cv.parroquia_id
            join public.municipio mu on mu.id=pa.municipio_id
            join public.eleccion on eleccion.id=ccv.eleccion_id
            where {$condition}
            group by mu.nombre, pa.nombre, candidato, cargo_eleccion
            order by mu.nombre, pa.nombre;"
            ));
        }elseif($tipo_detalle=="centro_votacion"){
            $raw=DB::select(DB::raw("SELECT mu.nombre municipio, pa.nombre parroquia, cv.nombre categoria, (candidatos.nombre||' '||candidatos.apellido) candidato, 
            cargo_eleccion, sum(cantidad_voto) total_votos
            FROM public.cierre_candidato_votacion ccv
            join public.mesa on mesa.id=ccv.mesa_id
            join public.candidatos on candidatos.id=ccv.candidatos_id
            join public.centro_votacion cv on cv.id=mesa.centro_votacion_id
            join public.parroquia pa on pa.id=cv.parroquia_id
            join public.municipio mu on mu.id=pa.municipio_id
            join public.eleccion on eleccion.id=ccv.eleccion_id
            where {$condition}
            group by mu.nombre, pa.nombre, cv.nombre, candidato, cargo_eleccion
            order by mu.nombre, pa.nombre, cv.nombre;"
            ));
        }else{
            //Mesa
            $raw=DB::select(DB::raw("SELECT mu.nombre municipio, pa.nombre parroquia, cv.nombre centro_votacion, (candidatos.nombre||' '||candidatos.apellido) candidato, 
            cargo_eleccion, numero_mesa, cantidad_voto total_votos
            FROM public.cierre_candidato_votacion ccv
            join public.mesa on mesa.id=ccv.mesa_id
            join public.candidatos on candidatos.id=ccv.candidatos_id
            join public.centro_votacion cv on cv.id=mesa.centro_votacion_id
            join public.parroquia pa on pa.id=cv.parroquia_id
            join public.municipio mu on mu.id=pa.municipio_id
            join public.eleccion on eleccion.id=ccv.eleccion_id
            where {$condition}
            group by mu.nombre, pa.nombre, cv.nombre, candidato, cargo_eleccion, numero_mesa, cantidad_voto
            order by mu.nombre, pa.nombre, cv.nombre, numero_mesa;"
            ));
        }
        return response()->json($raw,200);
    }//cierreCandidatoDetalle

    
    public function cierrePartido(Request $request){
        $candidato_id=$request->input("candidato_id");
        $municipio_nombre=$request->input("municipio_nombre");
        $parroquia_nombre=$request->input("parroquia_nombre");
        $centro_votacion_nombre=$request->input("centro_votacion_nombre");
        $cargo_eleccion=$request->input("cargo_eleccion");
        $eleccion=\App\Models\Eleccion::orderBy('id','DESC')->first();
        $condition="eleccion.id={$eleccion->id}";
        if($candidato_id){
            $condition.=" AND candidatos.id={$candidato_id}";
        }
        if($municipio_nombre){
            $condition.=" AND mu.nombre='{$municipio_nombre}'";
        }
        if($parroquia_nombre){
            $condition.=" AND pa.nombre='{$parroquia_nombre}'";
        }
        if($centro_votacion_nombre){
            $condition.=" AND cv.nombre='{$centro_votacion_nombre}'";
        }
        if($cargo_eleccion){
            $condition.=" AND cargo_eleccion='{$cargo_eleccion}'";
        }
        $raw=DB::select(DB::raw("SELECT eleccion.nombre comando_campaña, eleccion.nivel eleccion, (candidatos.nombre||' '||candidatos.apellido) candidato, candidatos.id candidato_id,
        cargo_eleccion, pp.nombre partido_politico, sum(cantidad_voto) total_votos
        FROM public.cierre_partido_votacion cpv
        join public.candidatos_partido_politico cpp on cpp.id=cpv.candidatos_partido_politico_id
        join public.mesa on mesa.id=cpv.mesa_id
        join public.candidatos on candidatos.id=cpp.candidatos_id
        join public.partido_politico pp on pp.id=cpp.partido_politico_id
        join public.centro_votacion cv on cv.id=mesa.centro_votacion_id
        join public.parroquia pa on pa.id=cv.parroquia_id
        join public.municipio mu on mu.id=pa.municipio_id
        join public.eleccion on eleccion.id=cpv.eleccion_id
            where {$condition}
        group by candidato, cargo_eleccion, pp.nombre, eleccion.nombre, eleccion.nivel,candidatos.id
        order by cargo_eleccion desc, total_votos desc;"
        ));
        return response()->json($raw,200);

    }//cierrePartido

    
    public function cierrePartidoDetalle(Request $request){
        $eleccion=\App\Models\Eleccion::orderBy('id','DESC')->first();
        $candidato_id=$request->input("candidato_id");
        $tipo_detalle=$request->input("tipo_detalle");
        $municipio_nombre=$request->input("municipio_nombre");
        $parroquia_nombre=$request->input("parroquia_nombre");
        $partido_politico_nombre=$request->input("partido_politico_nombre");
        $centro_votacion_nombre=$request->input("centro_votacion_nombre");
        $condition="eleccion.id={$eleccion->id}";
        if($candidato_id){
            $condition.=" AND candidatos.id={$candidato_id}";
        }
        if($municipio_nombre){
            $condition.=" AND mu.nombre='{$municipio_nombre}'";
        }
        if($parroquia_nombre){
            $condition.=" AND pa.nombre='{$parroquia_nombre}'";
        }
        if($partido_politico_nombre){
            $condition.=" AND pp.nombre='{$partido_politico_nombre}'";
        }

        $raw=null;
        if($tipo_detalle=="municipio"){
            $raw=DB::select(DB::raw("SELECT mu.nombre categoria, (candidatos.nombre||' '||candidatos.apellido) candidato, 
            cargo_eleccion, pp.nombre partido_politico, sum(cantidad_voto) total_votos
            FROM public.cierre_partido_votacion cpv
            join public.candidatos_partido_politico cpp on cpp.id=cpv.candidatos_partido_politico_id
            join public.mesa on mesa.id=cpv.mesa_id
            join public.candidatos on candidatos.id=cpp.candidatos_id
            join public.partido_politico pp on pp.id=cpp.partido_politico_id
            join public.centro_votacion cv on cv.id=mesa.centro_votacion_id
            join public.parroquia pa on pa.id=cv.parroquia_id
            join public.municipio mu on mu.id=pa.municipio_id
            join public.eleccion on eleccion.id=cpv.eleccion_id
            where {$condition}
            group by mu.nombre, candidato, cargo_eleccion, pp.nombre
            order by mu.nombre;"
            ));
        }elseif($tipo_detalle=="parroquia"){
            $raw=DB::select(DB::raw("SELECT mu.nombre municipio, pa.nombre categoria, (candidatos.nombre||' '||candidatos.apellido) candidato, 
            cargo_eleccion, pp.nombre partido_politico, sum(cantidad_voto) total_votos
            FROM public.cierre_partido_votacion cpv
            join public.candidatos_partido_politico cpp on cpp.id=cpv.candidatos_partido_politico_id
            join public.mesa on mesa.id=cpv.mesa_id
            join public.candidatos on candidatos.id=cpp.candidatos_id
            join public.partido_politico pp on pp.id=cpp.partido_politico_id
            join public.centro_votacion cv on cv.id=mesa.centro_votacion_id
            join public.parroquia pa on pa.id=cv.parroquia_id
            join public.municipio mu on mu.id=pa.municipio_id
            join public.eleccion on eleccion.id=cpv.eleccion_id
            where {$condition}
            group by mu.nombre, pa.nombre, candidato, cargo_eleccion, pp.nombre
            order by mu.nombre, pa.nombre asc;"
            ));
        }elseif($tipo_detalle=="centro_votacion"){
            $raw=DB::select(DB::raw("SELECT mu.nombre municipio, pa.nombre parroquia, cv.nombre categoria, (candidatos.nombre||' '||candidatos.apellido) candidato, 
            cargo_eleccion, pp.nombre partido_politico, sum(cantidad_voto) total_votos
            FROM public.cierre_partido_votacion cpv
            join public.candidatos_partido_politico cpp on cpp.id=cpv.candidatos_partido_politico_id
            join public.mesa on mesa.id=cpv.mesa_id
            join public.candidatos on candidatos.id=cpp.candidatos_id
            join public.partido_politico pp on pp.id=cpp.partido_politico_id
            join public.centro_votacion cv on cv.id=mesa.centro_votacion_id
            join public.parroquia pa on pa.id=cv.parroquia_id
            join public.municipio mu on mu.id=pa.municipio_id
            join public.eleccion on eleccion.id=cpv.eleccion_id
            where {$condition}
            group by mu.nombre, pa.nombre, cv.nombre, candidato, cargo_eleccion, pp.nombre
            order by mu.nombre, pa.nombre, cv.nombre asc;"
            ));
        }else{
            //Mesa
            $raw=DB::select(DB::raw("SELECT mu.nombre municipio, pa.nombre parroquia, cv.nombre centro_votacion, (candidatos.nombre||' '||candidatos.apellido) candidato, 
            cargo_eleccion, pp.nombre partido_politico, numero_mesa, cantidad_voto total_votos
            FROM public.cierre_partido_votacion cpv
            join public.candidatos_partido_politico cpp on cpp.id=cpv.candidatos_partido_politico_id
            join public.mesa on mesa.id=cpv.mesa_id
            join public.candidatos on candidatos.id=cpp.candidatos_id
            join public.partido_politico pp on pp.id=cpp.partido_politico_id
            join public.centro_votacion cv on cv.id=mesa.centro_votacion_id
            join public.parroquia pa on pa.id=cv.parroquia_id
            join public.municipio mu on mu.id=pa.municipio_id
            join public.eleccion on eleccion.id=cpv.eleccion_id
            where {$condition}
            group by mu.nombre, pa.nombre, cv.nombre, candidato, cargo_eleccion, numero_mesa, pp.nombre, cantidad_voto
            order by mu.nombre, pa.nombre, cv.nombre, numero_mesa;"
            ));
        }
        return response()->json($raw,200);
    }//cierrePartidoDetalle

}
