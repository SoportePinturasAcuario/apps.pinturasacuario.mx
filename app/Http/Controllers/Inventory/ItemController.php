<?php
namespace Apps\Http\Controllers\Inventario;

use Illuminate\Http\Request;
use Apps\Item;
use Apps\Http\Controllers\Controller;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // $items = Item::latest()->where('equipo', $request->get('equipo'))->take(3)->get();

        if ($request->has("equipo")) {

            $items = Item::latest()->where('equipo', $request->get('equipo'))->get();
        }else{

            $items = Item::all();
        }

        return response()->json([
            'success' => true,
            'data' => $items
        ], 200); 
    }

    // Domingo
    public function domingo(Request $request)
    {
        $items = Item::latest()->where('created_at', '<', '2020-09-14 00:00:00')->get();

        return response()->json([
            'success' => true,
            'data' => $items
        ], 200); 
    }

    // Lunes
    public function lunes(Request $request)
    {
        $items = Item::latest()->where('created_at', '>', '2020-09-14 00:00:00')->get();

        return response()->json([
            'success' => true,
            'data' => $items
        ], 200); 
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $item = Item::create($request->all());

        return response()->json([
            'success' => true,
            'data' => $item
        ], 200); 
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, item $item)
    {
        $item->update($request->all());

        return response()->json([
            'success' => true,
            'data' => $item
        ], 200); 
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function check(Request $request, item $item)
    {
        $item->update($request->all());

        return response()->json([
            'success' => true,
            'data' => $item
        ], 200); 
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function template(Request $request){
        set_time_limit(500);

        $items = Item::where('registrado', true)->get();
        $time = time();
        
        $codigos = $items->map(function($item){
            return $item->codigo;
        })->unique();        

        $csv = fopen(public_path('storage/inventario/Template.csv'), 'w');

        fputcsv($csv, [
            'ID externo',
            'NOMBRE NO MAPEAR',
            'ARTICULO',
            'DESCRIPCION NO MAPEAR',
            'FECHA ',
            'PERIODO CONTABLE',
            'ubicacion de Ajuste',
            'ubicacion',
            'CUENTA DE AJUSTE',
            'CANTIDAD NUEVA',
            'CANTIDAD DE LOTE',
            'DEPOSITO',
            'LOTE',
            'DEPARTAMENTO GENERAL',
            'DEPARTAMENTO LINEA',
            'SUBSIDIARIA',
            'ESTADO ',
        ]);



        foreach ($this->data($codigos, $items) as $key => $row) {

            if (empty($row['familia'])) {
                $nombre = $row['codigo']; 
                $articulo = $row['codigo'] . ' ' . utf8_decode($row['descripcion']); 
            }else{
                $nombre = $row['familia'] . ' : ' . $row['codigo']; 
                $articulo = $row['familia'] . ' : ' . $row['codigo'] . " " . utf8_decode($row['descripcion']); 
            }

            fputcsv($csv, [
                $time, // 'ID externo' 
                $nombre, // 'NOMBRE NO MAPEAR'   
                $articulo, // 'ARTICULO'   
                utf8_decode($row['descripcion']), // 'DESCRIPCION NO MAPEAR'  
                '13/09/2020', // 'FECHA ' 
                '01/09/2020', // 'PERIODO CONTABLE'   
                'CENTRO DE DISTRIBUCION LERMA PT', // 'ubicacion de Ajuste'    
                'CEDIS PT', // 'ubicacion'  
                '1096-001 CUENTA DE AJUSTE', // 'CUENTA DE AJUSTE'   
                $row['total'], // 'CANTIDAD NUEVA' 
                $row['total'], // 'CANTIDAD DE LOTE'   
                'PISO', // 'DEPOSITO'   
                '4092020', // 'LOTE'   
                'CEDIS', // 'DEPARTAMENTO GENERAL'   
                'CEDIS', // 'DEPARTAMENTO LINEA' 
                'INDUSTRIAL TECNICA DE PINTURAS', // 'SUBSIDIARIA'    
                'GOOD', // 'ESTADO '    
            ]);
        }

        fclose($csv);

        return response()->json([
            'success' => true,
            'data' => [
                'downloadUrl' => asset('storage/inventario/Template.csv')
            ],
        ], 200);        
    }

    private function data($articulos, $items){
        $data = [];
        
        set_time_limit(600);

        foreach ($articulos as $key => $articulo) {
            $rows = [];
            $total = 0;
            $posiciones = [];
            $equipos = [];


            foreach ($items->whereIn('codigo', $articulo->codigo) as $key => $row) {
                array_push($rows, $row);

                $total = $total + (int)$row['cantidad'];
                
                array_push($posiciones, $row['posicion']);
                
                array_push($equipos, $row['equipo']);
            }

            array_push($data, [
                'codigo' => $articulo->codigo,
                'familia' => $rows[0]['familia'],
                'um' => 'Pieza',
                'posiciones' => $posiciones,
                'equipos' => $equipos,
                'um' => 'Pieza',
                'descripcion' => $rows[0]['descripcion'],
                'registrado' => $rows[0]['registrado'],
                'total' => $total,
                'factor' => 1,
                'rows' => $rows,
            ]);
        }

        return $data;
    }

    public function concentrado(){
        $items = Item::all();

        $articulos = Item::select('codigo')->groupBy('codigo')->get();

        $data = $this->data($articulos, $items);

        return response()->json([
            'success' => true,
            'data' => array_values($data)
        ], 200);
    }

    public function concentradoDomingo(){
        $items = Item::where('created_at', '<', '2020-09-14')->get();

        $articulos = Item::select('codigo')
        ->where('created_at', '<', '2020-09-14')
        ->groupBy('codigo')
        ->get();

        $data = $this->data($articulos, $items);

        return response()->json([
            'success' => true,
            'data' => array_values($data)
        ], 200);
    }

    public function concentradoLunes(){
        $items = Item::where('created_at', '>', '2020-09-13')->get();

        $articulos = Item::select('codigo')
        ->where('created_at', '>', '2020-09-13')
        ->groupBy('codigo')
        ->get();

        $data = $this->data($articulos, $items);

        return response()->json([
            'success' => true,
            'data' => array_values($data)
        ], 200);
    }    
}
