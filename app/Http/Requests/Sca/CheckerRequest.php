<?php

namespace Apps\Http\Requests\Sca;

use Illuminate\Foundation\Http\FormRequest;

class CheckerRequest extends FormRequest
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
            'name' => 'required|unique:sca.checkers,name,' . $this->id,
            'key' => 'required',
            'configuration' => 'nullable',
        ];
    }

    public function attributes() {
        return [
            'name' => '"nombre"',
            'key' => '"Llave"',
            'configuration' => '"configuración"',

        ];
    }  
}
