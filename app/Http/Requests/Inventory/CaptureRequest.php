<?php

namespace Apps\Http\Requests\Inventory;

use Illuminate\Foundation\Http\FormRequest;

class CaptureRequest extends FormRequest
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
            'code' => 'required',
            'position' => 'required',
            'registered' => 'required|boolean',
            'upc' => 'nullable',
            'amount' => 'required|integer|min:1|max:10000000',
            'team_id' => 'required|integer|exists:inventory.teams,id',
            'inventory_id' => 'required|integer|exists:inventory.inventories,id',
        ];
    }

    public function attributes() {
        return [
            'code' => '"código"',
            'amount' => '"cantidad"',
            'team_id' => '"equipo_id"',
            'upc' => '"UPC"',
            'position' => '"posición"',
            'registered' => '"registrado"',
            'inventory_id' => '"inventario_id"',
        ];
    }    
}
