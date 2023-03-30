<?php

namespace Apps\Http\Controllers\Checker;

use Illuminate\Http\Request;
use Apps\Http\Controllers\Controller;

// Models
use Apps\Collaborator;
use Apps\Models\Checker\Biometric;

// Requests
use Apps\Http\Requests\Checker\BiometricRequest;

class BiometricController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Biometric::with('collaborator')->get();

        $data = array_map(function($item) {
            
            $item['descriptor'] = collect(json_decode($item['descriptor']))->toArray();

            return $item;
        }, $data->toArray());

        return response()->json([
            'data' => $data
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // code...
    }

    /**
     * Display the specified resource.
     *
     * @param  \Apps\Models\Checker\Biometric  $biometric
     * @return \Illuminate\Http\Response
     */
    public function show(Biometric $biometric)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Apps\Models\Checker\Biometric  $biometric
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Biometric $biometric)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Apps\Models\Checker\Biometric  $biometric
     * @return \Illuminate\Http\Response
     */
    public function destroy(Biometric $biometric)
    {
        //
    }

    public function insert(BiometricRequest $request) {

        $collaborator = Collaborator::find($request->get('collaborator_id'));

        if ($collaborator) {
            if ($collaborator->biometric) {
                $biometric = $collaborator->biometric()->update($request->get('biometric'));
            }else{
                $biometric = $collaborator->biometric()->create($request->get('biometric'));
            }
        }

        return response()->json([
            'success' => true,
        ]);
    }    
}
