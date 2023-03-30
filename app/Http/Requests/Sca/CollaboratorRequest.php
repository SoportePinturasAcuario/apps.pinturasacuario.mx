<?php

namespace Apps\Http\Requests\Sca;

use Illuminate\Foundation\Http\FormRequest;

class CollaboratorRequest extends FormRequest
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
            'name' => 'required',
            'sucursal_id' => 'required|integer',
            'departamento_id' => 'required|integer',
            'sexo' => 'required|in:masculino,femenino',
            'folio' => 'required|unique:rh.collaborators,folio,' . $this->id,
        ];
    }

    public function attributes() {
        return [
            'name' => '"nombre"',
            'sexo' => '"género"',
            'sucursal_id' => '"sucursal"',
            'folio' => '"folio de empleado"',
            'departamento_id' => '"departamento"',
        ];
    }    
}
