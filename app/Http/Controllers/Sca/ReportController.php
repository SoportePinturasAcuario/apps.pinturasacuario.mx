<?php

namespace Apps\Http\Controllers\Sca;

use Illuminate\Http\Request;
use Apps\Http\Controllers\Controller;

// Models
use Apps\Models\Sca\Group;
use Apps\Models\Sca\Checker;
use Apps\Models\Sca\Register;

class ReportController extends Controller
{
    public function registers(Request $request)
    {
        $this->validate($request, [
            'date_start' => 'nullable|date',
            'date_end' => 'nullable|date',
            'checker_id' => 'nullable|numeric',
            'group_id' => 'nullable|numeric',
        ]);

        $data = Checker::with('groups')->find($request->get('checker_id'));

        return response()->json([
            'data' => $data,
        ]);
    }
}
