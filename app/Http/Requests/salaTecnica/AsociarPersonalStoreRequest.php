<?php

namespace App\Http\Requests\salaTecnica;

use Illuminate\Foundation\Http\FormRequest;

class AsociarPersonalStoreRequest extends FormRequest
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
            "municipio" => "required",
            "nombre" => "required",
            "apellido" => "required",
            "cedula" => "required",
            "telefono" => "required",
            "rol" => "required",
        ];
    }
}
