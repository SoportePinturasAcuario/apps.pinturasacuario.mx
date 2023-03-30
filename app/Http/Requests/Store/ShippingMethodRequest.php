<?php

namespace Apps\Http\Requests\Store;

use Illuminate\Foundation\Http\FormRequest;

class ShippingMethodRequest extends FormRequest
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
            'name' => 'required|unique:store.shipping_methods,name,' . $this->id,
            'idnetsuite' => 'required|integer|unique:store.shipping_methods,idnetsuite,' . $this->id,
        ];
    }

    public function attributes() {
        return [
            'name' => 'nombre',
            'idnetsuite' => 'Id NetSuite',
        ];
    }    
}
