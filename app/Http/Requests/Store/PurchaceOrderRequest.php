<?php

namespace Apps\Http\Requests\Store;

use Illuminate\Foundation\Http\FormRequest;

class PurchaceOrderRequest extends FormRequest
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
    public function rules() {
        $rules = [
            'note' => 'nullable|max:750',
            'articles' => 'required|array|min:1',
            'shipping_method_id' => 'nullable|integer',
            'articles.*.id' => 'required|integer|min:1',
            'articles.*.amount' => 'required|integer|min:1|max:17500',
        ];
        
        // 'articles.*.amount' => 'required|integer|min:1|max:9999',

        return $rules;
    }

    // public function messages() {
    //     return [
    //         'user_id.required' => 'El campo :attribute es obligatorio.',
    //     ];
    // }

    public function attributes() {
        return [
            'note' => '"nota"',
            'user_id'   => '"usuario"',
            'discount' => '"descuento"',
            'articles'  => '"artículos"',
            'customer_id'   => '"cliente"',
            'articles.*.price' => '"precio"',            
            'articles.*.amount' => '"la cantidad"',
            'articles.*.discount'   => '"descuento"',
            'shipping_method_id' => '"metodo de envío"',
        ];
    }
}
