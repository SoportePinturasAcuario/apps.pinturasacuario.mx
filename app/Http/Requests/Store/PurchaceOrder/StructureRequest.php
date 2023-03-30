<?php

namespace Apps\Http\Requests\Store\PurchaceOrder;

use Illuminate\Foundation\Http\FormRequest;

class StructureRequest extends FormRequest
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
            'articles' => 'required|array|min:1|max:1000',
            'articles.*.codigo' => 'required|min:4|max:17|distinct|exists:store.articles,code',
            'articles.*.cantidad' => 'required|integer|min:1|max:17500',
        ];
    }

    public function messages() {
        $messages = [];

        foreach ($this->request->get('articles') as $key => $value) {
            $index = "#" . ($key + 2);

            // Código
            $messages['articles.'.$key.'.codigo.exists'] = 'El código: "'.$value['codigo'].'" es deconocido.';
            $messages['articles.'.$key.'.codigo.required'] = 'El valor en la columna "código" de la línea: '.$index.' es requerido.';
            $messages['articles.'.$key.'.codigo.distinct'] = 'El código: "'.$value['codigo'].'" está duplicado. Linea: '.$index;
            $messages['articles.'.$key.'.codigo.min'] = 'El valor en la columna "código" de la línea: '.$index.' debe contener al menos 4 caracteres.';
            $messages['articles.'.$key.'.codigo.max'] = 'El valor en la columna "código" de la línea: '.$index.' no debe ser mayor a 17 caracteres.';

            // Cantidad
            $messages['articles.'.$key.'.cantidad.required'] = 'El valor en la columna "cantidad" de la línea: '.$index.' es requerido.';
            $messages['articles.'.$key.'.cantidad.integer'] = 'El valor en la columna "cantidad" de la línea: '.$index.' debe ser un número entero.';
            $messages['articles.'.$key.'.cantidad.min'] = 'El valor en la columna "cantidad" de la línea: '.$index.' debe ser de por lo menos: 1.';
            $messages['articles.'.$key.'.cantidad.max'] = 'El valor en la columna "cantidad" de la línea: '.$index.' no debe ser mayor a: 17500.';

        }

        return $messages;
    }

    public function attributes() {
        return [
            'codigo' => '"código"',
            'cantidad' => '"cantidad"',
            'articles' => '"el listado de artículos"',
        ];
    }
}