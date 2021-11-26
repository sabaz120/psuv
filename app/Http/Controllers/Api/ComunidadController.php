<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Comunidad\ComunidadStoreRequest;
use App\Http\Requests\Comunidad\ComunidadUpdateRequest;
use App\Models\Calle;
use App\Models\Comunidad;

class ComunidadController extends Controller
{
    
    function comunidadesByParroquia($parroquia){

        return response()->json(Comunidad::where("parroquia_id", $parroquia)->orderBy("nombre")->get());

    }

    function verificarNombreDuplicado($nombre, $parroquia_id){

        return Comunidad::where("parroquia_id", $parroquia_id)->where("nombre", strtoupper($nombre))->count();

    }

    function store(ComunidadStoreRequest $request){

        try{    

            if($this->verificarNombreDuplicado($request->nombre, $request->parroquia_id) > 0){
                return response()->json(["success" => false, "msg" => "Ésta comunidad ya existe"]);
            }

            $comunidad = new Comunidad;
            $comunidad->parroquia_id = $request->parroquia_id;
            $comunidad->nombre = strtoupper($request->nombre);
            $comunidad->save();

            return response()->json(["success" => true, "msg" => "Comunidad creada"]);

        }catch(\Exception $e){

            return response()->json(["success" => false, "msg" => "Ha ocurrido un problema", "err" => $e->getMessage()]);

        }

    }

    function update(ComunidadUpdateRequest $request){

        try{    

            if(Comunidad::where("parroquia_id", $request->parroquia_id)->where("nombre", strtoupper($request->nombre))->where("id", "<>", $request->id)->count() > 0){
                return response()->json(["success" => false, "msg" => "Ésta comunidad ya existe"]);
            }

            $comunidad = Comunidad::find($request->id);
            $comunidad->parroquia_id = $request->parroquia_id;
            $comunidad->nombre = strtoupper($request->nombre);
            $comunidad->update();

            return response()->json(["success" => true, "msg" => "Comunidad actualizada"]);

        }catch(\Exception $e){

            return response()->json(["success" => false, "msg" => "Ha ocurrido un problema", "err" => $e->getMessage()]);

        }

    }

    function delete(Request $request){

        try{    

            if(Calle::where("comunidad_id", $request->id)->count() > 0){
                return response()->json(["success" => false, "msg" => "No es posible eliminar debido a que hay calles asociadas"]);
            }

            $comunidad = Comunidad::find($request->id);
            $comunidad->delete();

            return response()->json(["success" => true, "msg" => "Comunidad eliminada"]);

        }catch(\Exception $e){

            return response()->json(["success" => false, "msg" => "Ha ocurrido un problema", "err" => $e->getMessage()]);

        }

    }

    function fetch(Request $request){

        $query = Comunidad::with("parroquia", "parroquia.municipio");
        
        if(\Auth::user()->municipio_id != null){
            $municipio_id = \Auth::user()->municipio_id;
            $query->whereHas("parroquia", function($q) use($municipio_id){
                $q->where('municipio_id', $municipio_id);
            });
        }

        $comunidades = $query->orderBy("nombre", "desc")->paginate(15);
        
        return response()->json($comunidades);

    }

    function search(Request $request){

        $query = Comunidad::where('nombre', 'like', '%' . strtoupper($request->search) . '%')->with("parroquia", "parroquia.municipio");
        
        if(\Auth::user()->municipio_id != null){
            $municipio_id = \Auth::user()->municipio_id;
            $query->whereHas("parroquia", function($q) use($municipio_id){
                $q->where('municipio_id', $municipio_id);
            });
        }

        $comunidades = $query->orderBy("id", "desc")->paginate(15);
        
        return response()->json($comunidades);

    }

}
