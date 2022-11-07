<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RolesEquipoPolitico as Model;

class RolEquipoPoliticoController extends Controller
{
    
    function index(Request $request){
        $nivel_territorial_id=$request->input("nivel_territorial_id");
        $query=Model::query();
        if($nivel_territorial_id){
            $query->whereHas("rolNivelTerritorial",function($q)use($nivel_territorial_id){
                $q->where("nivel_territorial_id",$nivel_territorial_id);
            });
        }
        $query->orderBy("nombre_rol","ASC");
        $query=$query->get();
        return response()->json($query);

    }

}
