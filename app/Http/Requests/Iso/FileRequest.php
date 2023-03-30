<?php

namespace Apps\Http\Requests\Iso;

use Illuminate\Foundation\Http\FormRequest;

class FileRequest extends FormRequest
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
            'files' => 'required',
            'files.*' => 'mimes:jpg,jpeg,png,pdf,doc,docx,docm,dotx,dotm,pptx,pptm,ppt,csv,xls,xlsx,xlsm,xlsb,xltx',
        ];
    }
}
