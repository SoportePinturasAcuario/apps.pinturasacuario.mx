<?php

namespace Apps\Http\Requests\Scanning;

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
            'articles' => 'required|array|min:1',
            'articles.*.code' => 'required|min:4|max:20|unique:scanning.articles,code,' . $this->id,
            'articles.*.upc' => 'required|numeric|digits_between:13,14|unique:scanning.articles,upc,' . $this->id,
            'articles.*.description' => 'required|string|max:250',
        ];
    }
}
