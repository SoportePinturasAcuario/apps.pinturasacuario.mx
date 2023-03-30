<?php

namespace Apps\Http\Requests\Claim;

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
            'name' => 'required|unique:claims.customers,name,' . $this->id,
            'folio' => 'required|integer|between:1,99999|unique:claims.customers,folio,' . $this->id,
            'rfc' => [
                'required',
                'regex:/^([A-ZÑ\x26]{3,4}([0-9]{2})(0[1-9]|1[0-2])(0[1-9]|1[0-9]|2[0-9]|3[0-1])([A-Z]|[0-9]){2}([A]|[0-9]){1})?$/',
                'unique:claims.customers,rfc,' . $this->id,
            ],
        ];

        return $rules;
    }
}
