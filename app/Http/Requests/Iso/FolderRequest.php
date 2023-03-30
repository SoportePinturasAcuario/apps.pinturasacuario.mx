<?php

namespace Apps\Http\Requests\Iso;

use Illuminate\Foundation\Http\FormRequest;

class FolderRequest extends FormRequest
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
            'name' => 'required|regex:/^([a-zA-Z,0-9,á,é,í,ó,ú,Á,É,Í,Ó,Ú, ]){1,40}$/',
            'config' => 'required',
        ];
    }
}
