<?php

namespace Apps\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
            'acount_type_id' => 'required|integer|min:1|max:2',
        ];

        if ($this->isMethod('post')) {
            
            $rules = array_merge($rules, [
                'email' => 'required|email|unique:users,email'
            ]);

            switch ($this->acount_type_id) {
                case 1:
                    // acount_type_id => 1 (internal)

                    $rules = array_merge($rules, [
                        'password' => 'required|min:3|max:20',
                        'collaborator_id' => 'required|integer|exists:nom.collaborators,id',
                    ]);

                    break;

                case 2:
                    // acount_type_id => 2 (customer)

                    $rules = array_merge($rules, [
                        'password'  => 'required|confirmed|min:8|max:20',
                        'rfc' => [
                            'required',
                            'regex:/^([A-ZÑ\x26]{3,4}([0-9]{2})(0[1-9]|1[0-2])(0[1-9]|1[0-9]|2[0-9]|3[0-1])([A-Z]|[0-9]){2}([A]|[0-9]){1})?$/',
                            'exists:store.customers,rfc'
                        ]
                    ]);
                    
                    break;
            }
        }

        return $rules;
    }

    public function attributes() {
        return [
            'rfc' => '"RFC"',
            'acount_type_id' => '"típo de cuenta"',
            'password_confirmation' => '"contraseña"'      
        ];
    }    
}
