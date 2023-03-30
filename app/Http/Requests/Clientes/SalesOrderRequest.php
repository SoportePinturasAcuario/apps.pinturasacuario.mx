<?php

namespace Apps\Http\Requests\Clientes;

use Illuminate\Foundation\Http\FormRequest;

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
    public function rules()
    {
        return [
            'note' => 'max:500',
            'user_id' => 'required|integer',
            'shipping_method_id' => 'nullable|integer',
            'customer_id' => 'required|integer',
            'articles' => 'required|array|min:1',
            'articles.*.amount' => 'required|integer|min:1',
            'articles.*.discount' => 'required|numeric|min:0|max:100',
        ];
    }

    // public function messages() {
    //     return [
    //         'user_id.required' => 'El campo :attribute es obligatorio.',
    //     ];
    // }

    public function attributes() {
        return [
            'note' => 'nota',
            'user_id'   => 'usuario',
            'articles'  => 'artículos',
            'customer_id'   => 'cliente',
            'articles.*.amount' => 'cantidad',
            'articles.*.discount'   => 'descuento',
            'shipping_method_id' => 'metodo de envío',
        ];
    }
}
