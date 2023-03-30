<?php

namespace Apps\Http\Requests\Store;

use Illuminate\Foundation\Http\FormRequest;

class ColorRequest extends FormRequest
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
            'name' => 'required|unique:store.colors,name,' . $this->id,
            'hex' => 'required|unique:store.colors,hex,' . $this->id, 
        ];
    }
}
