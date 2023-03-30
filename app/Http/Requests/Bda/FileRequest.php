<?php

namespace Apps\Http\Requests\Bda;

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
            'fileable_type' => 'required|string',
            'fileable_id' => 'required|integer',
            'files' => 'required|array|min:1|max:10',
            'files.*' => 'mimes:mp4,mp3,gif,jpg,jpeg,png,pdf,doc,docx,docm,dotx,dotm,pptx,pptm,ppt,csv,xls,xlsx,xlsm,xlsb,xltx'
        ];
    }

    public function attributes()
    {
        return [
            'fileable_type' => '"típo archivable"',
            'fileable_id' => '"id archivable"',
            'files' => '"archivos"',
        ];
    }
}
