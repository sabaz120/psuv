<?php

namespace App\Http\Requests\RAAS\JefeComunidad;

use Illuminate\Foundation\Http\FormRequest;

class JefeComunidadUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "comunidad" => "required|exists:comunidad,id",
            "cedula" => "required",
            //"cedulaJefe" => "required|exists:personal_caracterizacion,cedula",
            "tipo_voto" => "required",
            "telefono_principal" => "nullable|max:11",
            "telefono_secundario" => "nullable|max:11",
            "partido_politico_id" => "required|exists:partido_politico,id",
            "movilizacion_id" => "required|exists:movilizacion,id",
            "rol_equipo_politico_id"=>"required|exists:roles_equipo_politico,id"
        ];
    }
}
