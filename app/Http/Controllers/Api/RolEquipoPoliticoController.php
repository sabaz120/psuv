<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RolesEquipoPolitico as Model;

class RolEquipoPoliticoController extends Controller
{
    
    function index(Request $request){
        $query=Model::query();
        $query->orderBy("nombre_rol","ASC");
        $query=$query->get();
        return response()->json($query);

    }

}
