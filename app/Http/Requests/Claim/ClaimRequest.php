<?php

namespace Apps\Http\Requests\Claim;

use Illuminate\Foundation\Http\FormRequest;

class ClaimRequest extends FormRequest
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
            'date' => 'required',
            'lot' => 'nullable|max:100',
            'title' => 'required|min:1|max:100',
            'customer_id' => 'required|integer',
            'collaborator_id' => 'required|integer',
            'description' => 'required|min:1|max:750',
            'classification_id' => 'nullable|integer',            
        ];
    }

    public function attributes() {
        return [
            'date' => '"fecha"',
            'lot' => '"lote"',
            'title' => '"título"',
            'customer_id' => '"cliente"',
            'collaborator_id' => '"colaborador"',
            'description' => '"descripción"',
            'classification_id' => '"clacificación"',
        ];
    }    
}
