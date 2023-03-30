<?php

namespace Apps\Http\Requests\Checker;

use Illuminate\Foundation\Http\FormRequest;

class BiometricRequest extends FormRequest
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
        $rules = [
            // 'collaborator_id' => 'required|integer|exists:nom.collaborators,id',
            'collaborator_id' => 'required|integer',
            'biometric' => 'array',
        ];

        return $rules;
    }

    public function attributes() {
        return [
            'collaborator_id' => '"número de empleado"',
        ];
    }    
}
