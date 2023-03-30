<?php

namespace Apps\Http\Requests\Store;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class SalesOrderRequest extends FormRequest
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
            'discount' => 'required|numeric|min:0|max:100',

            'articles.*.id' => 'required|integer|min:1',
            'articles.*.amount' => 'required|integer|min:1|max:50999',

            'articles.*.price' => 'required|numeric|min:1|max:99999',
            'articles.*.discount' => 'required|numeric|min:0|max:99999',

            'user_id' => 'required|integer',
            'customer_id' => 'required|integer',

            'articles.*.base_price' => 'required|numeric|min:1|max:99999',
            'articles.*.base_discount' => 'required|numeric|min:0|max:100',

            'articles.*.preferential_price' => 'nullable|numeric|min:0|max:99999',
            'articles.*.preferential_discount' => 'nullable|numeric|min:0|max:100',            
        ];

        return $rules;
    }

    public function attributes() {
        return [
            'note' => 'nota',
            'user_id'   => 'usuario',
            'discount' => 'descuento',
            'articles'  => 'artículos',
            'customer_id'   => 'cliente',
            'articles.*.price' => 'precio',            
            'articles.*.amount' => 'la cantidad',
            'articles.*.discount'   => 'descuento',
            'shipping_method_id' => 'metodo de envío',
        ];
    }
}
