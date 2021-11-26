<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\NivelEstructura as Model;

class NivelEstructuraController extends Controller
{
    
    function all(Request $request){
        $query=Model::query();
        $query->orderBy("nombre_nivel","ASC");
        $query=$query->get();
        return response()->json($query);

    }

}
