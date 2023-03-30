<?php

namespace Apps\Http\Requests\Bda;

use Illuminate\Foundation\Http\FormRequest;

class ModuleRequest extends FormRequest
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
            'name' => 'required|min:1|max:100',
            'course_id' => 'required|integer',
        ];
    }

    public function attributes()
    {
        return  [
            'name' => '"nombre"',
            'course_id' => '"curso"',    
        ];
    }
}
