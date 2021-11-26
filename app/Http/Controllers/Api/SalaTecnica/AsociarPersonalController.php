<?php

namespace App\Http\Controllers\Api\SalaTecnica;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\salaTecnica\AsociarPersonalStoreRequest;
use App\Models\PersonalSalaTecnica;

class AsociarPersonalController extends Controller
{
    
    function getPersonal(Request $request){

        if($request->municipio_id == 0){

            $personalSalaTecnica = PersonalSalaTecnica::with("municipio")->orderBy("municipio_id")->paginate(20);

        }else{

            $personalSalaTecnica = PersonalSalaTecnica::with("municipio")->where("municipio_id", $request->municipio_id)->orderBy("municipio_id")->paginate(20);

        }

        return response()->json($personalSalaTecnica);

    }

    function storePersonal(AsociarPersonalStoreRequest $request){

        try{

            if($this->validateCedulaStore($request->cedula) > 0){

                return response()->json(["success" => false, "msg" => "Ya existe otro personal de sala con esta cédula"]);

            }

            $personalSalaTecnica = new PersonalSalaTecnica;
            $personalSalaTecnica->municipio_id = $request->municipio;
            $personalSalaTecnica->nombre = strtoupper($request->nombre);
            $personalSalaTecnica->apellido = strtoupper($request->apellido);
            $personalSalaTecnica->cedula = $request->cedula;
            $personalSalaTecnica->telefono_principal = $request->telefono;
            $personalSalaTecnica->rol = $request->rol;
            $personalSalaTecnica->save();

            return response()->json(["success" => true, "msg" => "Personal agregado a la sala técnica"]);

        }catch(\Exception $e){

            return response()->json(["success" => false, "msg" => "Ha ocurrido un problema", "err" => $e->getMessage()]);

        }

    }

    function updatePersonal(AsociarPersonalStoreRequest $request){

        try{

            if($this->validateCedulaUpdate($request->cedula, $request->personalId) > 0){

                return response()->json(["success" => false, "msg" => "Ya existe otro personal de sala con esta cédula"]);

            }

            $personalSalaTecnica = PersonalSalaTecnica::find($request->personalId);
            $personalSalaTecnica->municipio_id = $request->municipio;
            $personalSalaTecnica->nombre = strtoupper($request->nombre);
            $personalSalaTecnica->apellido = strtoupper($request->apellido);
            $personalSalaTecnica->cedula = $request->cedula;
            $personalSalaTecnica->telefono_principal = $request->telefono;
            $personalSalaTecnica->rol = $request->rol;
            $personalSalaTecnica->update();

            return response()->json(["success" => true, "msg" => "Personal actualizado"]);

        }catch(\Exception $e){

            return response()->json(["success" => false, "msg" => "Ha ocurrido un problema", "err" => $e->getMessage()]);

        }

    }

    function deletePersonal(Request $request){

        try{

            $personalSalaTecnica = PersonalSalaTecnica::find($request->id);
            $personalSalaTecnica->delete();

            return response()->json(["success" => true, "msg" => "Personal eliminado"]);

        }catch(\Exception $e){

            return response()->json(["success" => false, "msg" => "Ha ocurrido un problema", "err" => $e->getMessage()]);

        }

    }

    function validateCedulaStore($cedula){

        $count = PersonalSalaTecnica::where("cedula", $cedula)->count();
        return $count;
    }

    function validateCedulaUpdate($cedula, $id){

        $count = PersonalSalaTecnica::where("cedula", $cedula)->where("id", "<>", $id)->count();
        return $count;
    }

    function searchPersonal(Request $request){

        if($request->municipio_id == 0){

            $personalSalaTecnica = PersonalSalaTecnica::with("municipio")->where("nombre", "like", '%'.strtoupper($request->searchText).'%')->orWhere("apellido", "like", '%'.strtoupper($request->searchText).'%')->orderBy("municipio_id")->paginate(20);

        }else{

            $personalSalaTecnica = PersonalSalaTecnica::with("municipio")->where("nombre", "like", '%'.strtoupper($request->searchText).'%')->orWhere("apellido", "like", '%'.strtoupper($request->searchText).'%')->orderBy("municipio_id")->where("municipio_id", $request->municipio_id)->paginate(20);

        }

        return response()->json($personalSalaTecnica);

    }

}
