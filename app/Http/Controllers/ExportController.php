<?php

namespace Apps\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use Apps\Http\Requests\ExportRequest;


class ExportController extends Controller {
    public function export(ExportRequest $request){
        $mapType = $request->has('mapType') ? $request->get('mapType') : 'default';

        $fileName = $request->has('name') ? "{$request->get('name')}_" . time() : time();

        $path = "exports/" . $fileName. ".csv";

        $csv = fopen(public_path($path), 'w');

        switch ($mapType) {
            case 'vertical':
                $this->vertical($request, $csv);
                break;

            default:
                $this->default($request, $csv);
                break;
        }

        return response()->json([
            "success" => true,
            "data" => [
                "downloadUrl" => asset($path)
            ],
        ], 200);         
    }

    private function vertical($request, $csv) {
        $data = $request->get('data');

        $headers = array_keys($data);

        array_values($data);

        $counts = array_map(function($item) use ($data) {
            return count($data[$item]);
        }, $headers);

        rsort($counts);

        fputcsv($csv, $headers);

        for ($i=0; $i < $counts[0]; $i++) { 
            $row = array_map(function($header) use ($data, $i) {
                return isset($data[$header][$i]) ? $data[$header][$i] : null;
            }, $headers);

            fputcsv($csv, $row);
        }

        return fclose($csv);
    }


    public function csvtoarray(Request $request){
        $this->validate($request, [
            'file' => 'required|mimes:csv,txt|max:5120'
        ], [], ['file' => 'archivo']);

        $file = $request->file('file');

        $name = time() . ".csv";

        $path = "temp/$name";

        Storage::disk('local')->put($path,  \File::get($file));

        $csv = array_map('str_getcsv', file(public_path($path)));

        $headers = array_map('trim', $csv[0]);

        array_shift($csv);

        $data = [];

        foreach ($csv as $key => $row) {
            $row = array_map(function($item){
                return utf8_encode(strtoupper(trim($item)));
            }, $row);

            $combine = array_combine($headers, $row);
            
            array_push($data, $combine);
        }

        Storage::disk('local')->delete($path);

        return response()->json([
            'data' => $data,
        ]);        
    }   

    private function default($request, $csv) {
        $data = collect($request->get('data'));

        $headers = collect($data->first())->keys();
        
        fputcsv($csv, array_map('utf8_decode', $headers->toArray()));

        foreach ($data as $key => $row) {
            fputcsv($csv, array_map('utf8_decode', collect($row)->values()->toArray()));
        }

        return fclose($csv);
    }
}
