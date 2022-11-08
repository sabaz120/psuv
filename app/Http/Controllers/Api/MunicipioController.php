<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Municipio as Model;

class MunicipioController extends Controller
{
    function all(Request $request){
        $municipio_id=$request->input('municipio_id');
        $estado_id=$request->input('estado_id');
        $estado_name=$request->input('estado_name');
        $result=Model::query();
        if($estado_id){
            $result->where('estado_id',$estado_id);
        }
        if($estado_name){
            $result->whereHas('estado',function($q)use($estado_name){
                $q->where("nombre","ILIKE",$estado_name);
            });
        }
        if($municipio_id){
            $result->where('id',$municipio_id);
        }
        $result->orderBy('nombre');
        $result=$result->get();
        return response()->json($result);

    }

}
