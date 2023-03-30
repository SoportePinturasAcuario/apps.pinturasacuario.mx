<?php

namespace Apps\Http\Requests\Store;

use Illuminate\Foundation\Http\FormRequest;

class CustomerRequest extends FormRequest
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
            'discount' => 'nullable|numeric|min:0|max:100',
            'name' => 'required|unique:store.customers,name,' . $this->id,
            'folio' => 'required|unique:store.customers,folio,' . $this->id,
            'rfc' => [
                'required',
                'unique:store.customers,rfc,' . $this->id,
                'regex:/^([A-ZÑ\x26]{3,4}([0-9]{2})(0[1-9]|1[0-2])(0[1-9]|1[0-9]|2[0-9]|3[0-1])([A-Z]|[0-9]){2}([A]|[0-9]){1})?$/'
            ],
        ];

        if ($this->isMethod('post')) {
            $rules = array_merge($rules, [
                'id' => 'required|integer|unique:store.customers,id,',
            ]);
        }

        return $rules;
    }

    public function attributes() {
        return [
            'rfc' => '"RFC"',
            'id' => '"id"',
            'folio' => '"Folio"',
            'name' => '"Nombre"',
            'discount' => '"descuento"',
        ];
    }    
}
