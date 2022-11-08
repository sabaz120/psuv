<?php

namespace App\Http\Requests\RAAS\JefeCalle;

use Illuminate\Foundation\Http\FormRequest;
use \Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
class StoreRequest extends FormRequest
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
            "personal_caraterizacion" => "required",
            "tipo_voto" => "required",
            "telefono_principal" => "nullable|max:11",
            "telefono_secundario" => "nullable|max:11",
            "partido_politico_id" => "required|exists:partido_politico,id",
            "movilizacion_id" => "required|exists:movilizacion,id",
            "calle_id" => "required|exists:calle,id",
            "rol_equipo_politico_id"=>"required|exists:roles_equipo_politico,id"
        ];
    }
    
    protected function failedValidation(Validator $validator)
    {
        /**
         * @var array $response Is our response data.
         */
        $response = [
            "status" => 'Error', // Here I added a new field on JSON response.
            "message" =>"Existen elementos que no han sido seleccionados", // Here I used a custom message.
            "errors" => $validator->errors(), // And do not forget to add the common errors.
        ];

        // Finally throw the HttpResponseException.
        throw new HttpResponseException(response()->json($response, 422));
    }
}
