<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cargo as Model;

class CargoController extends Controller
{
    
    function all(Request $request){
        $tipo=$request->input('tipo');
        $query=Model::query();
        if(!is_null($tipo)){
            $query->where('tipo',$tipo);
        }
        $query->orderBy("nombre","ASC");
        $query=$query->get();
        return response()->json($query);

    }

}
