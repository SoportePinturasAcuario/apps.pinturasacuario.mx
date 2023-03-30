<?php

namespace Apps\Http\Requests\Store;

use Illuminate\Foundation\Http\FormRequest;

class ArticlePriceListRequest extends FormRequest
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
            'article_price_list' => 'required|array|min:1',
            'article_price_list.*.id' => 'required|integer',
            'article_price_list.*.preferential_price' => 'required|numeric|min:0.00001|max:1000000',
            'article_price_list.*.preferential_discount' => 'required|numeric|min:0|max:100',
        ];
    }

    public function messages() {
        $messages = [];

        foreach ($this->request->get('article_price_list') as $key => $value) {
            $index = "#" . ($key + 2);

            // Id
            $messages["article_price_list.$key.id.required"] = 'El valor en la columna "id" en la línea: '.$index.' es requerido.';
            $messages["article_price_list.$key.id.integer"] = 'El valor en la columna "id" en la línea: '.$index.' debe ser un valor entero.';

            // Preferential Price
            $messages["article_price_list.$key.preferential_price.required"] = 'El valor en la columna "preferential_price" en la línea: '.$index.' es requerido.';
            $messages["article_price_list.$key.preferential_price.numeric"] = 'El valor en la columna "preferential_price" en la línea: '.$index.' debe ser un valor numérico.';
            $messages["article_price_list.$key.preferential_price.min"] = 'El valor en la columna "preferential_price" en la línea: '.$index.' debe ser de por lo menos: 0.';
            $messages["article_price_list.$key.preferential_price.max"] = 'El valor en la columna "preferential_price" en la línea: '.$index.' debe ser menor o igual a: 1000000.';

            // Preferential Dicount
            $messages["article_price_list.$key.preferential_discount.required"] = 'El valor en la columna "preferential_discount" en la línea: '.$index.' es requerido.';
            $messages["article_price_list.$key.preferential_discount.numeric"] = 'El valor en la columna "preferential_discount" en la línea: '.$index.' debe ser un numérico.';
            $messages["article_price_list.$key.preferential_discount.min"] = 'El valor en la columna "preferential_discount" en la línea: '.$index.' debe ser de por lo menos: 0.';
            $messages["article_price_list.$key.preferential_discount.max"] = 'El valor en la columna "preferential_discount" en la línea: '.$index.' debe ser menor o igual a: 100.';
        }

        return $messages;
    }  
}
