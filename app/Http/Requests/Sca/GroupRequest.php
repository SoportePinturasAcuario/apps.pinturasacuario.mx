<?php

namespace Apps\Http\Requests\Sca;

use Illuminate\Foundation\Http\FormRequest;

class GroupRequest extends FormRequest
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
            'name' => 'required|unique:sca.groups,name,' . $this->id,

            'collaborators' => 'array|required|min:1',
            'collaborators.*' => 'integer',
        ];
    }

    public function attributes() {
        return [
            'name' => '"nombre"',
            'checkers' => '"checadores"',
            'collaborators' => '"colaboradores"',
        ];
    }    
}
