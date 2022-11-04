<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Estado as Model;

class EstadoController extends Controller
{
    function all(Request $request){
        $result=Model::query();
        $result->orderBy('nombre');
        $result=$result->get();
        return response()->json($result);

    }

}
