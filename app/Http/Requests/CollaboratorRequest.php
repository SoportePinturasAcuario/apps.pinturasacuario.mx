<?php

namespace Apps\Http\Requests;

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
            'name' => 'required|min:2|max:75',
            'sexo' => 'required|in:MASCULINO,FEMENINO',
            'ec' => 'nullable|max:25',
            'rturno' => 'nullable|',
            'ocupacion' => 'nullable|max:75',
            'nexperiencia' => 'nullable|numeric|min:.1|max:100',
            'meses' => 'nullable|numeric',
            'departamento_id' => 'required|numeric',
            'sucursal_id' => 'required|numeric',
            'tipopuesto_id' => 'nullable|numeric',
            'tipocontratacion_id' => 'nullable|numeric',
            'tipopersonal_id' => 'nullable|numeric',
            'tipojornada_id' => 'nullable|numeric',
            'tipoacademico_id' => 'nullable|numeric',
            'folio' => 'required|max:8|unique:rh.collaborators,folio,' . $this->id,
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
