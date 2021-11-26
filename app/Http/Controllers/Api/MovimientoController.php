<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Movimiento as Model;

class MovimientoController extends Controller
{
    
    function all(Request $request){

        $just_ids=$request->input('just_ids');
        $query=Model::query();
        if(!is_null($just_ids)){
            $just_ids=json_decode($just_ids);
            if(count($just_ids)>0)
            $query->whereIn('id',$just_ids);
        }
        $query->orderBy("nombre","ASC");
        $query=$query->get();
        return response()->json($query);

    }

}
