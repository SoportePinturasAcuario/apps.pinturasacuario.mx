<?php

namespace Apps\Http\Requests\Bda;

use Illuminate\Foundation\Http\FormRequest;

class CourseRequest extends FormRequest
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
            'title' => 'required|min:1|max:100',
            'subtitle' => 'required|min:1|max:75',
            'description' => 'required|min:1|max:1000',
            'image' => 'required|mimes:jpg,jpeg,png,gif|max:3000',
        ];

        if ($this->isMethod('put') || $this->isMethod('path')) {
            $rules = array_merge($rules, [
                'image' => 'mimes:jpg,jpeg,png,gif|max:3000',
            ]);
        }

        return $rules;
    }

    public function attributes()
    {
        return [
            'title' => '"título"',
            'image' => '"imagen"',
            'subtitle' => '"subtítulo"',
            'metadata' => '"metadatos"',
            'description' => '"descripción"',
        ];
    }
}
