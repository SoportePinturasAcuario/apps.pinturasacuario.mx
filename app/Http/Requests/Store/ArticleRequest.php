<?php

namespace Apps\Http\Requests\Store;

use Illuminate\Foundation\Http\FormRequest;

class ArticleRequest extends FormRequest
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
            'articles' => 'array|min:1|max:9000',
            'articles.*.description' => 'required|min:2|max:100',
            'articles.*.weight' => 'required|numeric|min:0.01|max:10000',
            'articles.*.color_id' => 'nullable|integer',
            'articles.*.contents' => 'required|numeric|min:0.01|max:10000',
            'articles.*.special' => 'required|boolean',
            'articles.*.category_id' => 'required|integer',
            'articles.*.base_price' => 'required|numeric|min:1|max:1000000',
            'articles.*.type_of_finish_id' => 'nullable|integer',
            'articles.*.box_capacity' => 'nullable|integer|min:1|max:100',
            'articles.*.quality' => 'required|numeric|min:1|max:5',
            'articles.*.base_discount' => 'nullable|numeric|min:0|max:100',
            'articles.*.unit_of_measurement_id' => 'required|integer',
            'articles.*.code' => 'required|unique:store.articles,code,' . $this->id,
            'articles.*.id' => 'required|integer|unique:store.articles,id,' . $this->id,
        ];

        return $rules;
    }

    public function attributes() {
        return [
            'articles' => '"artículos"',
            'articles.*.id' => '"id"',
            'articles.*.code' => '"código"',
            'articles.*.color_id' => '"color"',
            'articles.*.quality' => '"calidad"',
            'articles.*.special' => '"especial"',
            'articles.*.base_discount' => '"descuento"',
            'articles.*.weight' => '"peso unitario"',
            'articles.*.base_price' => '"precio unitario"',
            'articles.*.category_id' => '"categoría"',
            'articles.*.contents' => '"contenido neto"',
            'articles.*.description' => '"descripción"',
            'articles.*.type_of_finish_id' => '"acabado"',
            'articles.*.box_capacity' => '"capacidad por caja"',
            'articles.*.unit_of_measurement_id' => '"unidad de medidad"',
        ];
    }    
}
