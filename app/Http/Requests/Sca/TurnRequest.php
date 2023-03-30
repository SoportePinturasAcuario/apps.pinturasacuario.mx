<?php

namespace Apps\Http\Requests\Sca;

use Illuminate\Foundation\Http\FormRequest;

class TurnRequest extends FormRequest
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
            'name' => 'required|unique:sca.turns,name,' . $this->id,
            'note' => 'nullable|max:250',
            'events' => 'required|array|min:1|max:2',
            'events.*.pivot' => 'required|array',
            'events.*.pivot.start' => 'required|date_format:H:i',
            'events.*.pivot.end' => 'required|date_format:H:i',
            'events.*.pivot.duration' => 'required|numeric|min:0.5|max:24',
            'events.*.pivot.start_strict_after' => 'required|boolean',
            'events.*.pivot.start_strict_before' => 'required|boolean',
            'events.*.pivot.duration_strict' => 'required|boolean',
            'events.*.pivot.end_strict_after' => 'required|boolean',
            'events.*.pivot.end_strict_before' => 'required|boolean'
        ];

        return $rules;
    }

    // public function attributes() {
    //     return [
    //         'name' => '"nombre"',
    //         'description' => '"descripción"',
    //         'events' => '"eventos"',
    //         'events.*.pivot' => '"eventos.*.pivot"',
    //         'events.*.pivot.start' => '"eventos.*.pivot.hora de inicialización"',
    //         'events.*.pivot.end' => '"eventos.*.pivot.hora de finalización"',
    //         'events.*.pivot.duration' => '"eventos.*.pivot.duration"',
    //         'events.*.pivot.start_strict_after' => '"eventos.*.pivot.start_strict_after"',
    //         'events.*.pivot.start_strict_before' => '"eventos.*.pivot.start_strict_before"',
    //         'events.*.pivot.duration_strict' => '"eventos.*.pivot.duration_strict"',
    //         'events.*.pivot.end_strict_after' => '"eventos.*.pivot.end_strict_after"',
    //         'events.*.pivot.end_strict_before' => '"eventos.*.pivot.end_strict_before"',
    //     ]
    // }    
}
