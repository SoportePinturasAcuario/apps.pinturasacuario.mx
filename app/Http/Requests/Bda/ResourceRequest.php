<?php

namespace Apps\Http\Requests\Bda;

use Illuminate\Foundation\Http\FormRequest;

class ResourceRequest extends FormRequest
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
            'name' => 'required|min:1|max:200',
            'description' => 'required|max:250',
            'module_id' => 'required|integer',
            'index' => 'required|integer',
            'file' => 'required|mimes:mp4,mp3,jpg,jpeg,png,gif,pdf'
        ];
    }

    public function attributes()
    {
        return [
            'name' => '"nombre"',
            'description' => '"descripción"',
            'module_id' => '"módulo"',
            'index' => '"indice"',
            'file' => '"el archivo"',
        ];
    }
}
