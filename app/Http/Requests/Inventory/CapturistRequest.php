<?php

namespace Apps\Http\Requests\Inventory;

use Illuminate\Foundation\Http\FormRequest;

class CapturistRequest extends FormRequest
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
            'name' => 'required|min:1|unique:inventory.capturists,name,' . $this->id,
        ];
    }

    public function attributes() {
        return [
            'name' => '"nombre"',
        ];
    }    
}
