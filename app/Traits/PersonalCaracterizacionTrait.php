<?php 
namespace App\Traits;

use App\Models\PersonalCaracterizacion;
use App\Models\Elector;
use Auth;

trait PersonalCaracterizacionTrait
{
    public function storePersonalCaracterizacion($data)
    {
        $personalCaracterizacion = PersonalCaracterizacion::create($data->toArray());
        $personalCaracterizacion->municipio_id = Elector::where("cedula", $personalCaracterizacion->cedula)->first()->municipio_id;
        $personalCaracterizacion->parroquia_id = Elector::where("cedula", $personalCaracterizacion->cedula)->first()->parroquia_id;
        $personalCaracterizacion->centro_votacion_id = Elector::where("cedula", $personalCaracterizacion->cedula)->first()->centro_votacion_id;
        $personalCaracterizacion->update();

        return $personalCaracterizacion;

    }

    public function updatePersonalCaracterizacion($id, $data)
    {
  
        $personal = PersonalCaracterizacion::find($id);
        $personal->cedula = $data->cedula;
        $personal->nacionalidad = $data->nacionalidad;
        $personal->primer_apellido = $data->primer_apellido;
        $personal->segundo_apellido = $data->segundo_apellido;
        $personal->primer_nombre = $data->primer_nombre;
        $personal->segundo_nombre = $data->segundo_nombre;
        $personal->sexo = $data->sexo;
        $personal->telefono_principal = $data->telefono_principal;
        $personal->telefono_secundario = $data->telefono_secundario;
        $personal->fecha_nacimiento = $data->fecha_nacimiento;
        $personal->tipo_voto = $data->tipo_voto;
        $personal->inhabilitado_politicio = $data->inhabilitado_politico ? $data->inhabilitado_politico : 0;
        $personal->estado_id = $data->estado_id;
        //$personal->municipio_id = $data->municipio_id;
        //$personal->parroquia_id = $data->parroquia_id;
        //$personal->centro_votacion_id = $data->centro_votacion_id;
        $personal->partido_politico_id = $data->partido_politico_id;
        $personal->movilizacion_id = $data->movilizacion_id;
        $personal->update();

    }

}