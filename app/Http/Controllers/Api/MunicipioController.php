<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Municipio as Model;

class MunicipioController extends Controller
{
    function all(Request $request){
        $municipio_id=$request->input('municipio_id');
        $result=Model::query();
        if($municipio_id){
            $query->where('id',$municipio_id);
        }
        $result->orderBy('nombre');
        $result=$result->get();
        return response()->json($result);

    }
}
