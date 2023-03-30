<?php

namespace Apps\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
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
            'typeAuth' => 'required|in:"user/password","folio"',
            'credentials' => 'required|array',
        ];
    }

    public function attributes() {
        return [
            'credentials' => 'credenciales',
            'typeAuth' => 'el típo de autenticación',
        ];
    }    
}
