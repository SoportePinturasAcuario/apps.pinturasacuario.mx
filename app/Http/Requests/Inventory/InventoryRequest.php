<?php

namespace Apps\Http\Requests\Inventory;

use Illuminate\Foundation\Http\FormRequest;

class InventoryRequest extends FormRequest
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
    public function rules() {
        return [
            'search_by' => 'required|in:code,upc',
            'amount_by' => 'required|in:piezas,cajas',
            'alter_capture_config' => 'nullable|boolean',
            'name' => 'required|unique:inventory.inventories,name,' . $this->id,
            'type_id' => 'required|integer|exists:inventory.inventory_types,id',
            'start_date' => 'required|regex:/^([0-9]{4}-[0-9]{2}-[0-9]{2} [0-9]{2}:[0-9]{2})$/',
        ];
    }

    public function attributes() {
        return [
            'name' => '"Nombre"',
            'type_id' => '"Típo"',
            'start_date'   => '"Fecha de inicio"',
            'start_date'   => '"Fecha de inicio"',
            'search_by'   => '"Campo para captura de artículo"',
            'amount_by'   => '"Campo para captura de cantidad"',
            'alter_capture_config'   => '"alterar configuración de captura"',
        ];
    }    
}
