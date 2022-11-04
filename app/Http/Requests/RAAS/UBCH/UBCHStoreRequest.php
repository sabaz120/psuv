<?php

namespace App\Http\Requests\RAAS\UBCH;

use Illuminate\Foundation\Http\FormRequest;

class UBCHStoreRequest extends FormRequest
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
            "centro_votacion_id" => "required|exists:centro_votacion,id",
            "cedula" => "required",
            "tipo_voto" => "required",
            "telefono_principal" => "nullable|max:11",
            "telefono_secundario" => "nullable|max:11",
            "partido_politico_id" => "required|exists:partido_politico,id",
            "movilizacion_id" => "required|exists:movilizacion,id",
            "rol_equipo_politico_id"=>"required|exists:roles_equipo_politico,id"
        ];
    }
}
