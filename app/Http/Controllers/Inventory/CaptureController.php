<?php

namespace Apps\Http\Controllers\Inventory;

use Illuminate\Http\Request;
use Apps\Http\Controllers\Controller;
use Carbon\Carbon;

// Models
use Apps\Models\Inventory\Capture;
use Apps\Models\Inventory\Inventory;

// Requests
use Apps\Http\Requests\Inventory\CaptureRequest;

class CaptureController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CaptureRequest $request)
    {
        $request_data = array_merge(
            $request->all(),
            array_map('STRTOUPPER', $request->only(['code', 'amount', 'position', 'description']))
        );

        $capture = Capture::create($request_data);

        return response()->json([
            'data' => $capture,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \Apps\Models\Inventory\Capture  $capture
     * @return \Illuminate\Http\Response
     */
    public function show(Capture $capture)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Apps\Models\Inventory\Capture  $capture
     * @return \Illuminate\Http\Response
     */
    public function update_capture(CaptureRequest $request, $inventory_id, $capture_id)
    {
        $capture = Capture::findOrFail($capture_id);

        $request_data = array_merge(
            array_map('STRTOUPPER', $request->only(['code', 'amount', 'position', 'description'])),
            $request->only(['registered', 'box_capacity', 'upc'])
        );

        $capture->update($request_data);

        return response()->json([
            'data' => $capture,
        ]);
    }

    public function update(CaptureRequest $request, $capture_id)
    {
        $capture = Capture::findOrFail($capture_id);

        $request_data = array_merge(
            array_map('STRTOUPPER', $request->only(['code', 'amount', 'position', 'description'])),
            $request->only(['registered', 'box_capacity', 'upc']),
            [
                'change_applied' => true
            ]
        );

        $capture->update($request_data);

        return response()->json([
            'data' => $capture,
        ]);
    }     

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Apps\Models\Inventory\Capture  $capture
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Capture $capture) {
        $this->validate($request, [
            'reason_for_deleted' => 'required|min:1|max:250s'
        ], [], ['reason_for_deleted' => '"justificación de eliminación"']);

        $capture->update($request->only('reason_for_deleted'));

        $capture->delete();
    }

    public function change_request(Request $request, $capture_id) {
        $capture = Capture::findOrFail($capture_id);

        $this->validate($request, [
            'string_of_changes' => 'required|string',
        ]);

        $capture->update([
            'change_applied' => false,
            'change_requested' => Carbon::now(),
            'string_of_changes' => $request->get('string_of_changes'),
        ]);

        return response()->json([
            'data' => $capture,
        ]);        
    }
    
    public function query(Request $request, $inventory_id) {
        $this->validate($request, [
            'team_id' => 'nullable|integer',
            'code' => 'nullable|string|max:12',
            'position' => 'nullable|string|max:15',
            'reviewed' => 'nullable|in:true,false',
            'registered' => 'nullable|in:true,false',
            'onlytrashed' => 'nullable|in:true,false',
            'with_trashed' => 'nullable|in:true,false',
        ]);

        Inventory::findOrFail($inventory_id);

        $query = [['inventory_id', $inventory_id]];

        $request_data = array_map('STRTOUPPER', $request->only(['team_id', 'code', 'position']));

        if ($request->has('reviewed')) {
            $request_data['reviewed'] = ($request->get('reviewed') == 'true');
        }

        if ($request->has('registered')) {
            $request_data['registered'] = ($request->get('registered') == 'true');
        }        

        foreach ($request_data as $key => $value) {
            array_push($query, [$key, $value]);
        }

        if (filter_var($request->get('change_requested'), FILTER_VALIDATE_BOOLEAN)) {
            array_push($query, ['change_requested', '!=',  null]);
            array_push($query, ['change_applied', false]);
        }        

        if (filter_var($request->get('onlytrashed'), FILTER_VALIDATE_BOOLEAN)) {
            $captures = Capture::onlyTrashed()
            ->with('team', 'inventory')
            ->where($query)
            ->get();
        }else{
            $captures = Capture::withTrashed(filter_var($request->get('with_trashed'), FILTER_VALIDATE_BOOLEAN))
            ->with('team', 'inventory')
            ->where($query)
            ->get();            
        }

        return response()->json([
            'data' => $captures
        ]);          
    }

    public function mark_as_reviewed(Request $request, $capture_id) {
        $capture = Capture::with('inventory', 'team')->findOrFail($capture_id);

        $capture->update([
            'reviewed' => true,
        ]);

        return response()->json([
            'data' => $capture,
        ]);           
    }
}