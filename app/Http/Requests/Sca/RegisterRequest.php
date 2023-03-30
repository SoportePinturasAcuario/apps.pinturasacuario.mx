<?php

namespace Apps\Http\Requests\Sca;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'event_id' => 'required|integer',
            'type_id' => 'required|integer',
            'group_id' => 'required|integer',
            'checker_id' => 'required|integer',
            'registered_at' => 'required|date',
            'collaborator_id' => 'required|integer',
        ];
    }


    public function attributes() {
        return [
            'event_id' => '"evento"',
            'group_id' => '"groupo"',
            'checker_id' => '"instancia"',
            'type_id' => '"típo de registro"',
            'registered_at' => '"registrado en"',
            'collaborator_id' => '"colaborador"',
        ];
    }    
}
