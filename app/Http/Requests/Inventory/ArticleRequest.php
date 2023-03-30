<?php

namespace Apps\Http\Requests\Inventory;

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
        return [
            'description' => "required|min:1|max:250",
            'box_capacity' => "nullable|integer|min:0|max:50",
            'upc' => "required|numeric|unique:inventory.articles,upc,$this->id",
            'code' => "required|min:1|max:20|unique:inventory.articles,code,$this->id",
            'idnetsuite' => "required|integer|unique:inventory.articles,idnetsuite,$this->id",
        ];
    }

    public function attributes()
    {
        return [
            'upc' => '"UPC"',
            'code' => '"Código"',
            'description' => '"Descripción"',
            'idnetsuite' => '"ID NetSuite"',
            'box_capacity' => '"Capacidad por caja"',
        ];
    }
}
